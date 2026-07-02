/**
 * MediaPipe Pose Landmarker — browser-side movement tracking.
 * Runs entirely in-browser via WASM. No server-side ML processing.
 */
import {
    PoseLandmarker,
    FilesetResolver,
    DrawingUtils,
} from '@mediapipe/tasks-vision';

// ---------------------------------------------------------------------------
// Joint definitions (MediaPipe 33-point model)
// Each entry: [landmark_a, vertex_b, landmark_c] — angle is measured at b
// ---------------------------------------------------------------------------
const JOINT_MAP = {
    right_shoulder: [24, 12, 14], // right_hip → right_shoulder → right_elbow
    left_shoulder:  [23, 11, 13],
    right_elbow:    [12, 14, 16], // right_shoulder → right_elbow → right_wrist
    left_elbow:     [11, 13, 15],
    right_knee:     [24, 26, 28], // right_hip → right_knee → right_ankle
    left_knee:      [23, 25, 27],
    right_hip:      [12, 24, 26], // right_shoulder → right_hip → right_knee
    left_hip:       [11, 23, 25],
};

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------
function calcAngle(a, b, c) {
    const radians =
        Math.atan2(c.y - b.y, c.x - b.x) -
        Math.atan2(a.y - b.y, a.x - b.x);
    let angle = Math.abs((radians * 180) / Math.PI);
    if (angle > 180) angle = 360 - angle;
    return Math.round(angle);
}

// ---------------------------------------------------------------------------
// Sound effects — synthesized with the Web Audio API (no asset files).
// Unlocked from the Start Tracking button gesture so autoplay rules are met.
// ---------------------------------------------------------------------------
function initAudio() {
    if (!audioCtx) {
        const AC = window.AudioContext || window.webkitAudioContext;
        if (AC) audioCtx = new AC();
    }
    if (audioCtx && audioCtx.state === 'suspended') audioCtx.resume();

    // Preload the per-rep sound during the user gesture so it plays instantly.
    if (!repAudio && cfg.repSound) {
        repAudio = new Audio(cfg.repSound);
        repAudio.preload = 'auto';
        repAudio.load();
    }
}

function playTone(freq, startTime, duration, peakGain = 0.2) {
    if (!audioCtx) return;
    const osc = audioCtx.createOscillator();
    const gain = audioCtx.createGain();
    osc.type = 'sine';
    osc.frequency.value = freq;
    // Quick attack, exponential decay — avoids clicks.
    gain.gain.setValueAtTime(0.0001, startTime);
    gain.gain.exponentialRampToValueAtTime(peakGain, startTime + 0.01);
    gain.gain.exponentialRampToValueAtTime(0.0001, startTime + duration);
    osc.connect(gain).connect(audioCtx.destination);
    osc.start(startTime);
    osc.stop(startTime + duration + 0.02);
}

// Play the custom sound each time the patient hits the degree target (a rep).
function playRepBeep() {
    if (!repAudio) return;
    try {
        repAudio.currentTime = 0;
        repAudio.play().catch(() => {});
    } catch (_) { /* ignore playback errors */ }
}

// Ascending arpeggio when the overall rep target is reached.
function playSuccessChime() {
    if (!audioCtx) return;
    const now = audioCtx.currentTime;
    [523.25, 659.25, 783.99, 1046.5].forEach((f, i) => playTone(f, now + i * 0.12, 0.18));
}

// Two-note "ready" cue played when initial position validation passes.
function playReadyTone() {
    if (!audioCtx) return;
    const now = audioCtx.currentTime;
    playTone(440, now,       0.15, 0.3); // A4
    playTone(660, now + 0.2, 0.25, 0.3); // E5
}

// ---------------------------------------------------------------------------
// Voice cues — Web Speech API
// Spoken only on state transitions; same text is suppressed within 4 seconds.
// ---------------------------------------------------------------------------
let lastSpokenText = '';
let speechCooldownUntil = 0;
let idVoice = null;

function loadIdVoice() {
    const voices = window.speechSynthesis?.getVoices() ?? [];
    idVoice = voices.find(v => v.lang.startsWith('id')) ?? null;
}

