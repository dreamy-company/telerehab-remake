<div>
{{-- =====================================================================
     Beginner Tutorial Modal
     Auto-shown on first visit (per exercise type) via localStorage.
     Closes when user clicks "Mulai Latihan" or "Lewati Tutorial".
     ===================================================================== --}}
@if($status !== 'done' && $session->status !== 'completed')
<div id="tutorial-modal"
     class="hidden fixed inset-0 z-[300] bg-black/80 flex items-center justify-center p-4 backdrop-blur-sm"
     role="dialog" aria-modal="true" aria-label="{{ __('Exercise Tutorial') }}">

    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[92vh]">

        {{-- Header: step dots + skip --}}
        <div class="flex items-center justify-between px-6 pt-5 pb-3 border-b border-gray-100">
            <div class="flex gap-1.5" aria-hidden="true">
                <span id="tut-dot-0" class="w-2.5 h-2.5 rounded-full bg-indigo-500 transition-all"></span>
                <span id="tut-dot-1" class="w-2.5 h-2.5 rounded-full bg-gray-200 transition-all"></span>
                <span id="tut-dot-2" class="w-2.5 h-2.5 rounded-full bg-gray-200 transition-all"></span>
            </div>
            <button onclick="skipTutorial()"
                    class="text-xs font-bold text-gray-400 hover:text-gray-600 transition">
                {{ __('Skip Tutorial') }} →
            </button>
        </div>

        {{-- Scrollable step content --}}
        <div class="overflow-y-auto flex-1">

            {{-- STEP 0: Welcome --}}
            <div id="tut-step-0" class="px-6 py-6 space-y-4">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-100 mb-4">
                        <i class="fas fa-dumbbell text-3xl text-indigo-600" aria-hidden="true"></i>
                    </div>
                    <h2 class="text-2xl font-black text-gray-900">{{ $session->exercise->name }}</h2>
                    <p class="text-gray-500 text-sm mt-1">
                        {{ __('Target') }}: <strong class="text-indigo-600">{{ $session->exercise->thresholds['target_reps'] }} {{ __('reps') }}</strong>
                    </p>
                </div>
                <p class="text-gray-700 text-center text-sm leading-relaxed">
                    {{ __('Watch this short tutorial before you start. It will guide you through the correct starting position and movement.') }}
                </p>
                <div class="bg-indigo-50 rounded-xl px-4 py-3 flex items-center gap-3 text-sm text-indigo-700 border border-indigo-100 justify-center">
                    <i class="fas fa-ruler-combined text-indigo-400" aria-hidden="true"></i>
                    <span>{{ __('Target angle range') }}: <strong>{{ $session->exercise->thresholds['min_angle'] }}°</strong> → <strong>{{ $session->exercise->thresholds['max_angle'] }}°</strong></span>
                </div>
            </div>

            {{-- STEP 1: Starting Position --}}
            <div id="tut-step-1" class="hidden px-6 py-6 space-y-4">
                <h2 class="text-lg font-black text-indigo-700 uppercase tracking-widest text-center">
                    {{ __('Starting Position') }}
                </h2>

                <div class="flex justify-center">
                    @switch($session->exercise->target_joint)
                        @case('right_shoulder')
                        <svg viewBox="0 0 220 340" class="w-44 sm:w-56 h-auto" role="img" aria-label="{{ __('Right shoulder starting position') }}">
                            <style>
                            .tut-body{stroke:#6366f1;stroke-linecap:round;fill:none}
                            .tut-joint{fill:#6366f1}
                            .tut-active-joint{fill:#f59e0b}
                            .tut-lbl{font:bold 10px sans-serif}
                            </style>
                            <circle cx="110" cy="24" r="18" class="tut-body" stroke-width="3.5"/>
                            <line x1="110" y1="42" x2="110" y2="170" class="tut-body" stroke-width="3.5"/>
                            <line x1="68" y1="80" x2="152" y2="80" class="tut-body" stroke-width="3.5"/>
                            <line x1="68" y1="80" x2="54" y2="152" class="tut-body" stroke-width="3.5"/>
                            <circle cx="54" cy="152" r="4" class="tut-joint"/>
                            <line x1="152" y1="80" x2="166" y2="155" stroke="#10b981" stroke-width="4.5" stroke-linecap="round"/>
                            <circle cx="166" cy="155" r="5" fill="#10b981"/>
                            <line x1="90" y1="170" x2="130" y2="170" class="tut-body" stroke-width="3.5"/>
                            <line x1="90" y1="170" x2="80" y2="250" class="tut-body" stroke-width="3.5"/>
                            <line x1="80" y1="250" x2="76" y2="320" class="tut-body" stroke-width="3"/>
                            <line x1="130" y1="170" x2="140" y2="250" class="tut-body" stroke-width="3.5"/>
                            <line x1="140" y1="250" x2="144" y2="320" class="tut-body" stroke-width="3"/>
                            <circle cx="68" cy="80" r="5" class="tut-joint"/>
                            <circle cx="152" cy="80" r="7" class="tut-active-joint"/>
                            <text x="155" y="73" class="tut-lbl" fill="#374151">Bahu kanan</text>
                            <text x="168" y="162" class="tut-lbl" fill="#10b981">Lengan</text>
                        </svg>
                        @break

                        @case('left_shoulder')
                        <svg viewBox="0 0 220 340" class="w-44 sm:w-56 h-auto" role="img" aria-label="{{ __('Left shoulder starting position') }}">
                            <circle cx="110" cy="24" r="18" fill="none" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="110" y1="42" x2="110" y2="170" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="68" y1="80" x2="152" y2="80" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="152" y1="80" x2="166" y2="152" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <circle cx="166" cy="152" r="4" fill="#6366f1"/>
                            <line x1="68" y1="80" x2="54" y2="155" stroke="#10b981" stroke-width="4.5" stroke-linecap="round"/>
                            <circle cx="54" cy="155" r="5" fill="#10b981"/>
                            <line x1="90" y1="170" x2="130" y2="170" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="90" y1="170" x2="80" y2="250" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="80" y1="250" x2="76" y2="320" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                            <line x1="130" y1="170" x2="140" y2="250" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="140" y1="250" x2="144" y2="320" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                            <circle cx="152" cy="80" r="5" fill="#6366f1"/>
                            <circle cx="68" cy="80" r="7" fill="#f59e0b"/>
                            <text x="10" y="73" style="font:bold 10px sans-serif;fill:#374151">Bahu kiri</text>
                            <text x="28" y="162" style="font:bold 10px sans-serif;fill:#10b981">Lengan</text>
                        </svg>
                        @break

                        @case('right_elbow')
                        <svg viewBox="0 0 220 340" class="w-44 sm:w-56 h-auto" role="img" aria-label="{{ __('Right elbow starting position') }}">
                            <circle cx="110" cy="24" r="18" fill="none" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="110" y1="42" x2="110" y2="170" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="68" y1="80" x2="152" y2="80" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="68" y1="80" x2="54" y2="152" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <circle cx="54" cy="152" r="4" fill="#6366f1"/>
                            <line x1="152" y1="80" x2="158" y2="152" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="158" y1="152" x2="162" y2="225" stroke="#10b981" stroke-width="4.5" stroke-linecap="round"/>
                            <circle cx="162" cy="225" r="5" fill="#10b981"/>
                            <line x1="90" y1="170" x2="130" y2="170" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="90" y1="170" x2="80" y2="250" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="80" y1="250" x2="76" y2="320" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                            <line x1="130" y1="170" x2="140" y2="250" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="140" y1="250" x2="144" y2="320" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                            <circle cx="68" cy="80" r="5" fill="#6366f1"/>
                            <circle cx="152" cy="80" r="5" fill="#6366f1"/>
                            <circle cx="158" cy="152" r="7" fill="#f59e0b"/>
                            <text x="162" y="148" style="font:bold 10px sans-serif;fill:#374151">Siku kanan</text>
                            <text x="164" y="232" style="font:bold 10px sans-serif;fill:#10b981">Tangan</text>
                        </svg>
                        @break

                        @case('right_knee')
                        <svg viewBox="0 0 220 340" class="w-44 sm:w-56 h-auto" role="img" aria-label="{{ __('Right knee starting position') }}">
                            <circle cx="110" cy="24" r="18" fill="none" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="110" y1="42" x2="110" y2="130" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="68" y1="80" x2="152" y2="80" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="68" y1="80" x2="54" y2="140" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="152" y1="80" x2="166" y2="140" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="60" y1="165" x2="160" y2="165" stroke="#94a3b8" stroke-width="2" stroke-dasharray="5 3"/>
                            <line x1="85" y1="150" x2="135" y2="150" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="85" y1="150" x2="75" y2="215" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="75" y1="215" x2="72" y2="290" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                            <line x1="135" y1="150" x2="148" y2="215" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="148" y1="215" x2="152" y2="290" stroke="#10b981" stroke-width="4.5" stroke-linecap="round"/>
                            <circle cx="152" cy="290" r="5" fill="#10b981"/>
                            <circle cx="68" cy="80" r="4" fill="#6366f1"/>
                            <circle cx="152" cy="80" r="4" fill="#6366f1"/>
                            <circle cx="148" cy="215" r="7" fill="#f59e0b"/>
                            <text x="152" y="210" style="font:bold 10px sans-serif;fill:#374151">Lutut kanan</text>
                            <text x="154" y="297" style="font:bold 10px sans-serif;fill:#10b981">Kaki</text>
                        </svg>
                        @break

                        @case('right_hip')
                        <svg viewBox="0 0 220 340" class="w-44 sm:w-56 h-auto" role="img" aria-label="{{ __('Right hip starting position') }}">
                            <circle cx="110" cy="24" r="18" fill="none" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="110" y1="42" x2="110" y2="170" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="68" y1="80" x2="152" y2="80" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="68" y1="80" x2="54" y2="152" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="152" y1="80" x2="166" y2="152" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="90" y1="170" x2="130" y2="170" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="90" y1="170" x2="80" y2="250" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="80" y1="250" x2="76" y2="320" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                            <line x1="130" y1="170" x2="140" y2="250" stroke="#10b981" stroke-width="4.5" stroke-linecap="round"/>
                            <line x1="140" y1="250" x2="144" y2="320" stroke="#10b981" stroke-width="4" stroke-linecap="round"/>
                            <circle cx="144" cy="320" r="5" fill="#10b981"/>
                            <circle cx="68" cy="80" r="4" fill="#6366f1"/>
                            <circle cx="152" cy="80" r="4" fill="#6366f1"/>
                            <circle cx="130" cy="170" r="7" fill="#f59e0b"/>
                            <text x="134" y="163" style="font:bold 10px sans-serif;fill:#374151">Pinggul kanan</text>
                            <text x="145" y="318" style="font:bold 10px sans-serif;fill:#10b981">Kaki</text>
                        </svg>
                        @break
                    @endswitch
                </div>

                <p class="text-sm text-gray-700 text-center leading-relaxed">
                    @switch($session->exercise->target_joint)
                        @case('right_shoulder')
                            {{ __('Stand upright with your right arm hanging naturally at your side, palm facing inward toward your thigh. Keep your shoulders relaxed and your back straight.') }}
                            @break
                        @case('left_shoulder')
                            {{ __('Stand upright with your left arm hanging naturally at your side, palm facing inward toward your thigh. Keep your shoulders relaxed and your back straight.') }}
                            @break
                        @case('right_elbow')
                            {{ __('Stand or sit with your right upper arm held close to your body and your right forearm pointing straight down, palm facing forward.') }}
                            @break
                        @case('right_knee')
                            {{ __('Sit on a chair with your back straight and your right knee bent at approximately 90°, foot hanging freely.') }}
                            @break
                        @case('right_hip')
                            {{ __('Stand upright with feet together and your right leg straight down beside your left leg. Hold a wall or support if needed for balance.') }}
                            @break
                    @endswitch
                </p>

                <div class="flex items-center justify-center gap-2 text-xs text-amber-600 bg-amber-50 rounded-xl px-4 py-2.5 border border-amber-100">
                    <i class="fas fa-lightbulb" aria-hidden="true"></i>
                    <span>{{ __('The system will verify your starting position before counting reps.') }}</span>
                </div>
            </div>

            {{-- STEP 2: Movement --}}
            <div id="tut-step-2" class="hidden px-6 py-6 space-y-4">
                <h2 class="text-lg font-black text-indigo-700 uppercase tracking-widest text-center">
                    {{ __('Movement') }}
                </h2>

                <div class="flex justify-center">
                    @switch($session->exercise->target_joint)
                        @case('right_shoulder')
                        <svg viewBox="0 0 220 340" class="w-44 sm:w-56 h-auto" role="img" aria-label="{{ __('Shoulder raise demonstration') }}">
                            <style>
                            @keyframes tut-rs{0%{transform:rotate(0deg)}100%{transform:rotate(-130deg)}}
                            .tut-rs{animation:tut-rs 2s ease-in-out infinite alternate;transform-origin:152px 80px}
                            </style>
                            <path d="M 166,155 A 80,80 0 0,1 108,6" stroke="#10b981" stroke-width="2" stroke-dasharray="6 4" fill="none" stroke-linecap="round"/>
                            <circle cx="110" cy="24" r="18" fill="none" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="110" y1="42" x2="110" y2="170" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="68" y1="80" x2="152" y2="80" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="68" y1="80" x2="54" y2="152" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="90" y1="170" x2="130" y2="170" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="90" y1="170" x2="80" y2="250" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="80" y1="250" x2="76" y2="320" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                            <line x1="130" y1="170" x2="140" y2="250" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="140" y1="250" x2="144" y2="320" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                            <g class="tut-rs">
                                <line x1="152" y1="80" x2="166" y2="155" stroke="#10b981" stroke-width="5" stroke-linecap="round"/>
                                <circle cx="166" cy="155" r="6" fill="#10b981"/>
                            </g>
                            <circle cx="68" cy="80" r="5" fill="#6366f1"/>
                            <circle cx="152" cy="80" r="8" fill="#f59e0b"/>
                            <circle cx="54" cy="152" r="4" fill="#6366f1"/>
                        </svg>
                        @break

                        @case('left_shoulder')
                        <svg viewBox="0 0 220 340" class="w-44 sm:w-56 h-auto" role="img" aria-label="{{ __('Shoulder raise demonstration') }}">
                            <style>
                            @keyframes tut-ls{0%{transform:rotate(0deg)}100%{transform:rotate(130deg)}}
                            .tut-ls{animation:tut-ls 2s ease-in-out infinite alternate;transform-origin:68px 80px}
                            </style>
                            <path d="M 54,155 A 80,80 0 0,0 112,6" stroke="#10b981" stroke-width="2" stroke-dasharray="6 4" fill="none" stroke-linecap="round"/>
                            <circle cx="110" cy="24" r="18" fill="none" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="110" y1="42" x2="110" y2="170" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="68" y1="80" x2="152" y2="80" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="152" y1="80" x2="166" y2="152" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="90" y1="170" x2="130" y2="170" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="90" y1="170" x2="80" y2="250" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="80" y1="250" x2="76" y2="320" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                            <line x1="130" y1="170" x2="140" y2="250" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="140" y1="250" x2="144" y2="320" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                            <g class="tut-ls">
                                <line x1="68" y1="80" x2="54" y2="155" stroke="#10b981" stroke-width="5" stroke-linecap="round"/>
                                <circle cx="54" cy="155" r="6" fill="#10b981"/>
                            </g>
                            <circle cx="68" cy="80" r="8" fill="#f59e0b"/>
                            <circle cx="152" cy="80" r="5" fill="#6366f1"/>
                            <circle cx="166" cy="152" r="4" fill="#6366f1"/>
                        </svg>
                        @break

                        @case('right_elbow')
                        <svg viewBox="0 0 220 340" class="w-44 sm:w-56 h-auto" role="img" aria-label="{{ __('Elbow flexion demonstration') }}">
                            <style>
                            @keyframes tut-re{0%{transform:rotate(0deg)}100%{transform:rotate(-115deg)}}
                            .tut-re{animation:tut-re 2s ease-in-out infinite alternate;transform-origin:158px 152px}
                            </style>
                            <circle cx="110" cy="24" r="18" fill="none" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="110" y1="42" x2="110" y2="170" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="68" y1="80" x2="152" y2="80" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="68" y1="80" x2="54" y2="152" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="152" y1="80" x2="158" y2="152" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="90" y1="170" x2="130" y2="170" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="90" y1="170" x2="80" y2="250" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="80" y1="250" x2="76" y2="320" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                            <line x1="130" y1="170" x2="140" y2="250" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="140" y1="250" x2="144" y2="320" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                            <g class="tut-re">
                                <line x1="158" y1="152" x2="162" y2="225" stroke="#10b981" stroke-width="5" stroke-linecap="round"/>
                                <circle cx="162" cy="225" r="6" fill="#10b981"/>
                            </g>
                            <circle cx="68" cy="80" r="4" fill="#6366f1"/>
                            <circle cx="152" cy="80" r="5" fill="#6366f1"/>
                            <circle cx="54" cy="152" r="4" fill="#6366f1"/>
                            <circle cx="158" cy="152" r="8" fill="#f59e0b"/>
                        </svg>
                        @break

                        @case('right_knee')
                        <svg viewBox="0 0 220 340" class="w-44 sm:w-56 h-auto" role="img" aria-label="{{ __('Knee extension demonstration') }}">
                            <style>
                            @keyframes tut-rk{0%{transform:rotate(0deg)}100%{transform:rotate(-120deg)}}
                            .tut-rk{animation:tut-rk 2s ease-in-out infinite alternate;transform-origin:148px 215px}
                            </style>
                            <circle cx="110" cy="24" r="18" fill="none" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="110" y1="42" x2="110" y2="130" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="68" y1="80" x2="152" y2="80" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="68" y1="80" x2="54" y2="140" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="152" y1="80" x2="166" y2="140" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="60" y1="165" x2="160" y2="165" stroke="#94a3b8" stroke-width="2" stroke-dasharray="5 3"/>
                            <line x1="85" y1="150" x2="135" y2="150" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="85" y1="150" x2="75" y2="215" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="75" y1="215" x2="72" y2="290" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                            <line x1="135" y1="150" x2="148" y2="215" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <g class="tut-rk">
                                <line x1="148" y1="215" x2="152" y2="290" stroke="#10b981" stroke-width="5" stroke-linecap="round"/>
                                <circle cx="152" cy="290" r="6" fill="#10b981"/>
                            </g>
                            <circle cx="68" cy="80" r="4" fill="#6366f1"/>
                            <circle cx="152" cy="80" r="4" fill="#6366f1"/>
                            <circle cx="148" cy="215" r="8" fill="#f59e0b"/>
                        </svg>
                        @break

                        @case('right_hip')
                        <svg viewBox="0 0 220 340" class="w-44 sm:w-56 h-auto" role="img" aria-label="{{ __('Hip flexion demonstration') }}">
                            <style>
                            @keyframes tut-rh{0%{transform:rotate(0deg)}100%{transform:rotate(-72deg)}}
                            .tut-rh{animation:tut-rh 2s ease-in-out infinite alternate;transform-origin:130px 170px}
                            </style>
                            <circle cx="110" cy="24" r="18" fill="none" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="110" y1="42" x2="110" y2="170" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="68" y1="80" x2="152" y2="80" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="68" y1="80" x2="54" y2="152" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="152" y1="80" x2="166" y2="152" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="90" y1="170" x2="130" y2="170" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="90" y1="170" x2="80" y2="250" stroke="#6366f1" stroke-width="3.5" stroke-linecap="round"/>
                            <line x1="80" y1="250" x2="76" y2="320" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                            <g class="tut-rh">
                                <line x1="130" y1="170" x2="140" y2="250" stroke="#10b981" stroke-width="5" stroke-linecap="round"/>
                                <line x1="140" y1="250" x2="144" y2="320" stroke="#10b981" stroke-width="4.5" stroke-linecap="round"/>
                                <circle cx="144" cy="320" r="6" fill="#10b981"/>
                            </g>
                            <circle cx="68" cy="80" r="4" fill="#6366f1"/>
                            <circle cx="152" cy="80" r="4" fill="#6366f1"/>
                            <circle cx="130" cy="170" r="8" fill="#f59e0b"/>
                        </svg>
                        @break
                    @endswitch
                </div>

                {{-- Numbered movement steps --}}
                <ol class="space-y-2.5 text-sm text-gray-700 list-none">
                    @switch($session->exercise->target_joint)
                        @case('right_shoulder')
                        @case('left_shoulder')
                            <li class="flex gap-3 items-start">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-black flex items-center justify-center">1</span>
                                {{ __('Slowly raise your arm forward and upward in a wide arc.') }}
                            </li>
                            <li class="flex gap-3 items-start">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-black flex items-center justify-center">2</span>
                                {{ __('Continue until your arm is fully extended overhead (or as high as comfortable).') }}
                            </li>
                            <li class="flex gap-3 items-start">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-black flex items-center justify-center">3</span>
                                {{ __('Lower it back down slowly and with control. That is one rep.') }}
                            </li>
                            @break
                        @case('right_elbow')
                            <li class="flex gap-3 items-start">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-black flex items-center justify-center">1</span>
                                {{ __('Slowly curl your forearm upward, bending at the elbow.') }}
                            </li>
                            <li class="flex gap-3 items-start">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-black flex items-center justify-center">2</span>
                                {{ __('Bring your hand toward your shoulder as far as comfortable.') }}
                            </li>
                            <li class="flex gap-3 items-start">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-black flex items-center justify-center">3</span>
                                {{ __('Slowly lower your forearm back to the starting position. That is one rep.') }}
                            </li>
                            @break
                        @case('right_knee')
                            <li class="flex gap-3 items-start">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-black flex items-center justify-center">1</span>
                                {{ __('Slowly straighten your right leg by extending the knee forward.') }}
                            </li>
                            <li class="flex gap-3 items-start">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-black flex items-center justify-center">2</span>
                                {{ __('Hold for 1–2 seconds when the leg is fully extended.') }}
                            </li>
                            <li class="flex gap-3 items-start">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-black flex items-center justify-center">3</span>
                                {{ __('Lower your foot back down to the starting position. That is one rep.') }}
                            </li>
                            @break
                        @case('right_hip')
                            <li class="flex gap-3 items-start">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-black flex items-center justify-center">1</span>
                                {{ __('Slowly lift your right knee upward and forward, bending at the hip.') }}
                            </li>
                            <li class="flex gap-3 items-start">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-black flex items-center justify-center">2</span>
                                {{ __('Raise until your thigh is roughly parallel to the floor (or as high as comfortable).') }}
                            </li>
                            <li class="flex gap-3 items-start">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-black flex items-center justify-center">3</span>
                                {{ __('Lower your leg back down with control. That is one rep.') }}
                            </li>
                            @break
                    @endswitch
                </ol>
            </div>

        </div>{{-- end scrollable --}}

        {{-- Footer: back + next/start --}}
        <div class="px-6 py-4 border-t border-gray-100 flex items-center gap-3">
            <button id="tut-back-btn"
                    onclick="tutorialBack()"
                    class="hidden text-sm font-bold text-gray-500 hover:text-gray-700 px-4 py-2 rounded-xl transition">
                ← {{ __('Back') }}
            </button>
            <div class="flex-1"></div>
            <button id="tut-next-btn"
                    onclick="tutorialNext()"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition flex items-center gap-2">
                {{ __('Next') }} <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </button>
        </div>

    </div>
</div>
@endif

<section class="space-y-6 animate-in fade-in duration-500" aria-label="Movement Tracking Session">

    {{-- Config exposed to tracking.js --}}
    <script>
        window.REHAB_CONFIG = {
            sessionId:   {{ $session->id }},
            livewireId:  null, // set after Livewire init
            targetJoint: @json($session->exercise->target_joint),
            minAngle:    {{ $session->exercise->thresholds['min_angle'] }},
            maxAngle:    {{ $session->exercise->thresholds['max_angle'] }},
            targetReps:  {{ $session->exercise->thresholds['target_reps'] }},
            repSound:    @json(asset('assets/audio/0612.mp3')),

            // Localized strings for the celebration + real-time corrective cues.
            // ":angle" is replaced with the target angle by tracking.js.
            i18n: {
                statusTracking:    @json(__('Tracking')),
                statusDone:        @json(__('Done')),
                statusValidating:  @json(__('Calibrating')),
                targetReachedTitle:@json(__('Target reached!')),
                targetReachedMsg:  @json(__('Great job — press Complete Session when you are ready.')),
                cueRaise:          @json(__('Keep raising — reach :angle°')),
                cueLower:          @json(__('Lower back down to :angle°')),
                cueGood:           @json(__('Good form — keep going!')),
                cueNoPose:         @json(__('Step back so your full body is visible')),
                cueReady:          @json(__('Get into position and press Start Tracking')),
                cueValidating:     @json(__('Hold your starting position...')),
                cueGo:             @json(__('GO! Rep counting started')),
                voiceRaise:              @json(__('Raise up')),
                voiceLower:              @json(__('Lower down')),
                tutorialWelcome:         @json(__('Welcome to your exercise session. Watch this short tutorial to learn the correct technique before you start.')),
                tutorialStartingPosition:@json(__('Starting Position. Get into the position shown and hold still. The system will confirm your position before counting begins.')),
                tutorialMovement:        @json(__('Movement. Perform the exercise slowly and with control. Follow the numbered steps shown. Each full cycle counts as one rep.')),
                tutorialStartBtn:        @json(__('Start Exercise')),
                tutorialNextBtn:         @json(__('Next')),
            },
        };
    </script>

    @php
        $exerciseUrl = $session->rehab_routine_id
            ? route('patient.rehabilitation.exercise', ['id' => $session->rehab_routine_id])
            : route('patient.rehabilitation');
    @endphp

    {{-- Header --}}
    <div class="flex flex-wrap items-center gap-3 sm:gap-4">
        <a href="{{ $exerciseUrl }}"
           class="btn bg-primary-500 text-white hover:bg-primary-600 rounded-md text-sm font-bold"
           aria-label="{{ __('Go back') }}">
            <i class="fas fa-arrow-left mr-2" aria-hidden="true"></i> {{ __('Back') }}
        </a>
        <div class="min-w-0">
            <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900 leading-tight">
                {{ __('Live Movement Tracking') }}
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                {{ $session->exercise->name }} &mdash;
                {{ __('Target') }}: <strong>{{ $session->exercise->thresholds['target_reps'] }} {{ __('reps') }}</strong>
            </p>
        </div>
    </div>

    {{-- Done banner --}}
    @if($status === 'done' || $session->status === 'completed')
    <div class="alert bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center gap-3" role="alert">
        <i class="fas fa-check-circle text-2xl text-green-600" aria-hidden="true"></i>
        <div>
            <p class="font-bold text-lg">{{ __('Session Completed!') }}</p>
            <p class="text-sm">{{ __('Your movement data has been saved. Your doctor will review it shortly.') }}</p>
        </div>
    </div>
    @endif

    {{-- Stat cards --}}
    <div class="grid grid-cols-3 gap-2 sm:gap-4" aria-live="polite" aria-atomic="true">
        <div class="bg-white rounded-2xl shadow p-3 sm:p-5 text-center border border-gray-100">
            <p class="text-[10px] sm:text-xs font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('Reps Done') }}</p>
            <p id="rep-display"
               class="text-3xl sm:text-4xl md:text-5xl font-black text-teal-600 leading-none"
               aria-label="{{ __('Repetitions done') }}">{{ $repCount }}</p>
            <p class="text-[10px] sm:text-xs text-gray-400 mt-1">{{ __('of') }} {{ $session->exercise->thresholds['target_reps'] }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow p-3 sm:p-5 text-center border border-gray-100">
            <p class="text-[10px] sm:text-xs font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('Joint Angle') }}</p>
            <p id="angle-display"
               class="text-3xl sm:text-4xl md:text-5xl font-black text-indigo-600 leading-none"
               aria-label="{{ __('Current joint angle in degrees') }}">{{ number_format($currentAngle, 0) }}&deg;</p>
            <p class="text-[10px] sm:text-xs text-gray-400 mt-1">
                {{ __('Range') }}: {{ $session->exercise->thresholds['min_angle'] }}&deg; &ndash;
                {{ $session->exercise->thresholds['max_angle'] }}&deg;
            </p>
        </div>
        <div class="bg-white rounded-2xl shadow p-3 sm:p-5 text-center border border-gray-100">
            <p class="text-[10px] sm:text-xs font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('Status') }}</p>
            <p id="status-display"
               class="text-base sm:text-xl md:text-2xl font-black text-gray-700 leading-tight mt-2 break-words"
               aria-label="{{ __('Tracking status') }}">
               @if($status === 'done' || $session->status === 'completed')
                   <span class="text-green-600"><i class="fas fa-check-circle" aria-hidden="true"></i> {{ __('Done') }}</span>
               @elseif($status === 'tracking')
                   <span class="text-teal-600"><i class="fas fa-circle-notch fa-spin" aria-hidden="true"></i> {{ __('Tracking') }}</span>
               @else
                   <span class="text-gray-500"><i class="fas fa-hourglass-start" aria-hidden="true"></i> {{ __('Ready') }}</span>
               @endif
            </p>
        </div>
    </div>

    {{-- Live progress bar (reps toward target) --}}
    @php
        $targetReps = max(1, (int) $session->exercise->thresholds['target_reps']);
        $progressPct = min(100, round($repCount / $targetReps * 100));
    @endphp
    <div class="bg-white rounded-2xl shadow p-4 sm:p-5 border border-gray-100">
        <div class="flex items-center justify-between mb-2">
            <p class="text-[10px] sm:text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('Progress') }}</p>
            <p id="progress-label" class="text-xs sm:text-sm font-bold text-gray-600">
                {{ $repCount }} / {{ $session->exercise->thresholds['target_reps'] }} &middot; {{ $progressPct }}%
            </p>
        </div>
        <div class="h-3 w-full rounded-full bg-gray-100 overflow-hidden" role="progressbar"
             aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $progressPct }}">
            <div id="progress-bar"
                 class="h-full rounded-full bg-teal-500 transition-all duration-300"
                 style="width: {{ $progressPct }}%"></div>
        </div>
    </div>

    {{-- Real-time corrective form feedback (updated live by tracking.js) --}}
    <div id="form-feedback"
         class="rounded-xl p-4 flex items-center gap-3 border bg-gray-50 border-gray-200 text-gray-600 transition-colors"
         role="status" aria-live="polite">
        <i id="form-feedback-icon" class="fas fa-circle-info text-xl" aria-hidden="true"></i>
        <span id="form-feedback-text" class="text-sm font-bold">{{ __('Get into position and press Start Tracking') }}</span>
    </div>

    {{-- Initial position validation bar (shown by JS while patient holds starting position) --}}
    <div id="validation-bar-container"
         class="hidden bg-white rounded-2xl shadow p-4 sm:p-5 border border-indigo-100"
         role="status" aria-live="polite">
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-2">
                <i class="fas fa-person-walking text-indigo-500" aria-hidden="true"></i>
                <p class="text-xs font-black text-indigo-700 uppercase tracking-widest">{{ __('Position Check') }}</p>
            </div>
            <p id="validation-label" class="text-xs font-bold text-indigo-600">0%</p>
        </div>
        <div class="h-3 w-full rounded-full bg-gray-100 overflow-hidden">
            <div id="validation-bar"
                 class="h-full rounded-full bg-indigo-500 transition-all duration-100"
                 style="width: 0%"></div>
        </div>
        <p class="text-xs text-gray-500 mt-2">{{ __('Hold your starting position until the bar is full') }}</p>
    </div>

    {{-- Camera + skeleton overlay --}}
    <div wire:ignore
         id="camera-stage"
         class="bg-black rounded-2xl overflow-hidden shadow-xl relative w-full max-h-[60vh] sm:max-h-[520px]"
         style="aspect-ratio: 16/9;"
         aria-label="Webcam feed with pose skeleton overlay">
        <video id="rehab-video"
               autoplay
               playsinline
               muted
               class="w-full h-full object-cover"
               aria-label="Webcam feed">
        </video>
        <canvas id="rehab-canvas"
                class="absolute inset-0 w-full h-full"
                aria-hidden="true">
        </canvas>

        {{-- Fullscreen toggle (always visible) --}}
        <button id="fullscreen-btn"
                type="button"
                onclick="toggleFullscreen()"
                class="absolute top-3 right-3 z-20 w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-black/50 hover:bg-black/70 text-white flex items-center justify-center backdrop-blur-sm transition"
                aria-label="{{ __('Full Screen') }}" title="{{ __('Full Screen') }}">
            <i id="fullscreen-icon" class="fas fa-expand text-base sm:text-lg" aria-hidden="true"></i>
        </button>

        {{-- In-view HUD (shown only in fullscreen so key info stays visible) --}}
        <div class="fs-hud absolute inset-0 flex-col justify-between p-4 sm:p-6 pointer-events-none">
            <div class="flex items-start justify-between gap-3">
                <div class="rounded-2xl bg-black/55 px-3 py-1.5 sm:px-4 sm:py-2 backdrop-blur-sm text-center">
                    <p class="text-[10px] sm:text-xs font-black text-teal-300 uppercase tracking-widest leading-none">{{ __('Reps') }}</p>
                    <span id="hud-reps" class="block text-white font-black text-4xl sm:text-6xl leading-none mt-1">
                        {{ $repCount }} / {{ $session->exercise->thresholds['target_reps'] }}
                    </span>
                </div>
                <div class="rounded-2xl bg-black/55 px-3 py-1.5 sm:px-4 sm:py-2 backdrop-blur-sm text-center mr-12 sm:mr-16">
                    <p class="text-[10px] sm:text-xs font-black text-indigo-300 uppercase tracking-widest leading-none">{{ __('Joint Angle') }}</p>
                    <span id="hud-angle" class="block text-white font-black text-4xl sm:text-6xl leading-none mt-1">
                        {{ number_format($currentAngle, 0) }}&deg;
                    </span>
                </div>
            </div>
            <div class="space-y-3 text-center">
                <div id="hud-cue"
                     class="mx-auto inline-flex items-center gap-3 rounded-2xl px-5 py-3 backdrop-blur-sm bg-gray-900/70">
                    <i id="hud-cue-icon" class="fas fa-circle-info text-2xl sm:text-4xl text-white" aria-hidden="true"></i>
                    <span id="hud-cue-text" class="text-lg sm:text-3xl font-extrabold text-white drop-shadow-lg">
                        {{ __('Get into position and press Start Tracking') }}
                    </span>
                </div>
                <div class="h-2.5 w-full rounded-full bg-white/25 overflow-hidden">
                    <div id="hud-progress" class="h-full rounded-full bg-teal-400 transition-all duration-300"
                         style="width: {{ $progressPct }}%"></div>
                </div>
            </div>
        </div>

        {{-- Overlay when not tracking --}}
        <div id="camera-placeholder"
             class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900/80 text-white"
             aria-live="polite">
            <i class="fas fa-camera text-5xl mb-4 text-teal-400" aria-hidden="true"></i>
            <p class="text-xl font-bold">{{ __('Camera not started') }}</p>
            <p class="text-sm text-gray-300 mt-1">{!! __('Press :button to begin', ['button' => '<strong>' . __('Start Tracking') . '</strong>']) !!}</p>
        </div>

        {{-- Celebration confetti overlay (kept inside the stage so it shows in fullscreen) --}}
        <canvas id="confetti-canvas"
                class="absolute inset-0 w-full h-full pointer-events-none z-[90] hidden"
                aria-hidden="true"></canvas>

        {{-- In-camera animation guide: visible during position validation in both normal and fullscreen mode --}}
        <div id="hud-animation"
             class="hidden absolute bottom-14 right-3 z-10 bg-black/65 rounded-2xl p-2 backdrop-blur-sm pointer-events-none"
             aria-hidden="true">
            <p class="text-white text-[10px] font-black uppercase tracking-widest text-center mb-1 opacity-70">
                {{ __('Position') }}
            </p>
            @switch($session->exercise->target_joint)
                @case('right_shoulder')
                <svg viewBox="0 0 180 260" class="w-20 h-auto">
                    <style>.hud-anim-rs{animation:anim-right-shoulder 1.6s ease-in-out infinite alternate;transform-origin:108px 72px}</style>
                    <circle cx="90" cy="22" r="14" fill="none" stroke="#818cf8" stroke-width="3"/>
                    <line x1="90" y1="36" x2="90" y2="152" stroke="#818cf8" stroke-width="3" stroke-linecap="round"/>
                    <line x1="72" y1="72" x2="50" y2="125" stroke="#818cf8" stroke-width="3" stroke-linecap="round"/>
                    <line x1="90" y1="152" x2="76" y2="220" stroke="#818cf8" stroke-width="3" stroke-linecap="round"/>
                    <line x1="90" y1="152" x2="104" y2="220" stroke="#818cf8" stroke-width="3" stroke-linecap="round"/>
                    <g class="hud-anim-rs">
                        <line x1="108" y1="72" x2="132" y2="128" stroke="#4ade80" stroke-width="4" stroke-linecap="round"/>
                        <circle cx="132" cy="128" r="5" fill="#4ade80"/>
                    </g>
                    <circle cx="72" cy="72" r="4" fill="#818cf8"/>
                    <circle cx="108" cy="72" r="5" fill="#4ade80"/>
                </svg>
                @break

                @case('left_shoulder')
                <svg viewBox="0 0 180 260" class="w-20 h-auto">
                    <style>.hud-anim-ls{animation:anim-left-shoulder 1.6s ease-in-out infinite alternate;transform-origin:72px 72px}</style>
                    <circle cx="90" cy="22" r="14" fill="none" stroke="#818cf8" stroke-width="3"/>
                    <line x1="90" y1="36" x2="90" y2="152" stroke="#818cf8" stroke-width="3" stroke-linecap="round"/>
                    <line x1="108" y1="72" x2="130" y2="125" stroke="#818cf8" stroke-width="3" stroke-linecap="round"/>
                    <line x1="90" y1="152" x2="76" y2="220" stroke="#818cf8" stroke-width="3" stroke-linecap="round"/>
                    <line x1="90" y1="152" x2="104" y2="220" stroke="#818cf8" stroke-width="3" stroke-linecap="round"/>
                    <g class="hud-anim-ls">
                        <line x1="72" y1="72" x2="48" y2="128" stroke="#4ade80" stroke-width="4" stroke-linecap="round"/>
                        <circle cx="48" cy="128" r="5" fill="#4ade80"/>
                    </g>
                    <circle cx="72" cy="72" r="5" fill="#4ade80"/>
                    <circle cx="108" cy="72" r="4" fill="#818cf8"/>
                </svg>
                @break

                @case('right_elbow')
                <svg viewBox="0 0 180 260" class="w-20 h-auto">
                    <style>.hud-anim-re{animation:anim-right-elbow 1.6s ease-in-out infinite alternate;transform-origin:114px 135px}</style>
                    <circle cx="90" cy="22" r="14" fill="none" stroke="#818cf8" stroke-width="3"/>
                    <line x1="90" y1="36" x2="90" y2="152" stroke="#818cf8" stroke-width="3" stroke-linecap="round"/>
                    <line x1="72" y1="72" x2="50" y2="125" stroke="#818cf8" stroke-width="3" stroke-linecap="round"/>
                    <line x1="108" y1="72" x2="114" y2="135" stroke="#818cf8" stroke-width="3" stroke-linecap="round"/>
                    <line x1="90" y1="152" x2="76" y2="220" stroke="#818cf8" stroke-width="3" stroke-linecap="round"/>
                    <line x1="90" y1="152" x2="104" y2="220" stroke="#818cf8" stroke-width="3" stroke-linecap="round"/>
                    <g class="hud-anim-re">
                        <line x1="114" y1="135" x2="120" y2="205" stroke="#4ade80" stroke-width="4" stroke-linecap="round"/>
                        <circle cx="120" cy="205" r="5" fill="#4ade80"/>
                    </g>
                    <circle cx="108" cy="72" r="4" fill="#818cf8"/>
                    <circle cx="114" cy="135" r="5" fill="#4ade80"/>
                </svg>
                @break

                @case('right_knee')
                <svg viewBox="0 0 200 260" class="w-20 h-auto">
                    <style>.hud-anim-rk{animation:anim-right-knee 1.6s ease-in-out infinite alternate;transform-origin:115px 185px}</style>
                    <circle cx="90" cy="22" r="14" fill="none" stroke="#818cf8" stroke-width="3"/>
                    <line x1="90" y1="36" x2="90" y2="130" stroke="#818cf8" stroke-width="3" stroke-linecap="round"/>
                    <line x1="60" y1="130" x2="150" y2="130" stroke="#475569" stroke-width="2" stroke-dasharray="4 3"/>
                    <line x1="80" y1="130" x2="60" y2="185" stroke="#818cf8" stroke-width="3" stroke-linecap="round"/>
                    <line x1="100" y1="130" x2="115" y2="185" stroke="#818cf8" stroke-width="3" stroke-linecap="round"/>
                    <line x1="60" y1="185" x2="58" y2="245" stroke="#818cf8" stroke-width="3" stroke-linecap="round"/>
                    <g class="hud-anim-rk">
                        <line x1="115" y1="185" x2="118" y2="248" stroke="#4ade80" stroke-width="4" stroke-linecap="round"/>
                        <circle cx="118" cy="248" r="5" fill="#4ade80"/>
                    </g>
                    <circle cx="115" cy="185" r="5" fill="#4ade80"/>
                </svg>
                @break

                @case('right_hip')
                <svg viewBox="0 0 180 260" class="w-20 h-auto">
                    <style>.hud-anim-rh{animation:anim-right-hip 1.6s ease-in-out infinite alternate;transform-origin:102px 152px}</style>
                    <circle cx="90" cy="22" r="14" fill="none" stroke="#818cf8" stroke-width="3"/>
                    <line x1="90" y1="36" x2="90" y2="152" stroke="#818cf8" stroke-width="3" stroke-linecap="round"/>
                    <line x1="72" y1="72" x2="50" y2="125" stroke="#818cf8" stroke-width="3" stroke-linecap="round"/>
                    <line x1="108" y1="72" x2="130" y2="125" stroke="#818cf8" stroke-width="3" stroke-linecap="round"/>
                    <line x1="82" y1="152" x2="76" y2="230" stroke="#818cf8" stroke-width="3" stroke-linecap="round"/>
                    <g class="hud-anim-rh">
                        <line x1="102" y1="152" x2="108" y2="230" stroke="#4ade80" stroke-width="4" stroke-linecap="round"/>
                        <circle cx="108" cy="230" r="5" fill="#4ade80"/>
                    </g>
                    <circle cx="82" cy="152" r="4" fill="#818cf8"/>
                    <circle cx="102" cy="152" r="5" fill="#4ade80"/>
                </svg>
                @break
            @endswitch
        </div>
    </div>

    <style>
        /* The HUD overlay only appears while the camera is in (pseudo-)fullscreen */
        #camera-stage .fs-hud { display: none; }
        #camera-stage.is-fs .fs-hud { display: flex; }
        #camera-stage.is-fs {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            max-height: none;
            border-radius: 0;
            aspect-ratio: auto;
            z-index: 80;
        }
        #camera-stage.is-fs #rehab-video,
        #camera-stage.is-fs #rehab-canvas { object-fit: contain; }
    </style>

    {{-- Full screen button (discoverable on touch devices) --}}
    <button type="button"
            onclick="toggleFullscreen()"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gray-800 hover:bg-gray-900 text-white py-3 px-5 rounded-xl font-bold text-sm transition-all"
            aria-label="{{ __('Full Screen') }}">
        <i class="fas fa-expand" aria-hidden="true"></i> {{ __('Full Screen') }}
    </button>

    {{-- Exercise instruction panel (hidden by JS when camera starts) --}}
    @if($status !== 'done' && $session->status !== 'completed')
    <div id="instruction-panel"
         class="bg-white rounded-2xl shadow border border-indigo-100 overflow-hidden">

        <div class="bg-indigo-50 px-5 py-3 border-b border-indigo-100 flex items-center gap-2">
            <i class="fas fa-circle-info text-indigo-500" aria-hidden="true"></i>
            <p class="font-black text-xs text-indigo-700 uppercase tracking-widest">
                {{ __('How to Perform This Exercise') }}
            </p>
        </div>

        <div class="p-5 flex flex-col sm:flex-row gap-6">

            {{-- Text instructions --}}
            <div class="flex-1 space-y-4">

                <div>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Starting Position') }}</p>
                    @switch($session->exercise->target_joint)
                        @case('right_shoulder')
                            <p class="text-sm text-gray-700">{{ __('Stand upright with your right arm hanging naturally at your side, palm facing inward toward your thigh. Keep your shoulders relaxed and your back straight.') }}</p>
                            @break
                        @case('left_shoulder')
                            <p class="text-sm text-gray-700">{{ __('Stand upright with your left arm hanging naturally at your side, palm facing inward toward your thigh. Keep your shoulders relaxed and your back straight.') }}</p>
                            @break
                        @case('right_elbow')
                            <p class="text-sm text-gray-700">{{ __('Stand or sit with your right upper arm held close to your body and your right forearm pointing straight down, palm facing forward.') }}</p>
                            @break
                        @case('right_knee')
                            <p class="text-sm text-gray-700">{{ __('Sit on a chair with your back straight and your right knee bent at approximately 90°, foot hanging freely.') }}</p>
                            @break
                        @case('right_hip')
                            <p class="text-sm text-gray-700">{{ __('Stand upright with feet together and your right leg straight down beside your left leg. Hold a wall or support if needed for balance.') }}</p>
                            @break
                        @default
                            <p class="text-sm text-gray-700">{{ $session->exercise->description ?? '' }}</p>
                    @endswitch
                </div>

                <div>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Movement') }}</p>
                    <ol class="space-y-1.5 text-sm text-gray-700 list-none">
                        @switch($session->exercise->target_joint)
                            @case('right_shoulder')
                            @case('left_shoulder')
                                <li class="flex gap-2"><span class="font-black text-indigo-600">1.</span>{{ __('Slowly raise your arm forward and upward in a wide arc.') }}</li>
                                <li class="flex gap-2"><span class="font-black text-indigo-600">2.</span>{{ __('Continue until your arm is fully extended overhead (or as high as comfortable).') }}</li>
                                <li class="flex gap-2"><span class="font-black text-indigo-600">3.</span>{{ __('Lower it back down slowly and with control. That is one rep.') }}</li>
                                @break
                            @case('right_elbow')
                                <li class="flex gap-2"><span class="font-black text-indigo-600">1.</span>{{ __('Slowly curl your forearm upward, bending at the elbow.') }}</li>
                                <li class="flex gap-2"><span class="font-black text-indigo-600">2.</span>{{ __('Bring your hand toward your shoulder as far as comfortable.') }}</li>
                                <li class="flex gap-2"><span class="font-black text-indigo-600">3.</span>{{ __('Slowly lower your forearm back to the starting position. That is one rep.') }}</li>
                                @break
                            @case('right_knee')
                                <li class="flex gap-2"><span class="font-black text-indigo-600">1.</span>{{ __('Slowly straighten your right leg by extending the knee forward.') }}</li>
                                <li class="flex gap-2"><span class="font-black text-indigo-600">2.</span>{{ __('Hold for 1–2 seconds when the leg is fully extended.') }}</li>
                                <li class="flex gap-2"><span class="font-black text-indigo-600">3.</span>{{ __('Lower your foot back down to the starting position. That is one rep.') }}</li>
                                @break
                            @case('right_hip')
                                <li class="flex gap-2"><span class="font-black text-indigo-600">1.</span>{{ __('Slowly lift your right knee upward and forward, bending at the hip.') }}</li>
                                <li class="flex gap-2"><span class="font-black text-indigo-600">2.</span>{{ __('Raise until your thigh is roughly parallel to the floor (or as high as comfortable).') }}</li>
                                <li class="flex gap-2"><span class="font-black text-indigo-600">3.</span>{{ __('Lower your leg back down with control. That is one rep.') }}</li>
                                @break
                        @endswitch
                    </ol>
                </div>

                <div class="bg-indigo-50 rounded-xl px-4 py-2.5 flex items-center gap-3 text-xs text-indigo-700 border border-indigo-100">
                    <i class="fas fa-ruler-combined text-indigo-400" aria-hidden="true"></i>
                    <span>{{ __('Target angle range') }}: <strong>{{ $session->exercise->thresholds['min_angle'] }}°</strong> → <strong>{{ $session->exercise->thresholds['max_angle'] }}°</strong></span>
                </div>
            </div>

            {{-- SVG animation --}}
            <div class="flex-shrink-0 flex items-center justify-center w-full sm:w-44">
                @switch($session->exercise->target_joint)

                    @case('right_shoulder')
                    <svg viewBox="0 0 180 260" class="w-36 sm:w-44 h-auto" role="img" aria-label="{{ __('Shoulder raise demonstration') }}">
                        <style>
                            @keyframes anim-right-shoulder { 0% { transform: rotate(0deg); } 100% { transform: rotate(-130deg); } }
                            .anim-right-shoulder { animation: anim-right-shoulder 1.6s ease-in-out infinite alternate; transform-origin: 108px 72px; }
                        </style>
                        <circle cx="90" cy="22" r="14" fill="none" stroke="#6366f1" stroke-width="3"/>
                        <line x1="90" y1="36" x2="90" y2="152" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                        <line x1="72" y1="72" x2="50" y2="125" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                        <line x1="90" y1="152" x2="76" y2="220" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                        <line x1="90" y1="152" x2="104" y2="220" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                        <g class="anim-right-shoulder">
                            <line x1="108" y1="72" x2="132" y2="128" stroke="#22c55e" stroke-width="4" stroke-linecap="round"/>
                            <circle cx="132" cy="128" r="5" fill="#22c55e"/>
                        </g>
                        <circle cx="72" cy="72" r="4" fill="#6366f1"/>
                        <circle cx="108" cy="72" r="5" fill="#22c55e"/>
                    </svg>
                    @break

                    @case('left_shoulder')
                    <svg viewBox="0 0 180 260" class="w-36 sm:w-44 h-auto" role="img" aria-label="{{ __('Shoulder raise demonstration') }}">
                        <style>
                            @keyframes anim-left-shoulder { 0% { transform: rotate(0deg); } 100% { transform: rotate(130deg); } }
                            .anim-left-shoulder { animation: anim-left-shoulder 1.6s ease-in-out infinite alternate; transform-origin: 72px 72px; }
                        </style>
                        <circle cx="90" cy="22" r="14" fill="none" stroke="#6366f1" stroke-width="3"/>
                        <line x1="90" y1="36" x2="90" y2="152" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                        <line x1="108" y1="72" x2="130" y2="125" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                        <line x1="90" y1="152" x2="76" y2="220" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                        <line x1="90" y1="152" x2="104" y2="220" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                        <g class="anim-left-shoulder">
                            <line x1="72" y1="72" x2="48" y2="128" stroke="#22c55e" stroke-width="4" stroke-linecap="round"/>
                            <circle cx="48" cy="128" r="5" fill="#22c55e"/>
                        </g>
                        <circle cx="72" cy="72" r="5" fill="#22c55e"/>
                        <circle cx="108" cy="72" r="4" fill="#6366f1"/>
                    </svg>
                    @break

                    @case('right_elbow')
                    <svg viewBox="0 0 180 260" class="w-36 sm:w-44 h-auto" role="img" aria-label="{{ __('Elbow flexion demonstration') }}">
                        <style>
                            @keyframes anim-right-elbow { 0% { transform: rotate(0deg); } 100% { transform: rotate(-115deg); } }
                            .anim-right-elbow { animation: anim-right-elbow 1.6s ease-in-out infinite alternate; transform-origin: 114px 135px; }
                        </style>
                        <circle cx="90" cy="22" r="14" fill="none" stroke="#6366f1" stroke-width="3"/>
                        <line x1="90" y1="36" x2="90" y2="152" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                        <line x1="72" y1="72" x2="50" y2="125" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                        <line x1="108" y1="72" x2="114" y2="135" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                        <line x1="90" y1="152" x2="76" y2="220" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                        <line x1="90" y1="152" x2="104" y2="220" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                        <g class="anim-right-elbow">
                            <line x1="114" y1="135" x2="120" y2="205" stroke="#22c55e" stroke-width="4" stroke-linecap="round"/>
                            <circle cx="120" cy="205" r="5" fill="#22c55e"/>
                        </g>
                        <circle cx="108" cy="72" r="4" fill="#6366f1"/>
                        <circle cx="114" cy="135" r="5" fill="#22c55e"/>
                    </svg>
                    @break

                    @case('right_knee')
                    <svg viewBox="0 0 200 260" class="w-36 sm:w-44 h-auto" role="img" aria-label="{{ __('Knee extension demonstration') }}">
                        <style>
                            @keyframes anim-right-knee { 0% { transform: rotate(0deg); } 100% { transform: rotate(-120deg); } }
                            .anim-right-knee { animation: anim-right-knee 1.6s ease-in-out infinite alternate; transform-origin: 115px 185px; }
                        </style>
                        <circle cx="90" cy="22" r="14" fill="none" stroke="#6366f1" stroke-width="3"/>
                        <line x1="90" y1="36" x2="90" y2="130" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                        <line x1="60" y1="130" x2="150" y2="130" stroke="#94a3b8" stroke-width="2" stroke-dasharray="4 3"/>
                        <line x1="80" y1="130" x2="60" y2="185" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                        <line x1="100" y1="130" x2="115" y2="185" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                        <line x1="60" y1="185" x2="58" y2="245" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                        <g class="anim-right-knee">
                            <line x1="115" y1="185" x2="118" y2="248" stroke="#22c55e" stroke-width="4" stroke-linecap="round"/>
                            <circle cx="118" cy="248" r="5" fill="#22c55e"/>
                        </g>
                        <circle cx="115" cy="185" r="5" fill="#22c55e"/>
                    </svg>
                    @break

                    @case('right_hip')
                    <svg viewBox="0 0 180 260" class="w-36 sm:w-44 h-auto" role="img" aria-label="{{ __('Hip flexion demonstration') }}">
                        <style>
                            @keyframes anim-right-hip { 0% { transform: rotate(0deg); } 100% { transform: rotate(-72deg); } }
                            .anim-right-hip { animation: anim-right-hip 1.6s ease-in-out infinite alternate; transform-origin: 102px 152px; }
                        </style>
                        <circle cx="90" cy="22" r="14" fill="none" stroke="#6366f1" stroke-width="3"/>
                        <line x1="90" y1="36" x2="90" y2="152" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                        <line x1="72" y1="72" x2="50" y2="125" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                        <line x1="108" y1="72" x2="130" y2="125" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                        <line x1="82" y1="152" x2="76" y2="230" stroke="#6366f1" stroke-width="3" stroke-linecap="round"/>
                        <g class="anim-right-hip">
                            <line x1="102" y1="152" x2="108" y2="230" stroke="#22c55e" stroke-width="4" stroke-linecap="round"/>
                            <circle cx="108" cy="230" r="5" fill="#22c55e"/>
                        </g>
                        <circle cx="82" cy="152" r="4" fill="#6366f1"/>
                        <circle cx="102" cy="152" r="5" fill="#22c55e"/>
                    </svg>
                    @break

                @endswitch
            </div>

        </div>
    </div>
    @endif

    {{-- Action buttons --}}
    @if($status !== 'done' && $session->status !== 'completed')
    <div class="flex flex-col sm:flex-row gap-4">
        <button id="start-btn"
                onclick="startTracking()"
                class="flex-1 bg-teal-600 hover:bg-teal-700 active:scale-95 text-white py-4 rounded-xl font-bold text-lg shadow-lg transition-all flex items-center justify-center gap-3"
                aria-label="{{ __('Start movement tracking with webcam') }}">
            <i class="fas fa-play-circle text-xl" aria-hidden="true"></i>
            {{ __('Start Tracking') }}
        </button>

        <button wire:click="completeSession"
                wire:loading.attr="disabled"
                class="flex-1 bg-indigo-600 hover:bg-indigo-700 active:scale-95 disabled:bg-gray-300 disabled:text-gray-500 text-white py-4 rounded-xl font-bold text-lg shadow-lg transition-all flex items-center justify-center gap-3"
                aria-label="{{ __('Complete and save this tracking session') }}">
            <span wire:loading.remove wire:target="completeSession">
                <i class="fas fa-flag-checkered text-xl mr-2" aria-hidden="true"></i>{{ __('Complete Session') }}
            </span>
            <span wire:loading wire:target="completeSession">
                <i class="fas fa-circle-notch fa-spin mr-2" aria-hidden="true"></i>{{ __('Saving...') }}
            </span>
        </button>
    </div>
    @else
    <a href="{{ $exerciseUrl }}"
       class="block w-full text-center bg-teal-600 hover:bg-teal-700 text-white py-4 rounded-xl font-bold text-lg shadow-lg transition-all">
        <i class="fas fa-arrow-left mr-2" aria-hidden="true"></i> {{ __('Return to Exercise Page') }}
    </a>
    @endif

    {{-- Rep log table --}}
    @if($session->logs->isNotEmpty())
    <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100">
            <h2 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                <i class="fas fa-list-ol text-teal-500" aria-hidden="true"></i>
                {{ __('Rep Log') }}
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full text-xs sm:text-sm" aria-label="{{ __('Movement repetition log') }}">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th scope="col">{{ __('Rep') }}</th>
                        <th scope="col">{{ __('Angle') }}</th>
                        <th scope="col">{{ __('In Range') }}</th>
                        <th scope="col">{{ __('Time') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($session->logs->sortBy('rep_number') as $log)
                    <tr class="{{ $log->within_threshold ? '' : 'bg-red-50' }}">
                        <td class="font-bold">{{ $log->rep_number }}</td>
                        <td>{{ number_format($log->joint_angle, 1) }}&deg;</td>
                        <td>
                            @if($log->within_threshold)
                                <span class="badge bg-green-100 text-green-700 border-green-200 font-bold gap-1">
                                    <i class="fas fa-check" aria-hidden="true"></i> {{ __('Yes') }}
                                </span>
                            @else
                                <span class="badge bg-red-100 text-red-700 border-red-200 font-bold gap-1">
                                    <i class="fas fa-times" aria-hidden="true"></i> {{ __('No') }}
                                </span>
                            @endif
                        </td>
                        <td class="text-gray-400">{{ $log->recorded_at->format('H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</section>
</div>

@push('scripts')
@vite(['resources/js/tracking.js'])
@endpush
