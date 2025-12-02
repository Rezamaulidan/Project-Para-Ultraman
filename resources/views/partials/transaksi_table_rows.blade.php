@forelse ($transaksis as $transaksi)
<tr>
    <td>TRKS-{{ str_pad($transaksi->id_booking, 3, '0', STR_PAD_LEFT) }}</td>
    <td>{{ $transaksi->penyewa ? $transaksi->penyewa->nama_penyewa : 'Unknown' }}</td>
    <td>{{ $transaksi->kamar ? $transaksi->kamar->no_kamar : '-' }}</td>
    <td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('M Y') }}</td>
    <td style="font-weight: bold;">Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}</td>
    <td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d M Y') }}</td>
    <td><span class="status-tag status-lunas">✅ Lunas</span></td>
    <td>
        <button class="action-btn">👁️</button>
        <button class="action-btn">⬇️</button>
    </td>
</tr>
@empty
<tr>
    <td colspan="8" style="text-align: center;">Data tidak ditemukan.</td>
</tr>
@endforelse