if (window.speechSynthesis) {
    window.speechSynthesis.addEventListener('voiceschanged', loadIdVoice);
    loadIdVoice();
}

function speakCue(text) {
    if (!window.speechSynthesis || !text) return;
    const now = Date.now();
    if (text === lastSpokenText && now < speechCooldownUntil) return;
    lastSpokenText = text;
    speechCooldownUntil = now + 4000;
    try {
        window.speechSynthesis.cancel();
        const utt = new SpeechSynthesisUtterance(text);
        utt.rate   = 0.9;
        utt.volume = 0.85;
        utt.lang   = 'id-ID';
        if (idVoice) utt.voice = idVoice;
        window.speechSynthesis.speak(utt);
    } catch (_) { /* speech unavailable in this browser */ }
}

function speakTutorialCue(text, onEnd) {
    if (!window.speechSynthesis || !text) { if (onEnd) onEnd(); return; }
    try {
        window.speechSynthesis.cancel();
        const utt = new SpeechSynthesisUtterance(text);
        utt.rate   = 0.9;
        utt.volume = 0.85;
        utt.lang   = 'id-ID';
        if (idVoice) utt.voice = idVoice;
        if (onEnd) utt.onend = onEnd;
        window.speechSynthesis.speak(utt);
    } catch (_) { if (onEnd) onEnd(); }
}

// ---------------------------------------------------------------------------
// State
// ---------------------------------------------------------------------------
const cfg = window.REHAB_CONFIG || {};
const i18n = cfg.i18n || {};
let poseLandmarker = null;
let videoStream = null;
let animFrameId = null;
let livewireComponent = null;

let repCount = 0;
let stage = 'down'; // 'up' | 'down'
let lastRepLogged = 0; // guard against duplicate logging
let celebrated = false; // fire the target-reached celebration only once
let audioCtx = null; // Web Audio context for the target-reached chime
let repAudio = null; // <audio> element for the per-rep degree-target sound

// Position validation state
let isValidating = false;
let validFrames = 0;
const VALIDATION_FRAMES = 45;        // ~1.5s at 30fps
const VALIDATION_TOLERANCE = 20;     // degrees above minAngle still accepted as "rest"
const MIN_LANDMARK_VISIBILITY = 0.5; // MediaPipe visibility threshold

// DOM refs (assigned after DOMContentLoaded)
let videoEl, canvasEl, canvasCtx, repDisplay, angleDisplay, statusDisplay, placeholder, startBtn;
let feedbackEl, feedbackIcon, feedbackText, confettiCanvas;
let progressBar, progressLabel, cameraStage, fullscreenIcon;
let hudReps, hudAngle, hudProgress, hudCue, hudCueText, hudCueIcon;
let validationContainer, validationBar, validationLabel;
let hudAnimation;

// ---------------------------------------------------------------------------
// i18n helper — pulls localized strings from REHAB_CONFIG, interpolates :angle
// ---------------------------------------------------------------------------
function t(key, replacements = {}) {
    let str = i18n[key] ?? '';
    for (const [k, v] of Object.entries(replacements)) {
        str = str.replace(`:${k}`, v);
    }
    return str;
}

// ---------------------------------------------------------------------------
// Real-time corrective feedback banner
// tone: 'good' (green) | 'correct' (amber) | 'none' (gray)
// Re-applied every frame so it survives Livewire DOM morphs.
// ---------------------------------------------------------------------------
const FEEDBACK_TONES = {
    good:    { box: 'bg-green-50 border-green-200 text-green-700', icon: 'fas fa-circle-check text-xl', hudBox: 'bg-green-600/80',  hudIcon: 'fa-circle-check' },
    correct: { box: 'bg-amber-50 border-amber-200 text-amber-700', icon: 'fas fa-arrows-up-down text-xl', hudBox: 'bg-amber-500/90', hudIcon: 'fa-arrows-up-down' },
    none:    { box: 'bg-gray-50 border-gray-200 text-gray-600',    icon: 'fas fa-circle-info text-xl',   hudBox: 'bg-gray-900/70',  hudIcon: 'fa-circle-info' },
};

