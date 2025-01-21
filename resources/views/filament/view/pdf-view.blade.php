<!DOCTYPE html>
<html>

<head>
    <title>Faktur Transaksi #{{ $transaction->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .kop-surat {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .kop-surat img {
            width: 150px;
            height: auto;
        }

        .kop-surat .info {
            text-align: right;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid rgb(200, 115, 3);
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
        }
    </style>
</head>

<body>
    <div class="kop-surat">
        <!-- Gunakan public_path() untuk path absolut ke gambar -->
        <img src="{{ public_path('img/brandLogo.png') }}" alt="Logo">
        <div class="info">
            <!-- Informasi tambahan jika diperlukan -->
        </div>
    </div>

    <h1 style="font-size: 20px; font-weight: bold; margin-bottom: 5px; color: #030e1a;">
        Faktur Transaksi #{{ $transaction->id }}
    </h1>
    <h2 style="font-size: 15px; margin-bottom: 5px; color: #030e1a; font-weight: bold;">
        <span id="kop-title"></span> <span id="periode-text"></span>
    </h2>
    <p style="font-size: 14px; margin-top: 5px; color: #7f8c8d;">
        Jl. Muhamad ali, No.34, Kecamatan Padang selatan, Kabupaten Manusia Langau
    </p>
    <p style="font-size: 14px; margin-top: 3px; color: #7f8c8d; border-top: 5px solid rgb(200, 115, 3);"></p>
    <br>

    <p><strong>Pelanggan:</strong> {{ $transaction->customer->name }}</p>
    <p><strong>Nomor Telepon:</strong> {{ $transaction->customer->number }}</p>
    <p><strong>Metode Pembayaran:</strong> {{ $transaction->payment->method }}</p>
    <p><strong>Total Harga:</strong> Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
    <p><strong>Status:</strong> {{ $transaction->status }}</p>
    <p><strong>Tanggal Transaksi:</strong> {{ $transaction->created_at->format('Y-m-d H:i:s') }}</p>

    <h2>Detail Layanan</h2>
    <table style="font-size: 12px">
        <thead>
            <tr>
                <th>Layanan</th>
                <th>Material</th>
                <th>Jumlah</th>
                <th>Lembaran</th>
                <th>Panjang</th>
                <th>Lebar</th>
                <th>Harga</th>
                <th>Gambar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->detailTransactions as $detail)
                <tr>
                    <td>{{ $detail->service->name_services }}</td>
                    <td>{{ $detail->material ? $detail->material->material_name : 'N/A' }}</td>
                    <td>{{ $detail->jumlah }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ $detail->panjang }}</td>
                    <td>{{ $detail->lebar }}</td>
                    <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td>
                        @if ($detail->image)
                            <img src="{{ public_path('storage/' . $detail->image) }}" alt="Gambar" style="max-width: 100px;">
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Terima kasih telah menggunakan layanan kami.</p>
        <p>Jl. Muhamad ali, No.34, Kecamatan Padang selatan, Kabupaten Manusia Langau</p>
        <p>Telp: 081234567890 | Email: agilsilord@lord.com</p>
    </div>

    <script>
        // Menambahkan tanggal sekarang
        const tanggalSekarang = new Date().toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        document.getElementById('tanggal-sekarang').textContent = tanggalSekarang;
    </script>
</body>

</html>