<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container" style="background-color: white; padding: 10px; margin-bottom: 50px; border: 1px solid #dedede; border-radius: 10px;">
    <div class="container">
        <div style="display: flex; align-items: center;">
            <h1>Preview Transaction</h1>
            <div class="image-loader" id="image-loader" style="margin-left: 10px;">
                <img src="{{ asset('img/loader.gif') }}" alt="Loader">
            </div>
        </div>
        <br>

        <table class="minimalist-table striped" id="transaction-table">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Service</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Quantity</th>
                    <th>Lebar</th>
                    <th>Panjang</th>
                    <th>Lampiran</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="floater" id="floater">
    <h1 class="total" id="view-totalharga">Total: Rp. 0</h1>
    {{-- <button class="proses-button">Proses Transaksi</button> --}}
    <button   id="btnProses" class="proses-button">Proses Transaksi</button>
</div>

<div id="total-harga"></div> <!-- Tambahkan elemen ini jika diperlukan -->

<script>
    const viewTotalHarga = document.getElementById('view-totalharga');
const loader = document.getElementById('image-loader');
const tableBody = document.querySelector('#transaction-table tbody');

loader.style.display = 'none';

let isFetching = false;

async function fetchTransactionData() {
    if (isFetching) return;
    isFetching = true;

    try {
        const response = await fetch('/tabel-preview-transaction');
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const transactions = await response.json();
        updateTable(transactions);

        const totalHarga = calculateTotalHarga(transactions);
        saveTotalHargaToJson(totalHarga);
    } catch (error) {
        console.error('Error fetching data:', error);
    } finally {
        isFetching = false;
    }
}

function calculateTotalHarga(transactions) {
    return transactions.reduce((total, transaction) => total + transaction.harga, 0);
}

function updateTable(transactions) {
    tableBody.innerHTML = '';

    let totalHarga = 0;
    transactions.forEach(transaction => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><img src="/storage/${transaction.image}" alt="Gambar" style="width:100px;height:100px;"></td>
            <td>${transaction.service.name_services}</td>
            <td>${transaction.jumlah}</td>
            <td>Rp. ${transaction.harga.toLocaleString('id-ID')}</td>
            <td>${transaction.quantity ? transaction.quantity + ' lembar' : '-'}</td>
            <td>${transaction.lebar ? transaction.lebar + ' cm' : '-'}</td>
            <td>${transaction.panjang ? transaction.panjang + ' cm' : '-'}</td>
            <td>${transaction.lampiran ? 'Terlampir' : 'Tidak ada'}</td>
            <td>
                <button class="action-button" data-id="${transaction.id}">Hapus</button>
            </td>
        `;
        tableBody.appendChild(row);
        totalHarga += transaction.harga;
    });

    viewTotalHarga.textContent = `Total: Rp ${totalHarga.toLocaleString()}`;
    // document.getElementById('total-harga').innerText = `Rp. ${totalHarga.toLocaleString('id-ID')}`; // Hapus jika tidak diperlukan
}

tableBody.addEventListener('click', (event) => {
    if (event.target.classList.contains('action-button')) {
        const itemId = event.target.getAttribute('data-id');
        loader.style.display = 'block';
        hapusTransaction(itemId);
    }
});

async function hapusTransaction(itemId) {
    try {
        const response = await fetch(`/hapus-transaction/${itemId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        fetchTransactionData();
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus data.');
    } finally {
        loader.style.display = 'none';
    }
}

async function saveTotalHargaToJson(totalHarga) {
    try {
        console.log(`Menyimpan total sejumlah ${totalHarga}`);
        const response = await fetch('/save-total-harga', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ totalHarga: totalHarga }),
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const result = await response.json();
        console.log(result.message);
    } catch (error) {
        console.error('Error:', error);
    }
}

setInterval(fetchTransactionData, 1000);
fetchTransactionData();
</script>