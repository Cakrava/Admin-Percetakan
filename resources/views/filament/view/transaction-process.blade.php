<style>
    .detail-transactions li {
        background-color: #f9f9f9;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .detail-info {
        flex: 1;
    }

    .detail-image {
        margin-left: 20px;
        max-width: 100px;
        max-height: 100px;
        border-radius: 5px;
        object-fit: cover;
    }

    /* Gaya untuk container transaksi */
    .transaction-container {
        border: 1px solid #dedede;
        border-radius: 10px;
        background-color: white;
        padding: 30px;
        width: 100%;
        margin: 0 auto;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .status {
        font-weight: bold;
        color: rgb(0, 123, 94);
        font-size: 40px;
        text-align: right;
        margin-bottom: 20px;
    }

    .transaction-info {
        font-size: 18px;
        color: #333;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    .transaction-info p {
        margin: 0;
    }

    .detail-transactions {
        margin-top: 20px;
    }

    .detail-transactions h3 {
        margin-bottom: 10px;
        font-size: 20px;
        color: #333;
    }

    .detail-transactions ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .detail-transactions li {
        background-color: #f9f9f9;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .pay-button {
        color: white;
        background-color: rgb(200, 115, 3);
        padding: 10px 20px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
        text-align: center;
        width: 100%;
        font-size: 18px;
        margin-top: 20px;
    }

    .pay-button:hover {
        background-color: rgb(255, 190, 106);
    }

    .print-button {
        color: white;
        background-color: rgb(200, 115, 3);
        padding: 10px 20px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
        text-align: center;
        width: 100%;
        font-size: 18px;
        margin-top: 20px;
    }

    .print-button:hover {
        background-color: rgb(255, 190, 106);
    }

    /* Gaya untuk modal */
    .modal {
        display: none;
        /* Modal disembunyikan secara default */
        position: fixed;
        z-index: 1000;
        /* Pastikan z-index lebih tinggi dari elemen lain */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        /* Latar belakang semi-transparan */
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.3s ease;
        /* Animasi fadeIn */
    }

    .modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        width: 80%;
        max-width: 400px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        padding: 10px 0;
        border-bottom: 1px solid #ddd;
        font-size: 18px;
        font-weight: bold;
        color: #333;
    }

    .modal-body {
        padding: 20px 0;
        font-size: 16px;
        color: #555;
    }

    .modal-footer {
        padding: 10px 0;
        border-top: 1px solid #ddd;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .modal-footer button {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s ease;
    }

    .modal-footer button.confirm {
        background-color: rgb(180, 102, 0);
        color: white;
    }

    .modal-footer button.confirm:hover {
        background-color: rgb(255, 185, 93);
    }

    .modal-footer button.cancel {
        background-color: #6c757d;
        color: white;
    }

    .modal-footer button.cancel:hover {
        background-color: #5a6268;
    }

    /* Animasi fadeIn */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    /* Mencegah scroll pada body saat modal terbuka */
    body.modal-open {
        overflow: hidden;
    }
</style>

<div class="transaction-container">
    <!-- Tempat untuk menampilkan data transaksi -->
    <div class="status" id="status"></div>
    <div class="detail-transactions">
        <h3>Detail Layanan:</h3>
        <ul id="detail-transactions"></ul>
    </div>
    <div>
        <button class="pay-button" id="pay-button" style="display: none">Konfirmasi selesai</button>
        <button class="print-button" id="print-button" style="display: none">Cetak Faktur</button>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div class="modal" id="confirmation-modal">
    <div class="modal-content">
        <div class="modal-header">
            Konfirmasi Penyelesaian
        </div>
        <div class="modal-body">
            Apakah pemesanan sudah selesai?
        </div>
        <div class="modal-footer">
            <button class="confirm" id="confirm-button">Ya</button>
            <button class="cancel" id="cancel-button">Tidak</button>
        </div>
    </div>
</div>

<div id="loading" style="display: none">
    @include('filament.view.component_transaction.loader-transaction')
</div>