function setFeedback(tone, text, hudIcon) {
    const style = FEEDBACK_TONES[tone] || FEEDBACK_TONES.none;
    if (feedbackEl) {
        feedbackEl.className =
            'rounded-xl p-4 flex items-center gap-3 border transition-colors ' + style.box;
        if (feedbackIcon) feedbackIcon.className = style.icon;
        if (feedbackText) feedbackText.textContent = text;
    }
    // Mirror onto the prominent fullscreen HUD cue pill.
    if (hudCueText) hudCueText.textContent = text;
    if (hudCue) {
        hudCue.className =
            'mx-auto inline-flex items-center gap-3 rounded-2xl px-5 py-3 backdrop-blur-sm transition-colors ' + style.hudBox;
    }
    if (hudCueIcon) {
        hudCueIcon.className = 'fas ' + (hudIcon || style.hudIcon) + ' text-2xl sm:text-4xl text-white';
    }
}

// ---------------------------------------------------------------------------
// Live progress bar (reps toward target) — drives the page bar + fullscreen HUD
// ---------------------------------------------------------------------------
function updateProgress(rep) {
    const target = cfg.targetReps || 0;
    const pct = target > 0 ? Math.min(100, Math.round((rep / target) * 100)) : 0;

    if (progressBar) progressBar.style.width = pct + '%';
    if (hudProgress) hudProgress.style.width = pct + '%';
    if (progressLabel) progressLabel.textContent = `${rep} / ${target} · ${pct}%`;
    if (hudReps) hudReps.textContent = `${rep} / ${target}`;
}

// ---------------------------------------------------------------------------
// MediaPipe init
// ---------------------------------------------------------------------------
async function initMediaPipe() {
    const vision = await FilesetResolver.forVisionTasks(
        'https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.14/wasm'
    );
    poseLandmarker = await PoseLandmarker.createFromOptions(vision, {
        baseOptions: {
            modelAssetPath:
                'https://storage.googleapis.com/mediapipe-models/pose_landmarker/pose_landmarker_lite/float16/1/pose_landmarker_lite.task',
            delegate: 'GPU',
        },
        runningMode: 'VIDEO',
        numPoses: 1,
    });
}

// ---------------------------------------------------------------------------
// Rep counter state machine
// ---------------------------------------------------------------------------
function processAngle(angle) {
    const { minAngle, maxAngle } = cfg;

    if (angle <= minAngle && stage === 'up') {
        // Completed full cycle: up → down — ready for next rep.
        stage = 'down';
        speakCue(t('voiceRaise') || 'Angkat');
    } else if (angle >= maxAngle && stage === 'down') {
        // Completed upswing: down → up — the patient hit the degree target.
        stage = 'up';
        repCount += 1;
        playRepBeep();
        logRepToServer(repCount, angle);
        speakCue(t('voiceLower') || 'Turunkan');
    }
}

// ---------------------------------------------------------------------------
// Server logging — debounced to once per completed rep
// ---------------------------------------------------------------------------
function logRepToServer(rep, angle) {
    if (rep === lastRepLogged) return;
    lastRepLogged = rep;

    const component = getLivewireComponent();
    if (!component) return;

    component.call('logMovement', { rep, angle });

    // Update DOM
    if (repDisplay) repDisplay.textContent = rep;
    updateProgress(rep);

    // Celebrate once when the patient reaches the target rep count.
    if (!celebrated && cfg.targetReps && rep >= cfg.targetReps) {
        celebrated = true;
        celebrate();
    }
}

function getLivewireComponent() {
    if (livewireComponent) return livewireComponent;
    // Find the Livewire component by looking for the element with wire:id
    const el = document.querySelector('[wire\\:id]');
    if (!el) return null;
    const id = el.getAttribute('wire:id');
    livewireComponent = window.Livewire?.find(id) ?? null;
    return livewireComponent;
}

