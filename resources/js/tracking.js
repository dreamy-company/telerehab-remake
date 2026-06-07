/**
 * MediaPipe Pose Landmarker — browser-side movement tracking.
 * Runs entirely in-browser via WASM. No server-side ML processing.
 */
import {
    PoseLandmarker,
    FilesetResolver,
    DrawingUtils,
} from 'https://cdn.skypack.dev/@mediapipe/tasks-vision@0.10.14';

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
// State
// ---------------------------------------------------------------------------
const cfg = window.REHAB_CONFIG || {};
let poseLandmarker = null;
let videoStream = null;
let animFrameId = null;
let livewireComponent = null;

let repCount = 0;
let stage = 'down'; // 'up' | 'down'
let lastRepLogged = 0; // guard against duplicate logging

// DOM refs (assigned after DOMContentLoaded)
let videoEl, canvasEl, canvasCtx, repDisplay, angleDisplay, statusDisplay, placeholder, startBtn;

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
        // Completed full cycle: up → down
        stage = 'down';
    } else if (angle >= maxAngle && stage === 'down') {
        // Completed upswing: down → up
        stage = 'up';
        repCount += 1;
        logRepToServer(repCount, angle);
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

        drawingUtils.drawConnectors(landmarks, PoseLandmarker.POSE_CONNECTIONS, {
            color: '#00d4ff',
            lineWidth: 2,
        });
        drawingUtils.drawLandmarks(landmarks, { color: '#ffffff', radius: 3 });

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

                // Update displays
                if (angleDisplay) angleDisplay.textContent = `${angle}°`;
                if (statusDisplay) {
                    statusDisplay.textContent = inRange ? '✅ In Range' : '⚠️ Out of Range';
                    statusDisplay.style.color = inRange ? '#16a34a' : '#dc2626';
                }

                processAngle(angle);
            }
        }
    } else {
        if (statusDisplay) {
            statusDisplay.textContent = '👁 No pose detected';
            statusDisplay.style.color = '#6b7280';
        }
    }

    animFrameId = requestAnimationFrame(detect);
}

// ---------------------------------------------------------------------------
// Public: startTracking — called by the "Start Tracking" button
// ---------------------------------------------------------------------------
window.startTracking = async function () {
    if (!poseLandmarker) {
        alert('Pose tracker is still loading. Please wait a moment and try again.');
        return;
    }

    if (startBtn) startBtn.disabled = true;
    if (placeholder) placeholder.style.display = 'none';

    try {
        videoStream = await navigator.mediaDevices.getUserMedia({
            video: { width: 1280, height: 720, facingMode: 'user' },
            audio: false,
        });
        videoEl.srcObject = videoStream;
        await videoEl.play();

        canvasEl.width = videoEl.videoWidth || 1280;
        canvasEl.height = videoEl.videoHeight || 720;

        if (statusDisplay) statusDisplay.textContent = '🎯 Tracking';

        animFrameId = requestAnimationFrame(detect);
    } catch (err) {
        console.error('Camera access error:', err);
        alert('Could not access camera. Please allow camera permission and try again.');
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
        videoStream.getTracks().forEach(t => t.stop());
        videoStream = null;
    }
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

    if (canvasEl) canvasCtx = canvasEl.getContext('2d');

    initMediaPipe().catch(err => console.error('MediaPipe init failed:', err));
});

// Stop webcam when Livewire dispatches session-done
document.addEventListener('livewire:init', () => {
    window.Livewire.on('session-done', () => {
        stopWebcam();
        if (statusDisplay) statusDisplay.textContent = '✅ Done';
    });
});

window.addEventListener('beforeunload', stopWebcam);
