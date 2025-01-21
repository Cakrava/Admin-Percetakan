    <style>


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
        max-width: 600px;
        max-height: 80vh; /* Batasi tinggi maksimal modal */
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Judul Modal */
    .modal-title {
        margin-bottom: 10px;
        position: sticky;
        top: 0;
        background: white;
        z-index: 1;
        padding: 10px 0;
        font-size: 24px;
        color: #333;
    }

    /* Input Pencarian */
    .modal-search {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 10px;
        transition: border-color 0.3s ease;
        position: sticky;
        top: 60px; /* Sesuaikan dengan tinggi judul */
        background: white;
        z-index: 1;
    }

    .modal-search:focus {
        border-color: #007bff;
        outline: none;
    }

    /* Daftar Customer */
    .customer-list {
        overflow-y: auto; /* Scroll di dalam daftar customer */
        max-height: calc(80vh - 150px); /* Sesuaikan tinggi dengan modal */
        margin-bottom: 10px;
    }

    /* Item Customer */
    .customer-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        border-bottom: 1px solid #ddd;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .customer-item:hover {
        background-color: #f9f9f9;
    }

    .customer-item h3 {
        margin: 0;
        font-size: 16px;
        color: #333;
    }

    .customer-item p {
        margin: 0;
        font-size: 14px;
        color: #666;
    }

    /* Footer Modal */
    .modal-footer {
        display: flex;
        justify-content: flex-end;
        padding: 10px 0;
        margin-top: 10px;
        position: sticky;
        bottom: 0;
        background: white;
        z-index: 1;
        border-top: 1px solid #ddd; /* Garis pemisah */
    }

    /* Tombol New Customer */
    #new-customer-btn {
        padding: 8px 16px;
        background-color: rgb(200, 115, 3);
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-right: 10px;
    }

    #new-customer-btn:hover {
        background-color: rgb(255, 177, 74);
    }

    /* Tombol Cancel */
    #cancel-customer-modal-btn {
        padding: 8px 16px;
        background-color: #6c757d;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #cancel-customer-modal-btn:hover {
        background-color: #5a6268;
    }

    /* Animasi fadeIn */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Class untuk mencegah scrolling pada body saat modal terbuka */
    body.modal-open {
        overflow: hidden;
    }

    </style>

    
    <div class="container" style="margin-bottom: 20px;">
        <div class="container" style="display: flex; justify-content: space-between; gap: 20px;">
            <!-- Gabungan Kolom Kiri dan Tengah -->
            <div style="border: 1px solid #dedede; border-radius: 10px; background-color: white; padding: 30px; display: flex; flex-direction: column; gap: 20px; flex: 2;">
                <!-- Tombol -->
                <h1>Data Customer</h1>
                <button id="open-customer-modal-btn"
                    style="align-self: flex-start; background-color: rgb(200, 115, 3); color: white; padding: 10px; 
                        border: none; border-radius: 5px; cursor: pointer; font-size: 16px;z-index : 10">
                    Cari
                </button>
                <input type="text" id="id" name="id" class="form-control" hidden>
                <input type="text" id="id-payment" name="id-payment" class="form-control" hidden >
    <!-- Modal -->
    <div id="customer-modal" class="modal">
    <div class="modal-content">
        <!-- Judul Modal (Tidak Ikut Terscroll) -->
        <h2 style="margin-bottom: 10px; position: sticky; top: 0; background: white; z-index: 1; padding: 10px 0;">
            Pilih Customer
        </h2>

        <!-- Input Pencarian (Tidak Ikut Terscroll) -->
        <input type="text" id="search-customer-input" class="search-input" placeholder="Cari customer..." 
                style="position: sticky; top: 60px; background: white; z-index: 1; margin-bottom: 10px; border-radius : 5px" />

        <!-- Daftar Customer (Bisa Di-scroll) -->
        <div id="customer-list-container" class="customer-list" style="overflow-y: auto; max-height: calc(80vh - 150px);">
            <!-- Daftar customer akan dimuat di sini -->
        </div>

        <!-- Footer Modal (Tidak Ikut Terscroll) -->
        <div class="modal-footer" style="position: sticky; bottom: 0; background: white; z-index: 1; padding: 10px 0;">
            <button id="new-customer-btn" type="button">New Customer</button>
            <button id="cancel-customer-modal-btn" type="button">Cancel</button>
        </div>
    </div>
    </div>
    <!-- Tombol Simpan (akan muncul setelah form diubah menjadi editable) -->

                <!-- Layout Kolom -->
                <div style="display: flex; flex-direction: row; gap: 20px;">
                    <!-- Kolom Kiri -->
                    <div style="flex: 1; display: flex; flex-direction: column; gap: 20px;">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" id="nama" name="nama" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" id="alamat" name="alamat" class="form-control">
                        </div>
                    </div>
            

                    <div style="flex: 1; display: flex; flex-direction: column; gap: 20px;">
                        <div class="form-group">
                            <label for="nomor">Nomor</label>
                            <input type="text" id="nomor" name="nomor" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control">
                        </div>
                    </div>
                </div>
                
                <div id="save-button-container" style="display: none; align-items: center; margin-top: 20px; gap: 10px;">
                <button id="save-customer-btn" type="button" 
                        style="border-radius: 5px; background-color: rgb(200, 115, 3); padding: 10px; color: white; width: 100px; border: none; cursor: pointer; transition: background-color 0.3s ease;"
                        onmouseover="this.style.backgroundColor='rgb(255, 182, 87)'" 
                        onmouseout="this.style.backgroundColor='rgb(200, 115, 3)'">
                    Simpan
                </button>
            
                <button id="cancel-customer-btn" type="button" 
                        style="border-radius: 5px; background-color: gray; padding: 10px; color: white; width: 100px; border: none; cursor: pointer; transition: background-color 0.3s ease;"
                        onmouseover="this.style.backgroundColor='#dedede'" 
                        onmouseout="this.style.backgroundColor='gray'">
                    Batal
                </button>
            
                <div id="save-proses">
                    <img src="{{ asset('img/loader.gif') }}" alt="Preview" style="width: auto; height: 50px;">
                </div>
            </div>
                
            </div>
            <!-- Kolom Kanan -->
            <div style="display: flex; flex-direction: column; flex: 1; gap: 20px;border: 1px solid #dedede; border-radius: 10px;background-color:orange; padding: 30px; color :#fff ">
                <div class="form-group">
                    <label for="tanggal_transaksi">Tanggal Transaksi</label>
                    <input type="text" id="tanggal_transaksi" readonly name="tanggal_transaksi" class="form-control" style="flex-grow: 1; color : orange">
                </div>
                <div class="form-group">
                    <label for="id_transaksi">ID Transaksi</label>
                    <input type="text" id="id_transaksi" name="id_transaksi" readonly class="form-control"  style="flex-grow: 1; color : orange">
                </div>
                <div class="form-group">
                    <label for="metode_pembayaran">Metode Pembayaran</label>
                    <select id="metode_pembayaran" name="metode_pembayaran" class="form-control" style="flex-grow: 1; color: orange">
                    </select>
                </div>
            </div>
        </div>
        
    </div>