// ---------------------------------------------------------------------------
// Initial position validation — runs per-frame before rep counting is allowed
// ---------------------------------------------------------------------------
function validatePosition(landmarks, angle) {
    const triple = JOINT_MAP[cfg.targetJoint];
    if (!triple) { validFrames = 0; return; }

    const [ia, ib, ic] = triple;
    const visOk = [ia, ib, ic].every(
        idx => landmarks[idx] && (landmarks[idx].visibility ?? 1) >= MIN_LANDMARK_VISIBILITY
    );
    const angleOk = angle <= cfg.minAngle + VALIDATION_TOLERANCE;

    if (visOk && angleOk) {
        validFrames++;
        if (validFrames === 1) speakCue(t('cueValidating') || 'Tahan posisi awal Anda');
    } else {
        validFrames = Math.max(0, validFrames - 2); // soft decay on bad frame
    }

    const pct = Math.min(100, Math.round((validFrames / VALIDATION_FRAMES) * 100));
    if (validationBar)   validationBar.style.width = pct + '%';
    if (validationLabel) validationLabel.textContent = pct + '%';

    if (statusDisplay) {
        statusDisplay.textContent = '⏳ ' + (i18n.statusValidating || 'Kalibrasi');
        statusDisplay.style.color = '#6366f1';
    }
    setFeedback('correct', t('cueValidating') || 'Tahan posisi awal Anda...', 'fa-person');

    if (validFrames >= VALIDATION_FRAMES) {
        completeValidation();
    }
}

function completeValidation() {
    isValidating = false;
    validFrames  = 0;

    if (validationContainer) validationContainer.classList.add('hidden');
    if (hudAnimation) hudAnimation.classList.add('hidden');

    playReadyTone();

    if (statusDisplay) {
        statusDisplay.textContent = '🎯 ' + (i18n.statusTracking || 'Melacak');
        statusDisplay.style.color = '#0d9488';
    }
    setFeedback('good', t('cueGo') || 'Mulai! Penghitungan rep dimulai', 'fa-flag-checkered');
    speakCue(t('cueGo') || 'Mulai! Lakukan latihan Anda');

    const component = getLivewireComponent();
    if (component) component.call('beginTracking');
}

