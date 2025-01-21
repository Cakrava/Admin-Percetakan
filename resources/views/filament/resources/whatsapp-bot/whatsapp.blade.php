@extends('filament::page')

@section('content')
<div
    style="display: flex; flex-direction: row; gap: 5px; background-color: white; border: 1px solid #dedede; padding: 10px; border-radius: 10px; width: 100%; height: auto;">
    <!-- Kolom Kiri: Teks Status, Detail Perangkat, dan Tombol Logout/Generate QR -->
    <div style="flex: 1; display: flex; flex-direction: column; gap: 10px;">
        <!-- Status Koneksi -->
        <div>
            <strong>Status Koneksi:</strong>
            <span id="status">Loading...</span>
        </div>

        <!-- Detail Perangkat -->
        <div>
            <strong>Detail Perangkat:</strong>
            <div id="device" style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
                <div><strong>Nama:</strong> <span id="device-name">Loading...</span></div>
                <div><strong>Nomor Telepon:</strong> <span id="device-phone">Loading...</span></div>
                <div><strong>Platform:</strong> <span id="device-platform">Loading...</span></div>
            </div>
        </div>

        <!-- Tombol Logout atau Generate QR -->
        <button id="actionButton"
            style="padding: 8px 12px; background-color: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer;"
            onclick="handleAction()" disabled>
            Scan to Connect
        </button>
    </div>

    <!-- Kolom Kanan: Gambar QR Code atau Logo -->
    <div style="display: flex; justify-content: center; align-items: center;">
        <img id="qrImage" src="#" alt="QR Code"
            style="max-width: 100%; height: auto; border-radius: 5px; display: none;">
        <img id="logoImage" src="{{ asset('img/logo_icon.png') }}" alt="Logo"
            style="max-width: 100%; height: auto; border-radius: 5px; display: none;">
    </div>
</div>

<script>
    // Fungsi untuk mengambil data dari API
    async function fetchData() {
        try {
            // Ambil status koneksi dan QR code dari API
            const statusResponse = await fetch('http://localhost:3000/status');
            const statusData = await statusResponse.json();

            const qrResponse = await fetch('http://localhost:3000/qr');
            const qrData = await qrResponse.json();

            // Update tampilan dengan data yang diterima
            document.getElementById('status').textContent = statusData.status || 'Disconnected';

            // Update detail perangkat
            const device = statusData.device || {};
            document.getElementById('device-name').textContent = device.pushname || 'Tidak tersedia';
            document.getElementById('device-phone').textContent = device.wid?.user || 'Tidak tersedia';
            document.getElementById('device-platform').textContent = device.platform || 'Tidak tersedia';

            // Update tombol berdasarkan status koneksi
            const actionButton = document.getElementById('actionButton');
            if (statusData.status === 'connected') {
                actionButton.textContent = 'Logout';
                actionButton.style.backgroundColor = '#dc3545'; // Warna merah untuk logout
                actionButton.disabled = false; // Aktifkan tombol

                // Sembunyikan QR code dan tampilkan logo
                document.getElementById('qrImage').style.display = 'none';
                document.getElementById('logoImage').style.display = 'block';
            } else {
                actionButton.textContent = 'Scan to Connect';
                actionButton.style.backgroundColor = '#6c757d'; // Warna abu-abu untuk non-aktif
                actionButton.disabled = true; // Non-aktifkan tombol

                // Tampilkan QR code dan sembunyikan logo
                document.getElementById('qrImage').style.display = 'block';
                document.getElementById('logoImage').style.display = 'none';
            }

            // Tampilkan QR code jika ada
            const qrImage = document.getElementById('qrImage');
            if (qrData.qr) {
                qrImage.src = qrData.qr; // Set src ke base64 string
                qrImage.alt = 'QR Code'; // Tambahkan alt text
            }
        } catch (error) {
            console.error('Error fetching data:', error);
            document.getElementById('status').textContent = 'Error loading status';
            document.getElementById('device-name').textContent = 'Error loading device details';
            document.getElementById('device-phone').textContent = 'Error loading device details';
            document.getElementById('device-platform').textContent = 'Error loading device details';
            document.getElementById('qrImage').style.display = 'none'; // Sembunyikan gambar jika terjadi error
        }
    }

    // Fungsi untuk menangani aksi tombol (Logout atau Generate QR)
    async function handleAction() {
        const statusResponse = await fetch('http://localhost:3000/status');
        const statusData = await statusResponse.json();

        if (statusData.status === 'connected') {
            // Jika terhubung, jalankan logout
            try {
                const response = await fetch('http://localhost:3000/logout');
                const data = await response.json();
                alert(data.message);
                fetchData(); // Refresh data setelah logout
            } catch (error) {
                console.error('Error:', error);
            }
        }
    }

    // Jalankan fetchData setiap 1 detik
    const pollingInterval = 1000; // 1 detik
    setInterval(fetchData, pollingInterval);

    // Jalankan fetchData saat halaman dimuat
    document.addEventListener('DOMContentLoaded', fetchData);
</script>
@endsection