<!-- Script JavaScript -->
<script>
    // Ambil recordId dari data yang dikirim dari Filament
    const recordId = "{{ $recordId }}";

    // Fungsi untuk mengambil data dari API
    async function fetchTransactionData() {
        try {
            // Lakukan request ke API dengan recordId
            const response = await fetch(`/api/detailTransaksi/${recordId}`);
            if (!response.ok) {
                throw new Error('Gagal mengambil data');
            }
            const data = await response.json(); // Parsing data JSON
            displayTransactionData(data.transaction); // Tampilkan data
        } catch (error) {
            console.error('Error:', error);
        }
    }
    function displayTransactionData(transaction) {
        // Tampilkan status transaksi
        document.getElementById('status').textContent = 'On ' + transaction.status;

        // Sembunyikan tombol jika status adalah "Completed"
        const payButton = document.getElementById('pay-button');
        const printButton = document.getElementById('print-button');

        if (transaction.status.toLowerCase() === 'completed') {
            payButton.style.display = 'none';
            printButton.style.display = 'block'; // Tampilkan tombol cetak faktur
        } else {
            payButton.style.display = 'block';
            printButton.style.display = 'none'; // Sembunyikan tombol cetak faktur
        }

        // Tampilkan detail layanan
        const detailTransactions = document.getElementById('detail-transactions');
        detailTransactions.innerHTML = ''; // Kosongkan list sebelum diisi
        transaction.detail_transactions.forEach(detail => {
            const li = document.createElement('li');

            // Cek apakah panjang dan lebar memiliki value
            const showPanjangLebar = detail.panjang && detail.lebar;
            const showQuantity = detail.quantity;

            // Buat konten detail info
            const detailInfo = document.createElement('div');
            detailInfo.className = 'detail-info';
            detailInfo.innerHTML = `
            <strong>Layanan:</strong> ${detail.service_name} <br>
            <strong>Material:</strong> ${detail.material_name} <br>
            <strong>Lampiran:</strong> ${detail.lampiran ? `<a href="/storage/${detail.lampiran}" download style="color: orange"> Unduh</a>` : '-'} <br>
            ${showPanjangLebar ? `<strong>Panjang:</strong> ${detail.panjang} <br>` : ''}
            ${showPanjangLebar ? `<strong>Lebar:</strong> ${detail.lebar} <br>` : ''}
            ${showQuantity ? `<strong>Quantity:</strong> ${detail.quantity} <br>` : ''}
        `;

            // Tambahkan gambar jika ada
            const detailImage = document.createElement('img');
            if (detail.image) {
                detailImage.src = `/storage/${detail.image}`;
                detailImage.className = 'detail-image';
                detailImage.alt = 'Image';
            }

            // Gabungkan detail info dan gambar ke dalam li
            li.appendChild(detailInfo);
            if (detail.image) {
                li.appendChild(detailImage);
            }

            detailTransactions.appendChild(li);
        });
    }
    // Event listener untuk tombol "Cetak Faktur"
    document.getElementById('print-button').addEventListener('click', async function () {
        try {
            const loader = document.getElementById('loading')
            loader.style.display = 'block'; // Tampilkan loader
            // Kirim request ke backend untuk generate PDF
            const response = await fetch(`/send-faktur/${recordId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            });

            if (response.ok) {
                // Jika berhasil, unduh PDF
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `faktur-transaksi-${recordId}.pdf`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
                loader.style.display = 'none'
            } else {
                alert('Gagal menghasilkan PDF.');
            }
        } catch (error) {
            console.error('Error:', error);
            loader.style.display = 'none'
            alert('Terjadi kesalahan saat mencetak faktur.');
        }
    });

    // Fungsi untuk menampilkan modal konfirmasi
    function showConfirmationModal() {
        const modal = document.getElementById('confirmation-modal');
        modal.style.display = 'flex';
        document.body.classList.add('modal-open'); // Tambahkan class untuk mencegah scroll
    }

    // Fungsi untuk menyembunyikan modal konfirmasi
    function hideConfirmationModal() {
        const modal = document.getElementById('confirmation-modal');
        modal.style.display = 'none';
        document.body.classList.remove('modal-open'); // Hapus class untuk mengembalikan scroll
    }

    // Event listener untuk tombol "Bayar"
    document.getElementById('pay-button').addEventListener('click', showConfirmationModal);

    // Event listener untuk tombol "Ya" di modal
    document.getElementById('confirm-button').addEventListener('click', async function () {
        try {
            const loader = document.getElementById('loading')
            loader.style.display = 'block'; // Tampilkan loader
            // Kirim request ke backend untuk memproses pembayaran
            const response = await fetch(`/proses-selesai/${recordId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            });

            const data = await response.json();

            if (data.success) {
                // alert(data.message); // Tampilkan pesan sukses
                hideConfirmationModal(); // Sembunyikan modal
                fetchTransactionData(); // Perbarui data transaksi setelah pembayaran

                loader.style.display = 'none'; // Tampilkan loader
                history.back();
            } else {
                alert(data.message); // Tampilkan pesan error
                if (data.error) {
                    console.error('Error:', data.error); // Log error untuk debugging

                    loader.style.display = 'none'; // Tampilkan loader
                }
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses pembayaran.');
        }
    });

    // Event listener untuk tombol "Tidak" di modal
    document.getElementById('cancel-button').addEventListener('click', hideConfirmationModal);

    // Panggil fungsi fetchTransactionData saat halaman dimuat
    window.onload = fetchTransactionData;
</script>