// ---------------------------------------------------------------------------
// Detection loop
// ---------------------------------------------------------------------------
function detect() {
    if (!videoEl || videoEl.readyState < 2) {
        animFrameId = requestAnimationFrame(detect);
        return;
    }

    const results = poseLandmarker.detectForVideo(videoEl, performance.now());

    canvasCtx.clearRect(0, 0, canvasEl.width, canvasEl.height);

    if (results.landmarks && results.landmarks.length > 0) {
        const landmarks = results.landmarks[0];
        const drawingUtils = new DrawingUtils(canvasCtx);

        // Landmarks 0–10 are face/eye points — body tracking only needs 11+
        const bodyConns = PoseLandmarker.POSE_CONNECTIONS.filter(
            c => c.start >= 11 && c.end >= 11
        );
        drawingUtils.drawConnectors(landmarks, bodyConns, { color: '#00d4ff', lineWidth: 2 });
        drawingUtils.drawLandmarks(landmarks.filter((_, i) => i >= 11), { color: '#ffffff', radius: 3 });

        // Highlight target joint
        const jointKey = cfg.targetJoint;
        const triple = JOINT_MAP[jointKey];
        if (triple) {
            const [ia, ib, ic] = triple;
            const a = landmarks[ia];
            const b = landmarks[ib];
            const c = landmarks[ic];

            if (a && b && c) {
                const angle = calcAngle(a, b, c);
                const inRange = angle >= cfg.minAngle && angle <= cfg.maxAngle;

                // Color-code vertex landmark
                const vx = b.x * canvasEl.width;
                const vy = b.y * canvasEl.height;
                canvasCtx.beginPath();
                canvasCtx.arc(vx, vy, 10, 0, 2 * Math.PI);
                canvasCtx.fillStyle = inRange ? '#22c55e' : '#ef4444';
                canvasCtx.fill();

                // Draw angle text next to joint
                canvasCtx.font = 'bold 20px sans-serif';
                canvasCtx.fillStyle = '#ffffff';
                canvasCtx.strokeStyle = '#000000';
                canvasCtx.lineWidth = 3;
                const label = `${angle}°`;
                canvasCtx.strokeText(label, vx + 14, vy - 8);
                canvasCtx.fillText(label, vx + 14, vy - 8);

                // ---- Real-time corrective guidance ----------------------
                // Rep cycle: from 'down' the patient must reach maxAngle;
                // from 'up' they must return to minAngle. Tell them which
                // way to move and draw a directional arrow + target angle.
                const target = stage === 'down' ? cfg.maxAngle : cfg.minAngle;
                const reachedStageTarget =
                    stage === 'down' ? angle >= cfg.maxAngle : angle <= cfg.minAngle;
                const needIncrease = angle < target; // increase joint angle to reach target

                if (celebrated) {
                    setFeedback('good', '🎉 ' + t('targetReachedTitle'), 'fa-trophy');
                } else if (!isValidating) {
                    if (reachedStageTarget) {
                        setFeedback('good', t('cueGood'), 'fa-circle-check');
                    } else {
                        setFeedback(
                            'correct',
                            needIncrease
                                ? t('cueRaise', { angle: cfg.maxAngle })
                                : t('cueLower', { angle: cfg.minAngle }),
                            needIncrease ? 'fa-arrow-up' : 'fa-arrow-down'
                        );
                        drawDirectionArrow(vx, vy, needIncrease ? 'up' : 'down');
                    }
                }

                // Draw the target angle hint under the joint
                const targetLabel = `🎯 ${target}°`;
                canvasCtx.font = 'bold 16px sans-serif';
                canvasCtx.strokeText(targetLabel, vx + 14, vy + 16);
                canvasCtx.fillText(targetLabel, vx + 14, vy + 16);

                // Update displays
                if (angleDisplay) angleDisplay.textContent = `${angle}°`;
                if (hudAngle) hudAngle.textContent = `${angle}°`;
                if (!isValidating && statusDisplay) {
                    statusDisplay.textContent = inRange ? '✅ Dalam Rentang' : '⚠️ Di Luar Rentang';
                    statusDisplay.style.color = inRange ? '#16a34a' : '#dc2626';
                }

                if (isValidating) {
                    validatePosition(landmarks, angle);
                } else {
                    processAngle(angle);
                }
            }
        }
    } else {
        if (statusDisplay) {
            statusDisplay.textContent = '👁 Pose tidak terdeteksi';
            statusDisplay.style.color = '#6b7280';
        }
        if (!celebrated) {
            setFeedback('none', t('cueNoPose'), 'fa-person');
            speakCue(t('cueNoPose') || 'Mundur agar seluruh tubuh Anda terlihat');
        }
    }

    animFrameId = requestAnimationFrame(detect);
}

// Draw a chevron arrow above the joint indicating the direction to move.
function drawDirectionArrow(x, y, dir) {
    const s = 18;           // arrow half-size
    const oy = dir === 'up' ? -42 : 42; // place above/below the joint
    const tipY = dir === 'up' ? y + oy - s : y + oy + s;
    const baseY = dir === 'up' ? y + oy + s : y + oy - s;

    canvasCtx.beginPath();
    canvasCtx.moveTo(x, tipY);
    canvasCtx.lineTo(x - s, baseY);
    canvasCtx.lineTo(x + s, baseY);
    canvasCtx.closePath();
    canvasCtx.fillStyle = '#f59e0b';
    canvasCtx.strokeStyle = '#000000';
    canvasCtx.lineWidth = 2;
    canvasCtx.fill();
    canvasCtx.stroke();
}

