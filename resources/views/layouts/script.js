 // --- DATA & STATE ---
        let currentRole = 'patient'; // patient, doctor, therapist
        let currentLang = 'id';
        let currentPage = 'dashboard';

        // --- MOCK DATA ---
        const patientData = {
            name: "Stefani Putri",
            id: "RH-99120",
            avatar: "https://api.dicebear.com/7.x/avataaars/svg?seed=Stefani",
            diagnosis: "Post-stroke hand stiffness",
            program: "Fine Motor Skills Level 2"
        };

        const doctorData = {
            name: "Dr. Budi Santoso",
            specialty: "Sp.KFR",
            avatar: "https://api.dicebear.com/7.x/avataaars/svg?seed=DoctorBudi&clothing=blazerAndShirt",
            pendingConsults: 2
        };

        const therapistData = {
            name: "Sarah Amalia",
            specialty: "Occupational Therapist",
            avatar: "https://api.dicebear.com/7.x/avataaars/svg?seed=Sarah&clothing=overall",
            pendingReviews: 5
        };

        // --- TEMPLATES ---

        const templates = {
            // --- PATIENT VIEWS ---
            patient_dashboard: `
                <section class="space-y-8 animate-in fade-in duration-500">
                    <div class="card-modern p-10 bg-gradient-to-br from-white to-teal-50 relative overflow-hidden">
                        <div class="relative z-10 lg:max-w-xl">
                            <span class="bg-teal-100 text-teal-700 px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mb-4 inline-block">Program Aktif</span>
                            <h3 class="text-3xl font-extrabold text-gray-900 mb-4">${patientData.program}</h3>
                            <p class="text-gray-500 mb-10 leading-relaxed text-lg">Sesi hari ini difokuskan pada penguatan otot jari dan kelenturan pergelangan tangan.</p>
                            <button onclick="navigate('rehab')" class="btn-primary"><i class="fas fa-circle-play mr-2"></i> MULAI REHABILITASI</button>
                        </div>
                        <img src="https://illustrations.popsy.co/teal/meditating.svg" class="absolute bottom-0 right-0 h-72 opacity-40 hidden md:block">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="card-modern p-8">
                            <h4 class="font-bold text-xl mb-6 flex items-center gap-3"><i class="fas fa-chart-line text-teal-500"></i> Progres Mingguan</h4>
                            <div class="flex items-center gap-8">
                                <div class="relative w-24 h-24">
                                    <svg class="w-full h-full" viewBox="0 0 36 36">
                                        <path stroke="#E8F1F3" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                        <path stroke="#94B9C5" stroke-width="3" stroke-dasharray="75, 100" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center font-black text-xl text-teal-600">75%</div>
                                </div>
                                <div><p class="text-sm font-bold text-gray-800">12 Sesi Selesai</p><p class="text-xs text-gray-400 mt-1">4 Sesi lagi.</p></div>
                            </div>
                        </div>
                        <div class="card-modern p-8 flex items-center justify-between">
                            <div>
                                <h4 class="font-bold text-xl mb-2">Konsultasi Dokter</h4>
                                <p class="text-gray-500 text-sm">Jadwal: Besok, 10:30 WIB</p>
                                <p class="text-[10px] text-teal-600 font-bold mt-2 uppercase tracking-widest"><i class="fas fa-circle text-[6px] mr-1"></i> Terkonfirmasi</p>
                            </div>
                            <button onclick="navigate('consultation')" class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl hover:bg-teal-100 transition-colors"><i class="fas fa-arrow-right"></i></button>
                        </div>
                    </div>
                </section>`,

            patient_rehab: `
                <section class="space-y-8 animate-in fade-in duration-500">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                        <div class="lg:col-span-2 space-y-8">
                            <div class="card-modern p-5">
                                <h4 class="font-bold text-gray-800 mb-4 px-3 flex items-center gap-2"><i class="fas fa-video text-teal-500"></i> Video Panduan dr. Budi</h4>
                                <div class="video-container">
                                    <img src="https://images.unsplash.com/photo-1576091160550-2173dad99901?auto=format&fit=crop&w=1200" class="absolute inset-0 w-full h-full object-cover opacity-40">
                                    <button class="relative z-10 w-24 h-24 bg-white bg-opacity-95 rounded-full shadow-2xl flex items-center justify-center text-teal-600 text-3xl"><i class="fas fa-play ml-1"></i></button>
                                </div>
                            </div>
                            <div class="card-modern p-8">
                                <h4 class="font-bold text-xl mb-6 flex items-center gap-3"><i class="fas fa-history text-teal-500"></i> Riwayat Upload & Feedback</h4>
                                <div class="border-b border-gray-100 pb-6 mb-6">
                                    <div class="flex flex-col md:flex-row gap-6">
                                        <div class="w-full md:w-40 h-24 bg-gray-800 rounded-xl overflow-hidden relative flex-shrink-0 flex items-center justify-center text-white"><i class="fas fa-play"></i></div>
                                        <div class="flex-1 space-y-3">
                                            <div class="flex justify-between items-start">
                                                <div><h5 class="font-bold text-gray-800 text-sm">Latihan Peregangan Jari</h5><p class="text-[10px] text-gray-400 font-bold uppercase mt-1">26 Des 2025</p></div>
                                                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-[10px] font-black uppercase">Reviewed</span>
                                            </div>
                                            <div class="bg-blue-50 p-3 rounded-xl border border-blue-100 flex gap-3 items-start">
                                                <div class="w-6 h-6 rounded-full bg-blue-200 flex items-center justify-center text-blue-600 text-[10px] flex-shrink-0 mt-0.5"><i class="fas fa-user-md"></i></div>
                                                <div><p class="text-[10px] font-black text-blue-700 uppercase mb-1">Dr. Budi</p><p class="text-xs text-blue-900 leading-relaxed italic">"Fleksibilitas meningkat 10%. Bagus!"</p></div>
                                            </div>
                                            <div class="bg-orange-50 p-3 rounded-xl border border-orange-100 flex gap-3 items-start">
                                                <div class="w-6 h-6 rounded-full bg-orange-200 flex items-center justify-center text-orange-600 text-[10px] flex-shrink-0 mt-0.5"><i class="fas fa-user-nurse"></i></div>
                                                <div><p class="text-[10px] font-black text-orange-700 uppercase mb-1">Terapis Sarah</p><p class="text-xs text-orange-900 leading-relaxed italic">"Tahan posisi puncak 2 detik lebih lama."</p></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-8">
                            <div id="uploadZone" class="card-modern p-10 border-4 border-dashed border-teal-50 flex flex-col items-center text-center py-20">
                                <div class="w-24 h-24 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center text-4xl mb-6 shadow-inner"><i class="fas fa-camera"></i></div>
                                <h4 class="text-xl font-extrabold text-gray-900 mb-2">Upload Video</h4>
                                <p class="text-sm text-gray-400 mb-10 leading-relaxed px-4">Rekam latihan Anda agar dokter & terapis dapat memantau.</p>
                                <button onclick="simulateUpload()" class="btn-primary w-full justify-center"><i class="fas fa-circle-dot mr-2"></i> REKAM VIDEO</button>
                            </div>
                        </div>
                    </div>
                </section>`,

            // --- DOCTOR VIEWS ---
            doctor_dashboard: `
                <section class="space-y-8 animate-in fade-in duration-500">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="card-modern p-6 bg-blue-50 border-blue-100">
                            <div class="flex justify-between items-start">
                                <div><p class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Total Pasien</p><h3 class="text-3xl font-extrabold text-blue-900 mt-1">24</h3></div>
                                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-500"><i class="fas fa-users"></i></div>
                            </div>
                        </div>
                        <div class="card-modern p-6 bg-orange-50 border-orange-100">
                            <div class="flex justify-between items-start">
                                <div><p class="text-[10px] font-black text-orange-400 uppercase tracking-widest">Permintaan Konsul</p><h3 class="text-3xl font-extrabold text-orange-900 mt-1">3</h3></div>
                                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-orange-500"><i class="fas fa-calendar-check"></i></div>
                            </div>
                        </div>
                         <div class="card-modern p-6 bg-teal-50 border-teal-100">
                            <div class="flex justify-between items-start">
                                <div><p class="text-[10px] font-black text-teal-400 uppercase tracking-widest">Perlu Review</p><h3 class="text-3xl font-extrabold text-teal-900 mt-1">5</h3></div>
                                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-teal-500"><i class="fas fa-clipboard-check"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2 card-modern p-8">
                            <h4 class="font-bold text-xl mb-6 text-gray-800">Permintaan Konsultasi Baru</h4>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                    <div class="flex items-center gap-4">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Stefani" class="w-12 h-12 rounded-full bg-white border-2 border-white shadow-sm">
                                        <div>
                                            <h5 class="font-bold text-gray-900">Stefani Putri</h5>
                                            <p class="text-xs text-gray-500">Keluhan: Nyeri pergelangan tangan pasca latihan.</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-200"><i class="fas fa-times"></i></button>
                                        <button onclick="navigate('patient_detail')" class="w-10 h-10 rounded-xl bg-teal-500 text-white shadow-lg shadow-teal-200"><i class="fas fa-check"></i></button>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                    <div class="flex items-center gap-4">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Budi" class="w-12 h-12 rounded-full bg-white border-2 border-white shadow-sm">
                                        <div>
                                            <h5 class="font-bold text-gray-900">Budi Santoso</h5>
                                            <p class="text-xs text-gray-500">Jadwal Rutin Bulanan</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-200"><i class="fas fa-times"></i></button>
                                        <button class="w-10 h-10 rounded-xl bg-teal-500 text-white shadow-lg shadow-teal-200"><i class="fas fa-check"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                         <div class="card-modern p-8">
                            <h4 class="font-bold text-xl mb-6 text-gray-800">Daftar Pasien</h4>
                            <ul class="space-y-3">
                                <li onclick="navigate('patient_detail')" class="cursor-pointer flex items-center gap-3 p-3 hover:bg-teal-50 rounded-xl transition-colors">
                                    <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                    <span class="font-bold text-gray-600 text-sm flex-1">Stefani Putri</span>
                                    <i class="fas fa-chevron-right text-xs text-gray-300"></i>
                                </li>
                                <li class="cursor-pointer flex items-center gap-3 p-3 hover:bg-teal-50 rounded-xl transition-colors">
                                    <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                                    <span class="font-bold text-gray-600 text-sm flex-1">Ahmad Dhani</span>
                                    <i class="fas fa-chevron-right text-xs text-gray-300"></i>
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>`,

            doctor_patient_detail: `
                <section class="space-y-8 animate-in fade-in duration-500">
                    <button onclick="navigate('dashboard')" class="text-sm font-bold text-gray-400 hover:text-teal-600 mb-4"><i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard</button>
                    
                    <div class="card-modern p-8 bg-white border-l-8 border-teal-500">
                        <div class="flex items-start justify-between">
                            <div class="flex gap-6">
                                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Stefani" class="w-24 h-24 rounded-2xl bg-gray-100">
                                <div>
                                    <h2 class="text-2xl font-extrabold text-gray-900">Stefani Putri</h2>
                                    <p class="text-gray-500 font-medium">Pasien Pasca-Stroke &bull; RH-99120</p>
                                    <div class="flex gap-2 mt-4">
                                        <button class="px-4 py-2 bg-teal-100 text-teal-700 rounded-lg text-xs font-bold" onclick="showToast('Sesi Dimulai', 'Membuka ruang konsultasi video...')"><i class="fas fa-video mr-2"></i> Mulai Konsultasi</button>
                                        <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-xs font-bold"><i class="fas fa-file-medical mr-2"></i> Rekam Medis</button>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-black text-gray-400 uppercase">Status Rehab</p>
                                <p class="text-xl font-bold text-green-500">Minggu ke-4</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Diagnosis & Plan -->
                        <div class="card-modern p-8">
                            <h4 class="font-bold text-xl mb-6 text-gray-800"><i class="fas fa-stethoscope text-teal-500 mr-2"></i> Diagnosa & Rencana</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase mb-2">Diagnosa Terkini</label>
                                    <textarea class="w-full bg-gray-50 border-0 rounded-xl p-4 text-sm font-medium text-gray-700 h-24 resize-none focus:ring-2 ring-teal-200 outline-none">Kekakuan sendi MCP jari telunjuk dan tengah berkurang. Perlu peningkatan repetisi untuk ketahanan otot.</textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase mb-2">Program Latihan</label>
                                    <select class="w-full bg-gray-50 border-0 rounded-xl p-4 text-sm font-bold text-gray-700 outline-none">
                                        <option>Level 1: Peregangan Pasif</option>
                                        <option selected>Level 2: Motorik Halus & Menggenggam</option>
                                        <option>Level 3: Kekuatan & Ketahanan</option>
                                    </select>
                                </div>
                                <button onclick="showToast('Tersimpan', 'Rencana perawatan diperbarui')" class="w-full py-3 bg-gray-800 text-white rounded-xl font-bold text-sm mt-2 hover:bg-gray-900">Simpan Perubahan</button>
                            </div>
                        </div>

                        <!-- Feedback Monitor -->
                        <div class="card-modern p-8">
                            <h4 class="font-bold text-xl mb-6 text-gray-800"><i class="fas fa-comments text-teal-500 mr-2"></i> Monitoring & Feedback</h4>
                            <p class="text-xs text-gray-400 mb-4">Upload terbaru dari pasien:</p>
                            
                            <!-- Video Item -->
                            <div class="p-4 border border-gray-100 rounded-2xl mb-4 hover:shadow-lg transition-all bg-white">
                                <div class="flex gap-4 mb-3">
                                    <div class="w-20 h-14 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400"><i class="fas fa-play"></i></div>
                                    <div>
                                        <p class="font-bold text-sm text-gray-800">Latihan Menggenggam Bola</p>
                                        <p class="text-[10px] text-gray-400">Hari ini, 09:00 WIB</p>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <input type="text" placeholder="Tulis feedback singkat..." class="w-full bg-gray-50 border-0 rounded-lg p-3 text-xs">
                                    <div class="flex gap-2">
                                        <button onclick="showToast('Feedback Terkirim', 'Pesan teks telah dikirim')" class="flex-1 py-2 bg-teal-500 text-white rounded-lg text-xs font-bold">Kirim Teks</button>
                                        <button onclick="showToast('Mode Rekam', 'Kamera diaktifkan untuk feedback video')" class="flex-1 py-2 bg-orange-100 text-orange-600 rounded-lg text-xs font-bold"><i class="fas fa-video mr-1"></i> Balas Video</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>`,

            // --- THERAPIST VIEWS ---
            therapist_dashboard: `
                <section class="space-y-8 animate-in fade-in duration-500">
                     <div class="card-modern p-6 bg-teal-600 text-white relative overflow-hidden">
                        <div class="relative z-10">
                            <h3 class="text-2xl font-extrabold mb-2">Daily Monitoring</h3>
                            <p class="text-teal-100">Anda memiliki 5 video latihan pasien yang belum di-review hari ini.</p>
                        </div>
                        <i class="fas fa-clipboard-list absolute -bottom-4 -right-4 text-9xl text-teal-700 opacity-50"></i>
                    </div>

                    <h4 class="font-bold text-xl text-gray-800 mt-8">Antrian Review Video</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Review Card 1 -->
                        <div class="card-modern p-0 overflow-hidden group">
                            <div class="relative h-48 bg-gray-900">
                                <img src="https://images.unsplash.com/photo-1579684385180-1ea55f9f7485?auto=format&fit=crop&w=500" class="w-full h-full object-cover opacity-60 group-hover:opacity-40 transition-opacity">
                                <button class="absolute inset-0 flex items-center justify-center text-white text-4xl"><i class="fas fa-play-circle"></i></button>
                                <span class="absolute top-4 left-4 bg-red-500 text-white text-[10px] font-black px-2 py-1 rounded">URGENT</span>
                            </div>
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h5 class="font-bold text-gray-900">Stefani Putri</h5>
                                        <p class="text-xs text-gray-500">Peregangan Jari &bull; Set 3</p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <textarea placeholder="Catatan teknis..." class="w-full bg-gray-50 border-0 rounded-xl p-3 text-xs h-20 resize-none"></textarea>
                                    <div class="flex gap-2">
                                        <button onclick="showToast('Terkirim', 'Feedback video direkam')" class="w-10 h-10 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-100"><i class="fas fa-video"></i></button>
                                        <button onclick="showToast('Selesai', 'Latihan ditandai selesai')" class="flex-1 h-10 rounded-lg bg-teal-500 text-white text-xs font-bold hover:bg-teal-600">Submit Review</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Review Card 2 -->
                         <div class="card-modern p-0 overflow-hidden group">
                            <div class="relative h-48 bg-gray-900">
                                <div class="absolute inset-0 flex items-center justify-center text-white text-4xl bg-gray-800"><i class="fas fa-video-slash"></i></div>
                            </div>
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h5 class="font-bold text-gray-900">Budi Santoso</h5>
                                        <p class="text-xs text-gray-500">Rotasi Pergelangan</p>
                                    </div>
                                </div>
                                <button class="w-full py-3 bg-gray-100 text-gray-400 rounded-xl font-bold text-xs">Menunggu Upload...</button>
                            </div>
                        </div>
                    </div>
                </section>`
        };

        const navConfigs = {
            patient: [{
                    id: 'dashboard',
                    icon: 'fa-grid-2',
                    label: 'Dashboard'
                },
                {
                    id: 'rehab',
                    icon: 'fa-play-circle',
                    label: 'Mulai Rehab'
                },
                {
                    id: 'consultation',
                    icon: 'fa-video',
                    label: 'Konsultasi'
                },
                {
                    id: 'history',
                    icon: 'fa-clock-rotate-left',
                    label: 'Riwayat'
                }
            ],
            doctor: [{
                    id: 'dashboard',
                    icon: 'fa-user-md',
                    label: 'Doctor Dashboard'
                },
                {
                    id: 'patients',
                    icon: 'fa-users',
                    label: 'Data Pasien'
                },
                {
                    id: 'schedule',
                    icon: 'fa-calendar-alt',
                    label: 'Jadwal Konsul'
                }
            ],
            therapist: [{
                    id: 'dashboard',
                    icon: 'fa-clipboard-list',
                    label: 'Daily Review'
                },
                {
                    id: 'patients',
                    icon: 'fa-users',
                    label: 'Pasien'
                }
            ]
        };

        // --- CORE FUNCTIONS ---

        function init() {
            renderNav();
            loadPage('dashboard');
        }

        function changeRole(role) {
            currentRole = role;
            currentPage = 'dashboard';

            // Update UI visuals
            const badge = document.getElementById('roleLabelSidebar');
            badge.innerText = role + " MODE";

            const avatar = document.getElementById('userAvatar');
            if (role === 'patient') avatar.src = patientData.avatar;
            else if (role === 'doctor') avatar.src = doctorData.avatar;
            else avatar.src = therapistData.avatar;

            // Header Texts
            const title = document.getElementById('pageTitle');
            const sub = document.getElementById('pageSubtitle');

            if (role === 'patient') {
                title.innerText = `Halo, ${patientData.name.split(' ')[0]}!`;
                sub.innerText = "Siap untuk progres pemulihan hari ini?";
            } else if (role === 'doctor') {
                title.innerText = `Halo, ${doctorData.name}`;
                sub.innerText = "Ada 3 permintaan konsultasi menunggu.";
            } else {
                title.innerText = `Halo, ${therapistData.name}`;
                sub.innerText = "Monitoring harian siap.";
            }

            renderNav();
            loadPage('dashboard');
            showToast("Mode Berubah", `Sekarang dalam tampilan ${role}`);
        }

        function renderNav() {
            const navItems = navConfigs[currentRole];
            const sidebarNav = document.getElementById('sidebarNav');
            const mobileNav = document.getElementById('mobileNav');

            // Clear existing
            sidebarNav.innerHTML = '';
            mobileNav.innerHTML = '';

            navItems.forEach(item => {
                // Desktop Sidebar
                const btn = document.createElement('button');
                btn.className = `w-full flex items-center gap-4 p-4 text-gray-500 font-bold rounded-2xl hover:bg-gray-50 transition-all ${item.id === currentPage ? 'nav-active' : ''}`;
                btn.onclick = () => navigate(item.id);
                btn.innerHTML = `<i class="fas ${item.icon} text-lg"></i> <span>${item.label}</span>`;
                sidebarNav.appendChild(btn);

                // Mobile Nav
                const mobBtn = document.createElement('button');
                mobBtn.className = `flex flex-col items-center gap-1 ${item.id === currentPage ? 'text-teal-600' : 'text-gray-400'}`;
                mobBtn.onclick = () => navigate(item.id);
                mobBtn.innerHTML = `<i class="fas ${item.icon} text-xl"></i><span class="text-[10px] font-bold">${item.label.split(' ')[0]}</span>`;
                mobileNav.appendChild(mobBtn);
            });
        }

        function navigate(pageId) {
            currentPage = pageId;
            renderNav(); // Re-render to update active state
            loadPage(pageId);
        }

        function loadPage(pageId) {
            const container = document.getElementById('mainContainer');

            // Special routing for 'patient_detail' in doctor view
            let templateKey = `${currentRole}_${pageId}`;

            // Fallback generic or specific mapping
            if (pageId === 'patient_detail' && currentRole === 'doctor') templateKey = 'doctor_patient_detail';

            // Check if template exists
            if (templates[templateKey]) {
                container.innerHTML = templates[templateKey];
            } else {
                // Fallback for pages not fully implemented in mock
                container.innerHTML = `
                    <div class="card-modern p-12 text-center">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 text-3xl"><i class="fas fa-wrench"></i></div>
                        <h3 class="text-xl font-bold text-gray-800">Halaman Belum Tersedia</h3>
                        <p class="text-gray-500 mt-2">Simulasi untuk halaman ini sedang dalam pengembangan.</p>
                        <button onclick="navigate('dashboard')" class="mt-6 text-teal-600 font-bold hover:underline">Kembali ke Dashboard</button>
                    </div>
                `;
            }

            // Voice announce
            if (currentLang === 'id') announce("Membuka " + pageId.replace('_', ' '));
        }

        function simulateUpload() {
            showToast("Upload Berhasil", "Video telah dikirim ke Dokter & Therapist");
            // In a real app, this would update history
        }

        function showToast(title, body) {
            const toast = document.getElementById('toastAlert');
            document.getElementById('alertHead').innerText = title;
            document.getElementById('alertBody').innerText = body;
            toast.classList.remove('hidden');
            toast.classList.add('flex');
            setTimeout(() => {
                toast.classList.add('hidden');
                toast.classList.remove('flex');
            }, 4000);
            announce(title);
        }

        // --- VOICE & UTILS ---
        function changeLanguage(lang) {
            currentLang = lang;
            showToast("Bahasa", lang === 'id' ? "Bahasa Indonesia" : "English");
        }

        function scrollPage(direction) {
            window.scrollBy({
                top: direction === 'down' ? 300 : -300,
                behavior: 'smooth'
            });
        }

        const synth = window.speechSynthesis;

        function announce(text) {
            if (synth.speaking) synth.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = currentLang === 'id' ? 'id-ID' : 'en-US';
            utterance.rate = 1.1;
            synth.speak(utterance);
        }

        // Initialize
        window.onload = init;