<div id="loader-proses" style="display: none">

    @include('filament.view.component_transaction.loader-transaction')
</div>

{{-- <button id="btnProses">mencoba memproses</button> --}}


    <script>








document.addEventListener('DOMContentLoaded', function () {

const customerModal = document.getElementById('customer-modal');
const openCustomerModalBtn = document.getElementById('open-customer-modal-btn');
const cancelCustomerModalBtn = document.getElementById('cancel-customer-modal-btn');
const newCustomerBtn = document.getElementById('new-customer-btn');
const saveCustomerBtn = document.getElementById('save-customer-btn');
const cancelCustomerBtn = document.getElementById('cancel-customer-btn');
const saveButtonContainer = document.getElementById('save-button-container');
const searchCustomerInput = document.getElementById('search-customer-input');
const customerListContainer = document.getElementById('customer-list-container');
const loadingProsesImage = document.getElementById('save-proses');

// Elemen form input
const namaInput = document.getElementById('nama');
const id = document.getElementById('id');
const alamatInput = document.getElementById('alamat');
const nomorInput = document.getElementById('nomor');
const emailInput = document.getElementById('email');
const idTransaksi = document.getElementById('id_transaksi');
const idPayment = document.getElementById('id-payment');
const prosesBtn = document.getElementById('btnProses');
const loaderProses = document.getElementById('loader-proses');





let customerData = []; // Data customer dari API
let paymentData = []; // Data pembayaran dari API

// Set form input menjadi readonly secara default
namaInput.readOnly = true;
alamatInput.readOnly = true;
nomorInput.readOnly = true;
emailInput.readOnly = true;

// Fetch data dari API
fetch('/service-form-transaction')
    .then((response) => response.json())
    .then((data) => {
    paymentData = data.payment; // Ambil data pembayaran dari JSON
    customerData = data.customer; // Ambil data customer dari JSON
    console.log('Data Pembayaran:', paymentData); // Debug: Cek data pembayaran
    populatePaymentList(paymentData); // Tampilkan daftar pembayaran
    populateCustomerList(customerData); // Tampilkan daftar customer
    })
    .catch((error) => console.error('Error fetching data:', error));

// Event listener untuk membuka modal
openCustomerModalBtn.addEventListener('click', () => {
    customerModal.style.display = 'flex';
    document.body.classList.add('modal-open');
});

// Event listener untuk menutup modal
cancelCustomerModalBtn.addEventListener('click', () => {
    customerModal.style.display = 'none';
    document.body.classList.remove('modal-open');
});

// Event listener untuk tombol New Customer
newCustomerBtn.addEventListener('click', () => {
    namaInput.readOnly = false;
    alamatInput.readOnly = false;
    nomorInput.readOnly = false;
    emailInput.readOnly = false;
    namaInput.value = '';
    alamatInput.value = '';
    nomorInput.value = '';
    emailInput.value = '';
    saveButtonContainer.style.display = 'flex';
    openCustomerModalBtn.style.display = 'none';
    customerModal.style.display = 'none';
    document.body.classList.remove('modal-open');
});

// Event listener untuk tombol Simpan
saveCustomerBtn.addEventListener('click', async function (event) {
    event.preventDefault();
    loadingProsesImage.style.display = 'block';

    const name = namaInput.value.trim();
    const address = alamatInput.value.trim();
    const number = nomorInput.value.trim();
    const email = emailInput.value.trim();

    if (!name || !address || !number || !email) {
    alert('Mohon isi semua form yang wajib diisi');
    return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
    alert('Mohon masukkan email yang valid.');
    return;
    }

    const data = { name, address, number, email };

    try {
    const response = await fetch('/saveCustomerTransaction', {
        method: 'POST',
        headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify(data),
    });

    if (!response.ok) throw new Error('Network response was not ok');

    const result = await response.json();
    if (result.success) {
        saveButtonContainer.style.display = 'none';
        openCustomerModalBtn.style.display = 'block';
        loadingProsesImage.style.display = 'none';
        await getIDforSave();
        namaInput.readOnly = true;
        alamatInput.readOnly = true;
        nomorInput.readOnly = true;
        emailInput.readOnly = true;
        alert('Data customer berhasil disimpan.');
    } else {
        alert('Gagal menyimpan data: ' + result.message);
    }
    } catch (error) {
    console.error('Error:', error);
    alert('Terjadi kesalahan saat menyimpan data.');
    }
});

// Event listener untuk tombol Batal
cancelCustomerBtn.addEventListener('click', () => {
    namaInput.readOnly = true;
    alamatInput.readOnly = true;
    nomorInput.readOnly = true;
    emailInput.readOnly = true;
    saveButtonContainer.style.display = 'none';
    openCustomerModalBtn.style.display = 'block';
});

function populatePaymentList(paymentData) {
const paymentSelect = document.getElementById('metode_pembayaran');

const idPayment = document.getElementById('id-payment');
// Validasi elemen
if (!paymentSelect) {
console.error('Elemen <select> dengan id "metode_pembayaran" tidak ditemukan!');
return;
}

if (!idPayment) {
console.error('Elemen dengan id "idPayment" tidak ditemukan!');
return;
}

// Kosongkan opsi yang ada
paymentSelect.innerHTML = '';

// Tambahkan opsi default
const defaultOption = document.createElement('option');
defaultOption.value = '';
defaultOption.textContent = 'Pilih Pembayaran';
defaultOption.disabled = true;
defaultOption.selected = true;
paymentSelect.appendChild(defaultOption);

// Tambahkan opsi pembayaran dari data
if (paymentData && paymentData.length > 0) {
paymentData.forEach(payment => {
const option = document.createElement('option');
option.value = payment.id; // Simpan ID pembayaran sebagai value
option.textContent = payment.method; // Tampilkan metode pembayaran sebagai teks
paymentSelect.appendChild(option);
});

// Tambahkan event listener untuk menangani perubahan pilihan pembayaran
paymentSelect.addEventListener('change', function () {
const selectedOption = paymentSelect.options[paymentSelect.selectedIndex];
if (selectedOption) {
// Simpan nilai terpilih ke elemen idPayment
idPayment.value = selectedOption.value; // Simpan ID pembayaran
console.log(`Pembayaran terpilih: ${selectedOption.textContent}`); // Opsional: Log untuk debug
}
});
} else {
console.warn('Data pembayaran kosong atau tidak valid.');
}
}

// Fungsi untuk menampilkan daftar customer
function populateCustomerList(customers) {
    customerListContainer.innerHTML = '';
    customers.forEach(customer => {
    const customerItem = document.createElement('div');
    customerItem.className = 'customer-item';
    customerItem.innerHTML = `
        <h3>${customer.name}</h3>
        <p>${customer.email}</p>
    `;
    customerItem.addEventListener('click', () => selectCustomer(customer));
    customerListContainer.appendChild(customerItem);
    });
}

// Fungsi untuk memilih customer
function selectCustomer(customer) {
    id.value = customer.id;
    namaInput.value = customer.name;
    alamatInput.value = customer.address;
    nomorInput.value = customer.number;
    emailInput.value = customer.email;
    customerModal.style.display = 'none';
    document.body.classList.remove('modal-open');
}

// Fungsi untuk filter customer berdasarkan pencarian
function filterCustomers() {
    const searchTerm = searchCustomerInput.value.toLowerCase();
    const filteredCustomers = customerData.filter(customer => {
    const customerName = customer.name.toLowerCase();
    const customerAddress = customer.address.toLowerCase();
    const customerNumber = customer.number.toLowerCase();
    const customerEmail = customer.email.toLowerCase();
    return (
        customerName.includes(searchTerm) ||
        customerAddress.includes(searchTerm) ||
        customerNumber.includes(searchTerm) ||
        customerEmail.includes(searchTerm)
    );
    });
    populateCustomerList(filteredCustomers);
}

// Event listener untuk input pencarian
searchCustomerInput.addEventListener('input', filterCustomers);










async function getUniqueIDFromJSON() {
    try {
      const response = await fetch('/storage/temp/temp_unique_id.json')
      const data = await response.json()
      return data.uniqueID
    } catch (error) {
      console.error('Error fetching uniqueID:', error)
      return null
    }
  }

async function getTotalHargaFromJSON() {
    try {
      const response = await fetch('/storage/temp/temp_total_harga.json')
      const data = await response.json()
      return data.totalHarga
    } catch (error) {
      console.error('Error fetching totalHarga:', error)
      return null
    }
  }

  async function fetchTransactionData() {
    try {
        // Ambil data dari backend
        const response = await fetch('/tabel-preview-transaction');
        
        // Cek jika response tidak OK
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        // Ambil data transaksi dari response
        const tempTableTransaction = await response.json();

        // Validasi data
        if (!tempTableTransaction || tempTableTransaction.length === 0) {
            console.log('Data transaksi kosong.');
            return false; // Kembalikan false jika data kosong
        }

        // Kembalikan true jika data valid
        return true;
    } catch (error) {
        console.error('Error fetching data:', error);
        alert('Gagal mengambil data transaksi: ' + error.message);
        return false; // Kembalikan false jika terjadi error
    }
}

prosesBtn.addEventListener('click', async function (event) {
    event.preventDefault(); // Mencegah form submit default

    // Validasi input
    const isTransactionDataValid = await fetchTransactionData();
    if (!isTransactionDataValid) {
        alert('Service belum ditambahkan');
        return;
    }

    if (!namaInput.value) {
        alert('Pilih customer terlebih dahulu');
        return;
    }
    if (!idPayment.value) {
        alert('Metode pembayaran belum ditentukan');
        return;
    }
    if (!idTransaksi.value) {
        alert('ID Transaksi belum diisi');
        return;
    }

    // Tampilkan loading
    loadingProsesImage.style.display = 'block';

    const data = {
        id_transaction: idTransaksi.value, // Sesuaikan dengan nama kolom di backend
        id_customer: id.value,
        id_payment: idPayment.value,
        group_id: await getUniqueIDFromJSON(),
        total_price: await getTotalHargaFromJSON(), // Sesuaikan dengan nama kolom di backend
    };

    try {
        loaderProses.style.display='block';
        // Kirim data ke backend menggunakan fetch
        const response = await fetch('/simpanTransaksi', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify(data),
        });

        // Sembunyikan loading]

        // Cek jika response tidak OK
        if (!response.ok) {
            // Jika response tidak OK, ambil pesan error dari backend
            const errorResult = await response.json();
            throw new Error(errorResult.message || 'Terjadi kesalahan saat menyimpan data.');
            loaderProses.style.display='none';
        }

        // Ambil data response
        const result = await response.json();

        // Berikan feedback ke pengguna
        if (result.success) {
            // alert('Transaksi berhasil disimpan!');
            // Redirect ke halaman home atau halaman lain jika diperlukan
            history.back();
            loaderProses.style.display='none';
       
            // alert('Gagal menyimpan data: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        // Tampilkan pesan error ke pengguna
        // alert(error.message || 'Terjadi kesalahan saat menyimpan data.');
        // Sembunyikan loading jika terjadi error
        loaderProses.style.display='none';
    }
});



});


// Fungsi untuk mendapatkan ID customer setelah menyimpan
async function getIDforSave() {
const emailInput = document.getElementById('email');
const namaInput = document.getElementById('nama');
const id = document.getElementById('id');

try {
    const response = await fetch('/service-form-transaction');
    if (!response.ok) throw new Error('Network response was not ok');

    const data = await response.json();
    const customerData = data.customer;

    const nama = namaInput.value.trim();
    const email = emailInput.value.trim();

    const foundCustomer = customerData.find(customer => 
    customer.name === nama && customer.email === email
    );

    if (foundCustomer) {
    id.value = foundCustomer.id;
    console.log('ID Customer:', foundCustomer.id);
    return foundCustomer.id;
    } else {
    console.warn('Customer tidak ditemukan.');
    return null;
    }
} catch (error) {
    console.error('Error fetching data:', error);
    return null;
}
}



    </script>
    