// ---------------------------------------------------------------------------
// Public: startTracking — called by the "Start Tracking" button
// ---------------------------------------------------------------------------
window.startTracking = async function () {
    if (!poseLandmarker) {
        alert('Pelacak pose masih memuat. Harap tunggu sebentar dan coba lagi.');
        return;
    }

    if (startBtn) startBtn.disabled = true;
    if (placeholder) placeholder.style.display = 'none';

    // Unlock the audio context within this user gesture so beeps can play.
    initAudio();

    try {
        videoStream = await navigator.mediaDevices.getUserMedia({
            video: { width: 1280, height: 720, facingMode: 'user' },
            audio: false,
        });
        videoEl.srcObject = videoStream;
        await videoEl.play();

        canvasEl.width = videoEl.videoWidth || 1280;
        canvasEl.height = videoEl.videoHeight || 720;

        isValidating = true;
        validFrames  = 0;
        if (validationContainer) validationContainer.classList.remove('hidden');
        if (hudAnimation) hudAnimation.classList.remove('hidden');
        if (statusDisplay) {
            statusDisplay.textContent = '⏳ ' + (i18n.statusValidating || 'Kalibrasi');
            statusDisplay.style.color = '#6366f1';
        }
        setFeedback('correct', t('cueValidating') || 'Tahan posisi awal Anda...', 'fa-person');
        speakCue(t('cueValidating') || 'Tahan posisi awal Anda');

        const instructionPanel = document.getElementById('instruction-panel');
        if (instructionPanel) instructionPanel.classList.add('hidden');

        animFrameId = requestAnimationFrame(detect);
    } catch (err) {
        console.error('Camera access error:', err);
        alert('Tidak dapat mengakses kamera. Harap izinkan akses kamera dan coba lagi.');
        if (startBtn) startBtn.disabled = false;
        if (placeholder) placeholder.style.display = '';
    }
};

// ---------------------------------------------------------------------------
// Stop webcam (called on session complete or page unload)
// ---------------------------------------------------------------------------
function stopWebcam() {
    if (animFrameId) {
        cancelAnimationFrame(animFrameId);
        animFrameId = null;
    }
    if (videoStream) {
        videoStream.getTracks().forEach(track => track.stop());
        videoStream = null;
    }
}

// ---------------------------------------------------------------------------
// Celebration — fired once when the target rep count is reached.
// Shows a confetti burst + a SweetAlert success toast. Does NOT auto-complete
// the session; the patient still presses "Complete Session" themselves.
// ---------------------------------------------------------------------------
function celebrate() {
    // Fill + green-out the progress bars.
    updateProgress(cfg.targetReps || repCount);
    if (progressBar) progressBar.classList.replace('bg-teal-500', 'bg-green-500');
    if (hudProgress) hudProgress.classList.replace('bg-teal-400', 'bg-green-400');

    playSuccessChime();
    speakCue((t('targetReachedTitle') || 'Target tercapai') + '. ' + (t('targetReachedMsg') || 'Tekan Selesaikan Sesi saat Anda siap.'));
    launchConfetti();

    if (window.Swal) {
        window.Swal.fire({
            icon: 'success',
            title: t('targetReachedTitle') || 'Target tercapai!',
            text: t('targetReachedMsg') || '',
            timer: 3000,
            showConfirmButton: false,
            timerProgressBar: true,
            // Attach to the fullscreen element so it shows while fullscreen.
            target: document.fullscreenElement || document.body,
        });
    }
}

// Lightweight self-contained confetti (no external dependency).
function launchConfetti() {
    if (!confettiCanvas) return;
    const canvas = confettiCanvas;
    const ctx = canvas.getContext('2d');

    canvas.classList.remove('hidden');
    // Size to the canvas's own box (it now lives inside the camera stage),
    // so confetti stays crisp in both normal and fullscreen modes.
    canvas.width = canvas.offsetWidth || window.innerWidth;
    canvas.height = canvas.offsetHeight || window.innerHeight;

    const colors = ['#22c55e', '#14b8a6', '#6366f1', '#f59e0b', '#ef4444', '#06b6d4'];
    const pieces = Array.from({ length: 140 }, () => ({
        x: Math.random() * canvas.width,
        y: -20 - Math.random() * canvas.height * 0.5,
        r: 4 + Math.random() * 6,
        color: colors[(Math.random() * colors.length) | 0],
        vx: -2 + Math.random() * 4,
        vy: 2 + Math.random() * 4,
        rot: Math.random() * Math.PI,
        vrot: -0.2 + Math.random() * 0.4,
    }));

    const start = performance.now();
    const DURATION = 3000;

    function frame(now) {
        const elapsed = now - start;
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        for (const p of pieces) {
            p.x += p.vx;
            p.y += p.vy;
            p.vy += 0.05; // gravity
            p.rot += p.vrot;

            ctx.save();
            ctx.translate(p.x, p.y);
            ctx.rotate(p.rot);
            ctx.fillStyle = p.color;
            ctx.globalAlpha = Math.max(0, 1 - elapsed / DURATION);
            ctx.fillRect(-p.r / 2, -p.r / 2, p.r, p.r * 0.6);
            ctx.restore();
        }

        if (elapsed < DURATION) {
            requestAnimationFrame(frame);
        } else {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            canvas.classList.add('hidden');
        }
    }

    requestAnimationFrame(frame);
}

