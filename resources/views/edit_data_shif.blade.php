<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Shift Staf</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #001931; }
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: rgba(156, 163, 175, 0.5); border-radius: 20px; }
    </style>
</head>
<body class="text-slate-800 selection:bg-blue-200">

    <div id="toast" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 hidden items-center gap-3 px-6 py-3 rounded-full shadow-2xl transition-all duration-300">
        <i data-lucide="info" class="w-5 h-5"></i>
        <span id="toast-message" class="font-medium">Pesan notifikasi</span>
    </div>

    <div class="max-w-6xl mx-auto px-6 py-10">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white tracking-tight flex items-center gap-3">
                    <span class="bg-white/10 p-2 rounded-lg">🗓️</span>
                    Manajemen Shift
                </h1>
                <p class="text-blue-200 mt-1 text-sm">Atur jadwal staf keamanan Pagi & Malam.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="javascript:history.back()" class="group flex items-center gap-2 bg-transparent border border-blue-400/30 text-blue-100 px-6 py-3 rounded-xl font-bold hover:bg-white/5 hover:text-white hover:border-white/50 active:scale-95 transition-all cursor-pointer">
                    <i data-lucide="arrow-left" class="w-5 h-5 group-hover:-translate-x-1 transition-transform"></i>
                    Kembali
                </a>

                <button onclick="simpanPerubahan()" class="group flex items-center gap-2 bg-white text-[#001931] px-6 py-3 rounded-xl font-bold hover:bg-blue-50 hover:scale-105 active:scale-95 transition-all shadow-lg shadow-blue-900/50 cursor-pointer">
                    <i data-lucide="save" class="w-5 h-5 group-hover:rotate-12 transition-transform"></i>
                    Simpan Perubahan
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pb-20">

            <div class="flex flex-col h-full">
                <div class="bg-orange-50 rounded-t-3xl p-6 border-b-4 border-orange-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-orange-100 text-orange-600 rounded-2xl shadow-sm">
                                <i data-lucide="sun" class="w-8 h-8"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-800">Shift Pagi</h2>
                                <p class="text-xs font-semibold text-orange-600 uppercase tracking-wide">07:00 - 19:00</p>
                            </div>
                        </div>
                        <span id="count-pagi" class="bg-white text-orange-600 px-3 py-1 rounded-full text-sm font-bold shadow-sm">0 Staf</span>
                    </div>
                </div>

                <div class="bg-white/95 backdrop-blur-sm rounded-b-3xl p-6 min-h-[500px] shadow-xl border border-white/10 flex flex-col">
                    <div class="relative mb-4">
                        <select id="select-pagi" onchange="tambahStaf('Pagi', this)" class="w-full py-3 px-4 rounded-xl border-2 border-dashed border-orange-200 text-orange-600 bg-orange-50/50 hover:bg-orange-50 cursor-pointer appearance-none text-center font-medium focus:outline-none">
                            <option value="" selected disabled>+ Tambah ke Pagi</option>
                            </select>
                        <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center px-2 text-orange-600">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>

                    <div id="list-pagi" class="flex-1 space-y-2 overflow-y-auto pr-1 custom-scrollbar">
                        </div>
                </div>
            </div>

            <div class="flex flex-col h-full">
                <div class="bg-indigo-900 rounded-t-3xl p-6 border-b-4 border-indigo-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-indigo-800 text-yellow-300 rounded-2xl shadow-inner">
                                <i data-lucide="moon" class="w-8 h-8"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">Shift Malam</h2>
                                <p class="text-xs font-semibold text-indigo-300 uppercase tracking-wide">19:00 - 07:00</p>
                            </div>
                        </div>
                        <span id="count-malam" class="bg-indigo-800 text-white px-3 py-1 rounded-full text-sm font-bold shadow-sm border border-indigo-700">0 Staf</span>
                    </div>
                </div>

                <div class="bg-[#0a2744] rounded-b-3xl p-6 min-h-[500px] shadow-xl border border-indigo-900/50 flex flex-col">
                    <div class="relative mb-4">
                        <select id="select-malam" onchange="tambahStaf('Malam', this)" class="w-full py-3 px-4 rounded-xl border-2 border-dashed border-indigo-400 text-indigo-300 bg-indigo-900/30 hover:bg-indigo-900/50 cursor-pointer appearance-none text-center font-medium focus:outline-none">
                            <option value="" selected disabled>+ Tambah ke Malam</option>
                            </select>
                        <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center px-2 text-indigo-300">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>

                    <div id="list-malam" class="flex-1 space-y-2 overflow-y-auto pr-1 custom-scrollbar">
                         </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Mengambil data dari controller Laravel dan mengubahnya jadi JSON object
        let allStaf = @json($stafs);

        // Render awal saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            renderAll();
        });

        function renderAll() {
            renderList('Pagi');
            renderList('Malam');
            updateDropdowns();
            lucide.createIcons();
        }

        function renderList(tipe) {
            const container = document.getElementById(`list-${tipe.toLowerCase()}`);
            const countSpan = document.getElementById(`count-${tipe.toLowerCase()}`);

            // Filter staf berdasarkan jadwal
            const filteredStaf = allStaf.filter(s => s.jadwal === tipe);

            // Update jumlah
            countSpan.innerText = `${filteredStaf.length} Staf`;

            container.innerHTML = '';

            if (filteredStaf.length === 0) {
                container.innerHTML = `
                    <div class="h-full flex flex-col items-center justify-center ${tipe === 'Pagi' ? 'text-slate-400 opacity-60' : 'text-indigo-300 opacity-40'} py-10">
                        <i data-lucide="${tipe === 'Pagi' ? 'sun' : 'moon'}" class="w-12 h-12 mb-2"></i>
                        <p>Belum ada staf ${tipe.toLowerCase()}</p>
                    </div>
                `;
                return;
            }

            filteredStaf.forEach(staf => {
                const isPagi = tipe === 'Pagi';
                const cardClass = isPagi
                    ? 'bg-white border-gray-100 hover:shadow-md'
                    : 'bg-[#0f3057] border-indigo-900/30 hover:bg-[#133a66]';

                const textClass = isPagi ? 'text-slate-800' : 'text-white';
                const subTextClass = isPagi ? 'text-slate-500' : 'text-indigo-300';
                const avatarBg = isPagi ? 'bg-orange-400' : 'bg-indigo-500';
                const btnClass = isPagi
                    ? 'text-indigo-600 bg-indigo-50 hover:bg-indigo-100'
                    : 'text-orange-400 bg-orange-900/30 hover:bg-orange-900/50';

                // Inisial nama
                const initial = staf.nama_staf ? staf.nama_staf.substring(0, 2).toUpperCase() : '??';

                const html = `
                <div class="group flex items-center justify-between p-3 mb-2 rounded-xl border transition-all duration-300 ${cardClass}">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm ${avatarBg} overflow-hidden">
                             ${staf.foto_staf
                                ? `<img src="/storage/${staf.foto_staf}" class="w-full h-full object-cover">`
                                : initial}
                        </div>
                        <div>
                            <p class="text-sm font-bold ${textClass}">${staf.nama_staf}</p>
                            <p class="text-xs ${subTextClass}">${staf.role || 'Staf'}</p>
                        </div>
                    </div>
                    <button onclick="pindahShift(${staf.id_staf}, '${isPagi ? 'Malam' : 'Pagi'}')"
                        class="opacity-0 group-hover:opacity-100 transition-opacity px-3 py-1.5 text-xs font-medium rounded-lg ${btnClass}">
                        Pindah ke ${isPagi ? 'Malam' : 'Pagi'}
                    </button>
                </div>
                `;
                container.insertAdjacentHTML('beforeend', html);
            });
        }

        function updateDropdowns() {
            // Dropdown Pagi (Hanya tampilkan yg BUKAN Pagi)
            fillDropdown('select-pagi', allStaf.filter(s => s.jadwal !== 'Pagi'));
            // Dropdown Malam (Hanya tampilkan yg BUKAN Malam)
            fillDropdown('select-malam', allStaf.filter(s => s.jadwal !== 'Malam'));
        }

        function fillDropdown(elementId, items) {
            const select = document.getElementById(elementId);
            // Simpan opsi pertama (placeholder)
            const firstOption = select.options[0];
            select.innerHTML = '';
            select.appendChild(firstOption);

            items.forEach(staf => {
                const option = document.createElement('option');
                option.value = staf.id_staf;
                option.text = staf.nama_staf + (staf.jadwal ? ` (Saat ini: ${staf.jadwal})` : '');
                select.appendChild(option);
            });
            // Reset selection ke default
            select.value = "";
        }

        // --- Logic Interaksi ---

        function tambahStaf(targetShift, selectElement) {
            const idStaf = parseInt(selectElement.value);
            if (!idStaf) return;

            // Update data lokal
            allStaf = allStaf.map(s => {
                if (s.id_staf === idStaf) return { ...s, jadwal: targetShift };
                return s;
            });

            renderAll();
        }

        function pindahShift(idStaf, targetShift) {
            allStaf = allStaf.map(s => {
                if (s.id_staf === idStaf) return { ...s, jadwal: targetShift };
                return s;
            });
            renderAll();
        }

        // --- Logic Simpan ke Server ---

        function showToast(type, message) {
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toast-message');

            toast.className = `fixed top-6 left-1/2 transform -translate-x-1/2 z-50 flex items-center gap-3 px-6 py-3 rounded-full shadow-2xl animate-bounce ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;

            toastMsg.innerText = message;
            toast.classList.remove('hidden');

            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }

        function simpanPerubahan() {
            const btn = document.querySelector('button[onclick="simpanPerubahan()"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = 'Menyimpan...';
            btn.disabled = true;

            const pagiIds = allStaf.filter(s => s.jadwal === 'Pagi').map(s => s.id_staf);
            const malamIds = allStaf.filter(s => s.jadwal === 'Malam').map(s => s.id_staf);

            fetch('{{ route("pemilik.shift.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    pagi_ids: pagiIds,
                    malam_ids: malamIds
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    showToast('success', 'Jadwal shift berhasil disimpan!');
                } else {
                    showToast('error', 'Gagal menyimpan.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Terjadi kesalahan sistem.');
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                lucide.createIcons(); // Re-render icons di tombol
            });
        }
    </script>
</body>
</html>
