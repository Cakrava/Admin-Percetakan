<style>
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
        color: red;
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

    /* Gaya untuk tombol Back */
    .back-button {
        color: white;
        background-color: #6c757d;
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
    .back-button:hover {
        background-color: #5a6268;
    }

    /* Gaya untuk modal */
    .modal {
        display: none; /* Modal disembunyikan secara default */
        position: fixed;
        z-index: 1000; /* Pastikan z-index lebih tinggi dari elemen lain */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Latar belakang semi-transparan */
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.3s ease; /* Animasi fadeIn */
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
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Mencegah scroll pada body saat modal terbuka */
    body.modal-open {
        overflow: hidden;
    }
</style>

<div class="transaction-container">
    <!-- Tempat untuk menampilkan data transaksi -->
    <div class="status" id="status"></div>
    <div class="transaction-info">
        <p><strong>Nomor Transaksi:</strong> <span id="transaction-id"></span></p>
        <p><strong>Nama Customer:</strong> <span id="customer-name"></span></p>
        <p><strong>Nomor Customer:</strong> <span id="customer-number"></span></p>
        <p><strong>Total Transaksi:</strong> <span id="total-price"></span></p>
        <p><strong>Metode Pembayaran:</strong> <span id="payment-method"></span></p>
        <p><strong>Tanggal Transaksi:</strong> <span id="created-at"></span></p>
    </div>
    <div class="detail-transactions">
        <h3>Detail Layanan:</h3>
        <ul id="detail-transactions"></ul>
    </div>
    <div>
        <button class="pay-button" id="pay-button" style="display: none">Bayar</button>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div class="modal" id="confirmation-modal">
    <div class="modal-content">
        <div class="modal-header">
            Konfirmasi Pembayaran
        </div>
        <div class="modal-body">
            Apakah Anda yakin ingin melanjutkan pembayaran?
        </div>
        <div class="modal-footer">
            <button class="confirm" id="confirm-button">Ya</button>
            <button class="cancel" id="cancel-button">Tidak</button>
        </div>
    </div>
</div>

<!-- Tambahkan script Midtrans Snap JS -->
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

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

            // Jika payment method adalah "Payment Gateway" dan snap_token tersedia, tampilkan tombol bayar Midtrans
            if (data.transaction.payment.method.toLowerCase() === 'payment gateway' && data.transaction.snap_token) {
                document.getElementById('pay-button').style.display = 'block';
                document.getElementById('pay-button').onclick = function() {
                    // Munculkan popup pembayaran Midtrans
                    snap.pay(data.transaction.snap_token, {
                        onSuccess: function(result) {
                            console.log('Pembayaran berhasil!', result);
                            // Kirim request ke backend untuk memproses pembayaran
                            processPayment(recordId);
                          
                        },
                        onPending: function(result) {
                            console.log('Pembayaran pending!', result);
                            alert('Pembayaran pending! Silakan selesaikan pembayaran.');
                        },
                        onError: function(result) {
                            console.log('Pembayaran gagal!', result);
                            alert('Pembayaran gagal! Silakan coba lagi.');
                        },
                        onClose: function() {
                            console.log('Popup ditutup tanpa menyelesaikan pembayaran');
                            alert('Popup ditutup tanpa menyelesaikan pembayaran.');
                        }
                    });
                };
            }
            // Jika payment method adalah "Cash", tampilkan tombol bayar dengan modal konfirmasi
            else if (data.transaction.payment.method.toLowerCase() === 'cash') {
                document.getElementById('pay-button').style.display = 'block';
                document.getElementById('pay-button').onclick = showConfirmationModal;
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    // Fungsi untuk memproses pembayaran
    async function processPayment(recordId) {
        try {
            // Kirim request ke backend untuk memproses pembayaran
            const response = await fetch(`/proses-bayar/${recordId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            });

            const data = await response.json();

            if (data.success) {
                fetchTransactionData(); // Perbarui data transaksi setelah pembayaran
                window.location.href = '/auth/transactions'; // Ganti dengan URL tujuan Anda
            } else {
                alert(data.message); // Tampilkan pesan error
                if (data.error) {
                    console.error('Error:', data.error); // Log error untuk debugging
                }
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses pembayaran.');
        }
    }

    // Fungsi untuk menampilkan data transaksi
    function displayTransactionData(transaction) {
        document.getElementById('status').textContent = transaction.status;
        document.getElementById('transaction-id').textContent = transaction.id;
        document.getElementById('customer-name').textContent = transaction.customer.name;
        document.getElementById('customer-number').textContent = transaction.customer.number;
        document.getElementById('total-price').textContent = `Rp ${transaction.total_price.toLocaleString()}`;
        document.getElementById('payment-method').textContent = transaction.payment.method;
        document.getElementById('created-at').textContent = transaction.created_at;

        // Tampilkan detail layanan
        const detailTransactions = document.getElementById('detail-transactions');
        detailTransactions.innerHTML = ''; // Kosongkan list sebelum diisi
        transaction.detail_transactions.forEach(detail => {
            const li = document.createElement('li');
            li.innerHTML = `
                <strong>Layanan:</strong> ${detail.service_name} <br>
                <strong>Material:</strong> ${detail.material_name}
            `;
            detailTransactions.appendChild(li);
        });

        // Tombol bayar
        const payButton = document.getElementById('pay-button');

        // Jika status adalah "Proses", ubah tombol bayar menjadi tombol Back
        if (transaction.status.toLowerCase() === 'proses') {
            payButton.textContent = 'Back';
            payButton.className = 'back-button'; // Ganti class untuk styling tombol Back
            payButton.onclick = function() {
                window.location.href = '/auth/transactions'; // Ganti dengan URL tujuan Anda
            };
        }
        // Jika status adalah "Canceled", sembunyikan tombol bayar
        else if (transaction.status.toLowerCase() === 'canceled') {
            payButton.style.display = 'none';
        }
        // Jika status bukan "Proses" atau "Canceled", tampilkan tombol bayar
        else {
            payButton.style.display = 'block';
        }
    }

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

    // Event listener untuk tombol "Ya" di modal
    document.getElementById('confirm-button').addEventListener('click', async function () {
        await processPayment(recordId); // Proses pembayaran
        hideConfirmationModal(); // Sembunyikan modal
    });

    // Event listener untuk tombol "Tidak" di modal
    document.getElementById('cancel-button').addEventListener('click', hideConfirmationModal);

    // Panggil fungsi fetchTransactionData saat halaman dimuat
    window.onload = fetchTransactionData;
</script>