// ---------------------------------------------------------------------------
// Fullscreen preview
// Uses the native Fullscreen API with a CSS `.is-fs` fixed-overlay fallback
// (for browsers/iOS that reject element fullscreen). `.is-fs` is the single
// switch that also reveals the in-view HUD.
// ---------------------------------------------------------------------------
window.toggleFullscreen = function () {
    if (!cameraStage) return;

    const isFs = cameraStage.classList.contains('is-fs');
    if (isFs) {
        cameraStage.classList.remove('is-fs');
        if (document.fullscreenElement) document.exitFullscreen?.();
        setFullscreenIcon(false);
    } else {
        cameraStage.classList.add('is-fs');
        setFullscreenIcon(true);
        // Native fullscreen is best-effort; the `.is-fs` class already gives a
        // full-viewport fallback if this rejects or is unsupported.
        const req = cameraStage.requestFullscreen || cameraStage.webkitRequestFullscreen;
        if (req) {
            try {
                const p = req.call(cameraStage);
                if (p && p.catch) p.catch(() => {});
            } catch (_) { /* fallback handles it */ }
        }
    }
};

function setFullscreenIcon(on) {
    if (!fullscreenIcon) return;
    fullscreenIcon.className = (on ? 'fas fa-compress' : 'fas fa-expand') + ' text-base sm:text-lg';
}

function onFullscreenChange() {
    // User exited native fullscreen (e.g. ESC) — sync the class + icon.
    if (!document.fullscreenElement && cameraStage?.classList.contains('is-fs')) {
        cameraStage.classList.remove('is-fs');
        setFullscreenIcon(false);
    }
}

document.addEventListener('fullscreenchange', onFullscreenChange);
document.addEventListener('webkitfullscreenchange', onFullscreenChange);

// ---------------------------------------------------------------------------
// Tutorial modal
// ---------------------------------------------------------------------------
const TUTORIAL_VOICES = [
    () => t('tutorialWelcome'),
    () => t('tutorialStartingPosition'),
    () => t('tutorialMovement'),
];
const TUTORIAL_STEPS = 3;
const TUTORIAL_KEY = `tutorial_seen_${cfg.targetJoint}`;
let tutorialCurrentStep = 0;

function showTutorial() {
    const modal = document.getElementById('tutorial-modal');
    if (!modal) return;
    if (localStorage.getItem(TUTORIAL_KEY)) {
        return;
    }
    modal.classList.remove('hidden');
    tutorialCurrentStep = 0;
    updateTutorialStep(0);
}

