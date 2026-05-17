@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

    <div class="ph">
        <div>
            <h1>Dashboard</h1>
            <p>Selamat datang, {{ auth()->user()->nama_lengkap }} — {{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="dash-stats">
        <div class="card">
            <div class="card-body" style="display:flex;flex-direction:column;gap:4px">
                <span class="stat-lbl">Total Barang</span>
                <span class="stat-num">{{ $totalBarang }}</span>
                @if($stokHabis > 0)
                    <span class="b br" style="width:fit-content">{{ $stokHabis }} habis</span>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-body" style="display:flex;flex-direction:column;gap:4px">
                <span class="stat-lbl">Pelanggan</span>
                <span class="stat-num">{{ $totalPelanggan }}</span>
                <span style="font-size:11px;color:var(--sub)">akun terdaftar</span>
            </div>
        </div>
        <div class="card">
            <div class="card-body" style="display:flex;flex-direction:column;gap:4px">
                <span class="stat-lbl">Total Faktur</span>
                <span class="stat-num">{{ $totalFaktur }}</span>
                <span style="font-size:11px;color:var(--sub)">transaksi masuk</span>
            </div>
        </div>
        <div class="card">
            <div class="card-body" style="display:flex;flex-direction:column;gap:4px">
                <span class="stat-lbl">Total Pendapatan</span>
                <span class="stat-num" style="font-size:20px;color:var(--blue)">Rp
                    {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
                <span style="font-size:11px;color:var(--sub)">dari semua faktur</span>
            </div>
        </div>
        @if($stokMenipis > 0)
            <div class="card" style="border-color:var(--a-b)">
                <div class="card-body" style="display:flex;flex-direction:column;gap:4px">
                    <span class="stat-lbl" style="color:var(--a-tx)">Stok Menipis</span>
                    <span class="stat-num" style="color:var(--a-tx)">{{ $stokMenipis }}</span>
                    <span style="font-size:11px;color:var(--a-tx)">barang sisa ≤ 5</span>
                </div>
            </div>
        @endif
    </div>

    {{-- Grafik + Terlaris --}}
    <div class="dash-mid">
        {{-- Grafik faktur harian --}}
        <div class="card">
            <div class="card-head">
                <span>Faktur 7 Hari Terakhir</span>
            </div>
            <div class="card-body">
                <canvas id="chartFaktur" height="110"></canvas>
            </div>
        </div>

        {{-- Barang terlaris --}}
        <div class="card">
            <div class="card-head">
                <span>Barang Terlaris</span>
            </div>
            <div class="card-body" style="padding:0">
                @forelse($barangTerlaris as $item)
                    <div
                        style="display:flex;align-items:center;justify-content:space-between;padding:10px 16px;border-bottom:1px solid var(--bdr)">
                        <span
                            style="font-size:13px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:180px">{{ $item->nama_barang }}</span>
                        <span class="b bb">{{ $item->total_terjual }} terjual</span>
                    </div>
                @empty
                    <div style="padding:24px 16px;text-align:center;font-size:13px;color:var(--sub)">Belum ada transaksi</div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Faktur terbaru --}}
    <div class="card" style="margin-bottom:20px">
        <div class="card-head">
            <span>Faktur Terbaru</span>
        </div>
        <div class="tscroll">
            <table>
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Pelanggan</th>
                        <th class="hide-sm">Alamat</th>
                        <th class="tr">Total</th>
                        <th class="tr hide-sm">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fakturTerbaru as $f)
                        <tr>
                            <td style="font-weight:600;font-size:12px">{{ $f->nomor_invoice }}</td>
                            <td>{{ $f->user->nama_lengkap ?? '-' }}</td>
                            <td class="hide-sm" style="color:var(--sub)">{{ $f->alamat_pengiriman }}, {{ $f->kode_pos }}</td>
                            <td class="tr" style="font-weight:600;color:var(--blue)">Rp
                                {{ number_format($f->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="tr hide-sm" style="color:var(--sub);font-size:12px">{{ $f->created_at->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="tbl-empty">Belum ada faktur masuk</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const labels = @json($fakturHarian->pluck('tanggal')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M')));
        const dataFaktur = @json($fakturHarian->pluck('jumlah_faktur'));
        const dataPendapatan = @json($fakturHarian->pluck('total'));

        new Chart(document.getElementById('chartFaktur'), {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Jumlah Faktur',
                        data: dataFaktur,
                        backgroundColor: 'rgba(59,126,245,0.15)',
                        borderColor: '#3b7ef5',
                        borderWidth: 2,
                        borderRadius: 6,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Pendapatan',
                        data: dataPendapatan,
                        type: 'line',
                        borderColor: '#22c55e',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        pointRadius: 3,
                        tension: 0.4,
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: true, position: 'top', labels: { font: { size: 11 }, boxWidth: 12 } },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.datasetIndex === 1
                                ? ' Rp ' + Number(ctx.raw).toLocaleString('id-ID')
                                : ' ' + ctx.raw + ' faktur'
                        }
                    }
                },
                scales: {
                    y: { position: 'left', grid: { color: '#f1f5f9' }, ticks: { font: { size: 11 } } },
                    y1: {
                        position: 'right', grid: { drawOnChartArea: false }, ticks: {
                            font: { size: 11 },
                            callback: v => 'Rp ' + Number(v).toLocaleString('id-ID')
                        }
                    }
                }
            }
        });
    </script>

@endsection