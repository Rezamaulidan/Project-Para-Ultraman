@extends('profil_pemilik')
@section('title', 'Laporan Pengeluaran - SIMK')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
    prefix: 'tw-',
    corePlugins: {
        preflight: false,
    },
    theme: {
        extend: {
            colors: {
                navydark: '#001931',
                navysoft: '#002a4d',
            },
            fontFamily: {
                sans: ['Quicksand', 'sans-serif'],
            }
        }
    }
}
</script>

<style>
.custom-wrapper {
    font-family: 'Quicksand', sans-serif;
    color: #1f2937;
}

.custom-wrapper input {
    width: 100%;
    display: block;
}

::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #001931;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #002a4d;
}
</style>

<div class="custom-wrapper tw-w-full tw-bg-gray-50 tw-text-gray-800 tw-font-sans tw-min-h-screen tw-p-4">

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
    <div class="tw-container tw-mx-auto tw-mb-4 tw-px-4 tw-max-w-7xl">
        <div class="tw-bg-green-100 tw-border tw-border-green-400 tw-text-green-700 tw-px-4 tw-py-3 tw-rounded tw-relative"
            role="alert">
            <strong class="tw-font-bold">Berhasil!</strong>
            <span class="tw-block sm:tw-inline">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <div class="tw-container tw-mx-auto tw-px-4 tw-py-8 tw-max-w-7xl">

        <div class="tw-flex tw-flex-col md:tw-flex-row tw-justify-between tw-items-center tw-mb-8 tw-gap-4">
            <div>
                <h1 class="tw-text-3xl tw-font-bold tw-text-navydark tw-flex tw-items-center tw-m-0">
                    <i class="fa-solid fa-wallet tw-mr-2"></i> Pengeluaran Operasional
                </h1>
                <p class="tw-text-gray-500 tw-mt-1">Kelola belanja keperluan kos dengan mudah, Bu!</p>
            </div>

            <div
                class="tw-bg-navydark tw-text-white tw-px-6 tw-py-4 tw-rounded-2xl tw-shadow-lg tw-flex tw-items-center tw-gap-4 tw-transform hover:tw-scale-105 tw-transition tw-duration-300">
                <div
                    class="tw-bg-white/20 tw-p-3 tw-rounded-full tw-flex tw-justify-center tw-items-center tw-w-12 tw-h-12">
                    <i class="fa-solid fa-money-bill-wave tw-text-2xl"></i>
                </div>
                <div>
                    <p class="tw-text-xs tw-opacity-80 tw-uppercase tw-tracking-wide tw-m-0">Total Bulan Ini</p>
                    {{-- DINAMIS: Menampilkan Total Bulan Ini --}}
                    <h2 class="tw-text-2xl tw-font-bold tw-m-0">Rp {{ number_format($totalBulanIni, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>

        <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-8">

            {{-- FORM INPUT PENGELUARAN --}}
            <div class="lg:tw-col-span-1">
                <div
                    class="tw-bg-white tw-p-6 tw-rounded-3xl tw-shadow-md tw-border tw-border-gray-100 tw-sticky tw-top-4">
                    <div class="tw-flex tw-items-center tw-gap-2 tw-mb-6 tw-border-b tw-pb-4 tw-border-gray-100">
                        <div
                            class="tw-bg-navydark tw-text-white tw-w-8 tw-h-8 tw-flex tw-items-center tw-justify-center tw-rounded-full tw-text-sm">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <h3 class="tw-text-xl tw-font-bold tw-text-navydark tw-m-0">Catat Baru</h3>
                    </div>

                    {{-- UPDATE: Form Action & Method --}}
                    <form action="{{ route('pengeluaran.store') }}" method="POST" class="tw-space-y-4">
                        @csrf
                        <div>
                            <label class="tw-block tw-text-sm tw-font-semibold tw-text-gray-600 tw-mb-1">Tanggal
                                Transaksi</label>
                            {{-- UPDATE: name="tanggal" --}}
                            <input type="date" name="tanggal"
                                class="tw-w-full tw-bg-gray-50 tw-border tw-border-gray-200 tw-rounded-xl tw-px-4 tw-py-3 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-navydark tw-transition"
                                required>
                        </div>

                        <div>
                            <label class="tw-block tw-text-sm tw-font-semibold tw-text-gray-600 tw-mb-1">Keterangan
                                Barang/Jasa</label>
                            {{-- UPDATE: name="keterangan" --}}
                            <input type="text" name="keterangan" placeholder="Contoh: Sabun Lantai"
                                class="tw-w-full tw-bg-gray-50 tw-border tw-border-gray-200 tw-rounded-xl tw-px-4 tw-py-3 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-navydark tw-transition"
                                required>
                        </div>

                        <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                            <div>
                                <label
                                    class="tw-block tw-text-sm tw-font-semibold tw-text-gray-600 tw-mb-1">Jumlah</label>
                                {{-- UPDATE: name="jumlah", id="input-jumlah" --}}
                                <input type="number" name="jumlah" id="input-jumlah" placeholder="1"
                                    class="tw-w-full tw-bg-gray-50 tw-border tw-border-gray-200 tw-rounded-xl tw-px-4 tw-py-3 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-navydark tw-transition"
                                    required>
                            </div>
                            <div>
                                <label class="tw-block tw-text-sm tw-font-semibold tw-text-gray-600 tw-mb-1">Harga
                                    Satuan</label>
                                {{-- UPDATE: id="input-harga" (Tidak perlu name karena tidak disimpan ke DB, hanya bantu hitung) --}}
                                <input type="number" id="input-harga" placeholder="Rp..."
                                    class="tw-w-full tw-bg-gray-50 tw-border tw-border-gray-200 tw-rounded-xl tw-px-4 tw-py-3 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-navydark tw-transition">
                            </div>
                        </div>

                        <div>
                            <label class="tw-block tw-text-sm tw-font-semibold tw-text-gray-600 tw-mb-1">Subtotal
                                (Rp)</label>
                            <div class="tw-relative">
                                <span class="tw-absolute tw-left-4 tw-top-3 tw-text-gray-400 tw-font-bold">Rp</span>
                                {{-- UPDATE: name="sub_total", id="input-subtotal" --}}
                                <input type="number" name="sub_total" id="input-subtotal" placeholder="0"
                                    class="tw-w-full tw-bg-navydark/5 tw-border tw-border-navydark/20 tw-text-navydark tw-font-bold tw-rounded-xl tw-pl-12 tw-pr-4 tw-py-3 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-navydark tw-transition"
                                    required>
                            </div>
                        </div>

                        <button type="submit"
                            class="tw-w-full tw-bg-navydark tw-text-white tw-font-bold tw-py-4 tw-rounded-xl tw-shadow-lg hover:tw-bg-navysoft tw-transition tw-duration-300 tw-mt-2 tw-cursor-pointer">
                            <i class="fa-solid fa-save tw-mr-2"></i> Simpan Pengeluaran
                        </button>
                    </form>

                    <div class="tw-mt-6 tw-bg-blue-50 tw-p-4 tw-rounded-xl tw-flex tw-items-start tw-gap-3">
                        <i class="fa-solid fa-lightbulb tw-text-yellow-500 tw-mt-1"></i>
                        <p class="tw-text-xs tw-text-navydark tw-leading-relaxed tw-m-0">
                            <strong>Tips:</strong> Masukkan jumlah dan harga satuan agar subtotal terhitung otomatis.
                        </p>
                    </div>
                </div>
            </div>

            {{-- TABEL RIWAYAT --}}
            <div class="lg:tw-col-span-2">
                <div class="tw-bg-white tw-rounded-3xl tw-shadow-sm tw-border tw-border-gray-100 tw-overflow-hidden">
                    <div
                        class="tw-p-6 tw-flex tw-flex-col sm:tw-flex-row tw-justify-between tw-items-center tw-gap-4 tw-border-b tw-border-gray-100">
                        <h3 class="tw-text-xl tw-font-bold tw-text-navydark tw-m-0">Riwayat Belanja</h3>
                        <div class="tw-flex tw-gap-2">
                            {{-- Fitur Filter (Kosongkan dulu atau buat form GET) --}}
                            <div class="tw-relative">
                                <input type="text" placeholder="Cari barang..."
                                    class="tw-pl-9 tw-pr-4 tw-py-2 tw-border tw-border-gray-200 tw-rounded-full tw-text-sm focus:tw-outline-none focus:tw-border-navydark">
                                <i
                                    class="fa-solid fa-search tw-absolute tw-left-3 tw-top-2.5 tw-text-gray-400 tw-text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div class="tw-overflow-x-auto">
                        <table class="tw-w-full tw-text-left tw-border-collapse">
                            <thead>
                                <tr class="tw-bg-navydark tw-text-white tw-text-sm tw-uppercase tw-tracking-wider">
                                    <th class="tw-p-4 tw-font-semibold">No</th>
                                    <th class="tw-p-4 tw-font-semibold">Tanggal</th>
                                    <th class="tw-p-4 tw-font-semibold">Keterangan</th>
                                    <th class="tw-p-4 tw-font-semibold tw-text-center">Jumlah</th>
                                    <th class="tw-p-4 tw-font-semibold tw-text-right">Subtotal</th>
                                    <th class="tw-p-4 tw-font-semibold tw-text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="tw-text-sm tw-text-gray-700">
                                {{-- LOOPING DATA DARI DATABASE --}}
                                @forelse ($pengeluarans as $index => $item)
                                <tr
                                    class="tw-border-b tw-border-gray-50 hover:tw-bg-blue-50 tw-transition tw-duration-200">
                                    <td class="tw-p-4 tw-text-gray-400 tw-font-bold">
                                        {{ $pengeluarans->firstItem() + $index }}
                                    </td>
                                    <td class="tw-p-4">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                    </td>
                                    <td class="tw-p-4 tw-font-medium tw-text-navydark">
                                        <div class="tw-flex tw-items-center tw-gap-2">
                                            {{-- Variasi warna dot hiasan --}}
                                            @php $colors = ['tw-bg-pink-400', 'tw-bg-teal-400', 'tw-bg-blue-400',
                                            'tw-bg-purple-400']; @endphp
                                            <span
                                                class="tw-w-2 tw-h-2 tw-rounded-full {{ $colors[$loop->index % 4] }} tw-inline-block"></span>
                                            {{ $item->keterangan }}
                                        </div>
                                    </td>
                                    <td class="tw-p-4 tw-text-center">
                                        <span
                                            class="tw-bg-gray-100 tw-px-2 tw-py-1 tw-rounded tw-text-xs tw-font-bold tw-inline-block">
                                            {{ $item->jumlah }}
                                        </span>
                                    </td>
                                    <td class="tw-p-4 tw-text-right tw-font-bold">
                                        Rp {{ number_format($item->sub_total, 0, ',', '.') }}
                                    </td>
                                    <td class="tw-p-4 tw-text-center">
                                        {{-- TOMBOL DELETE --}}
                                        <form action="{{ route('pengeluaran.destroy', $item->id_pengeluaran) }}"
                                            method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="tw-text-gray-400 hover:tw-text-red-500 tw-transition tw-cursor-pointer tw-border-none tw-bg-transparent">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="tw-p-8 tw-text-center tw-text-gray-400 tw-italic">
                                        Belum ada data pengeluaran.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    <div class="tw-p-4">
                        {{ $pengeluarans->links('pagination::simple-tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT HITUNG OTOMATIS --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputJumlah = document.getElementById('input-jumlah');
    const inputHarga = document.getElementById('input-harga');
    const inputSubtotal = document.getElementById('input-subtotal');

    function hitungSubtotal() {
        const jumlah = parseFloat(inputJumlah.value) || 0;
        const harga = parseFloat(inputHarga.value) || 0;

        if (harga > 0) {
            inputSubtotal.value = jumlah * harga;
        }
    }

    inputJumlah.addEventListener('input', hitungSubtotal);
    inputHarga.addEventListener('input', hitungSubtotal);
});
</script>

@endsection