function updateTutorialStep(step) {
    for (let i = 0; i < TUTORIAL_STEPS; i++) {
        const el  = document.getElementById(`tut-step-${i}`);
        const dot = document.getElementById(`tut-dot-${i}`);
        if (el)  el.classList.toggle('hidden', i !== step);
        if (dot) dot.className = i === step
            ? 'w-2.5 h-2.5 rounded-full bg-indigo-500 transition-all'
            : (i < step
                ? 'w-2.5 h-2.5 rounded-full bg-indigo-300 transition-all'
                : 'w-2.5 h-2.5 rounded-full bg-gray-200 transition-all');
    }
    const backBtn = document.getElementById('tut-back-btn');
    const nextBtn = document.getElementById('tut-next-btn');
    if (backBtn) backBtn.classList.toggle('hidden', step === 0);
    if (nextBtn) {
        const isLast = step === TUTORIAL_STEPS - 1;
        nextBtn.innerHTML = isLast
            ? `<i class="fas fa-play-circle mr-1" aria-hidden="true"></i> ${t('tutorialStartBtn') || 'Mulai Latihan'}`
            : `${t('tutorialNextBtn') || 'Lanjut'} <i class="fas fa-arrow-right" aria-hidden="true"></i>`;
        nextBtn.className = isLast
            ? 'bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition flex items-center gap-2'
            : 'bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition flex items-center gap-2';
    }
}

window.tutorialNext = function () {
    if (tutorialCurrentStep < TUTORIAL_STEPS - 1) {
        const fromStep = tutorialCurrentStep;
        tutorialCurrentStep++;
        updateTutorialStep(tutorialCurrentStep);

        if (fromStep === 0) {
            const welcomeCue = TUTORIAL_VOICES[0]?.();
            const nextCue    = TUTORIAL_VOICES[tutorialCurrentStep]?.();
            speakTutorialCue(welcomeCue, nextCue ? () => speakTutorialCue(nextCue) : null);
        } else {
            const cue = TUTORIAL_VOICES[tutorialCurrentStep]?.();
            if (cue) speakTutorialCue(cue);
        }
    } else {
        closeTutorial(true);
    }
};

window.tutorialBack = function () {
    if (tutorialCurrentStep > 0) {
        tutorialCurrentStep--;
        updateTutorialStep(tutorialCurrentStep);
        const cue = TUTORIAL_VOICES[tutorialCurrentStep]?.();
        if (cue) speakTutorialCue(cue);
    }
};

window.skipTutorial = function () {
    closeTutorial(false);
};

function closeTutorial(andStart = false) {
    localStorage.setItem(TUTORIAL_KEY, '1');
    const modal = document.getElementById('tutorial-modal');
    if (modal) modal.classList.add('hidden');
    if (andStart) startTracking();
}

// ---------------------------------------------------------------------------
// Bootstrap
// ---------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    videoEl = document.getElementById('rehab-video');
    canvasEl = document.getElementById('rehab-canvas');
    repDisplay = document.getElementById('rep-display');
    angleDisplay = document.getElementById('angle-display');
    statusDisplay = document.getElementById('status-display');
    placeholder = document.getElementById('camera-placeholder');
    startBtn = document.getElementById('start-btn');
    feedbackEl = document.getElementById('form-feedback');
    feedbackIcon = document.getElementById('form-feedback-icon');
    feedbackText = document.getElementById('form-feedback-text');
    confettiCanvas = document.getElementById('confetti-canvas');
    progressBar = document.getElementById('progress-bar');
    progressLabel = document.getElementById('progress-label');
    cameraStage = document.getElementById('camera-stage');
    fullscreenIcon = document.getElementById('fullscreen-icon');
    hudReps = document.getElementById('hud-reps');
    hudAngle = document.getElementById('hud-angle');
    hudProgress = document.getElementById('hud-progress');
    hudCue = document.getElementById('hud-cue');
    hudCueText = document.getElementById('hud-cue-text');
    hudCueIcon = document.getElementById('hud-cue-icon');
    validationContainer = document.getElementById('validation-bar-container');
    validationBar       = document.getElementById('validation-bar');
    validationLabel     = document.getElementById('validation-label');
    hudAnimation        = document.getElementById('hud-animation');

    if (canvasEl) canvasCtx = canvasEl.getContext('2d');

    initMediaPipe().catch(err => console.error('MediaPipe init failed:', err));

    showTutorial();
});

// Stop webcam when Livewire dispatches session-done
document.addEventListener('livewire:init', () => {
    window.Livewire.on('session-done', () => {
        stopWebcam();
        if (statusDisplay) statusDisplay.textContent = '✅ ' + (t('statusDone') || 'Selesai');
    });
});

window.addEventListener('beforeunload', stopWebcam);
