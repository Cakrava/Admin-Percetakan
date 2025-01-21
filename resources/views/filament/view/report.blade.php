<style>
    .fl-table {
        border-radius: 5px;
        font-size: 12px;
        font-weight: normal;
        border: none;
        border-collapse: collapse;
        width: 100%;
        max-width: 100%;
        white-space: nowrap;
        background-color: white;
    }

    .fl-table td,
    .fl-table th {
        text-align: center;
        padding: 8px;
    }

    .fl-table td {
        border-right: 1px solid #f8f8f8;
        font-size: 12px;
    }

    .fl-table thead th {
        color: #ffffff;
        background: rgb(200, 115, 3);
    }


    .fl-table thead th:nth-child(odd) {
        color: #ffffff;
        background: #324960;
    }

    .fl-table tr:nth-child(even) {
        background: #F8F8F8;
    }
</style>
<div
    style="display: flex; flex-direction: row; gap: 5px; background-color: white; border: 1px solid #dedede; padding: 10px; border-radius: 10px; width: 100%; height: auto;">
    <!-- Kolom Kiri untuk Selector dan Option Button -->
    <div style=" width: 30%; padding: 10px; border-radius: 5px;">
        <h3 style="color: #333; margin-bottom: 10px;">Filter Data</h3>

        <!-- Selector untuk Memilih Jenis Data -->
        <div style="margin-bottom: 20px;">
            <label for="dataSelector" style="font-size: 14px; color: #555;">Pilih Data:</label>
            <select id="dataSelector"
                style="padding: 5px 10px; border-radius: 5px; border: 1px solid #ccc; background-color: #fff7ed; width: 100%;">
                <option value="transaction">Transaction</option>
                <option value="customer">Customer</option>
                <option value="service">Service</option>
                <option value="material">Material</option>
            </select>
        </div>

        <!-- Group Button untuk Status Transaksi (Hanya Muncul Saat Selector adalah Transaction) -->
        <div id="statusButtons" style="margin-bottom: 20px;">
            <h4 style="color: #555; margin-bottom: 10px;">Status Transaksi</h4>
            <div style="display: flex; gap: 5px;">
                <button onclick="filterData('All')" id="btnAll"
                    style="padding: 5px 10px; border-radius: 5px; border: 1px solid #ccc; background-color:  rgb(200, 115, 3); color: white; cursor: pointer;">All</button>
                <button onclick="filterData('Pending')" id="btnPending"
                    style="padding: 5px 10px; border-radius: 5px; border: 1px solid #ccc; background-color: #fff7ed; cursor: pointer;">Pending</button>
                <button onclick="filterData('Completed')" id="btnCompleted"
                    style="padding: 5px 10px; border-radius: 5px; border: 1px solid #ccc; background-color: #fff7ed; cursor: pointer;">Completed</button>
                <button onclick="filterData('Canceled')" id="btnCanceled"
                    style="padding: 5px 10px; border-radius: 5px; border: 1px solid #ccc; background-color: #fff7ed; cursor: pointer;">Canceled</button>
            </div>
        </div>

        <!-- Input Rentang Waktu (Hanya Muncul Saat Selector adalah Transaction) -->
        <div id="dateRange" style="margin-bottom: 20px;">
            <h4 style="color: #555; margin-bottom: 10px;">Rentang Waktu</h4>
            <div style="display: flex; gap: 5px;">
                <input type="date" id="startDate"
                    style="padding: 5px; border-radius: 5px; border: 1px solid #ccc; width: 48%;">
                <input type="date" id="endDate"
                    style="padding: 5px; border-radius: 5px; border: 1px solid #ccc; width: 48%;">
            </div>
            <button onclick="applyDateRange()"
                style="padding: 5px 10px; border-radius: 5px; border: none; background-color:  rgb(200, 115, 3); color: white; cursor: pointer; margin-top: 10px; width: 100%;">Terapkan</button>
        </div>

        <!-- Filter Kategori (Hanya Muncul Saat Selector adalah Service atau Material) -->
        <div id="categoryFilter" style="margin-bottom: 20px; display: none;">
            <h4 style="color: #555; margin-bottom: 10px;">Filter Kategori</h4>
            <select id="categorySelector"
                style="padding: 5px 10px; border-radius: 5px; border: 1px solid #ccc; width: 100%;">
                <option value="all">Semua Kategori</option>
            </select>
        </div>

        <button onclick="printTable()"
            style="padding: 5px 10px; border-radius: 5px; border: none; background-color:  rgb(200, 115, 3); color: white; cursor: pointer; margin-top: 10px; width: 100%;">Cetak</button>

        <!-- Garis Pemisah -->
        <hr style="border: 1px solid #dedede; margin-bottom: 20px;">

        <!-- Informasi Tambahan -->
        <div id="additionalInfo" style="font-size: 14px; color: #555;">
            <!-- Informasi akan ditampilkan di sini berdasarkan pilihan -->
        </div>
    </div>

    <!-- Kolom Kanan untuk Tabel -->
    <div style=" width: 70%; padding: 10px; border-radius: 5px;">
        <h3 style="color: #333; margin-bottom: 10px;">Tabel Data</h3>

        <!-- Tombol Cetak -->

        <!-- Container untuk Cetak -->
        <div id="cetak-data">
            <div id="tampil-kop">
                <div
                    style="flex-direction: row; display: flex; width: 100%; justify-content: space-between; align-items :center">
                    <img src="{{ asset('img/brandLogo.png') }}" style="width: 300px ; height: auto; margin-left : -5px">
                    <div>
                        <p style="font-weight: bold">Dicetak pada</p>
                        <p id="tanggal-sekarang">Tanggal sekarang</p>
                    </div>
                </div>
                <h1 style="font-size: 20px; font-weight: bold; margin-bottom: 5px; color: #030e1a;">
                    LAPORAN PERCETAKAN NECHACORP
                </h1>
                <h2 style="font-size: 15px;margin-bottom: 5px; color: #030e1a;font-weight : bold; ">
                    <span id="kop-title"></span> <span id="periode-text"></span>
                </h2>
                <p style="font-size: 14px;margin-top: 5px; color: #7f8c8d; border-bottom: 2px solid  rgb(200, 115, 3);">
                    Jl. Muhamad ali, No.34, Kecamatan Padang selatan, Kabupaten Manusia Langau
                </p>
                <p style="font-size: 14px;margin-top: 3px; color: #7f8c8d; border-top: 5px solid  rgb(200, 115, 3);">

                </p>
                <br>
                <h1 id="total-pendapatan"
                    style="font-size: 15px; font-weight: 400; margin-bottom: 5px; color: #030e1a;">
                    Total pendapatan : Rp.
                </h1>
            </div>


            <table id="dataTable" class="fl-table">
                <thead>
                    <tr style="background-color:  rgb(200, 115, 3); color: white;">
                        <!-- Kolom akan diisi berdasarkan jenis data yang dipilih -->
                    </tr>
                </thead>
                <tbody>
                    <!-- Data akan ditampilkan di sini -->
                </tbody>
            </table>
            <br>
            <br>
            <br>
            <br>
            <br>

            <div style="text-align: right;" id="tanda-tangan">
                <p style="font-size: 14px; margin-top: 5px; color: #7f8c8d; margin-bottom: 0;margin-right : 25px">
                    Padang
                </p>
                <br>
                <br>
                <br>
                <p style="font-size: 14px; margin-top: 5px; color: #7f8c8d; margin-bottom: 0;">
                    Agil SiLord.S.Km
                </p>
            </div>
        </div>
    </div>
</div> <!-- Style untuk Input Date -->
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@500&display=swap" rel="stylesheet">
<style>
    input[type="date"] {
        background-color: #0080ff;
        padding: 10px;
        font-family: "Roboto Mono", monospace;
        color: #ffffff;
        font-size: 14px;
        border: none;
        outline: none;
        border-radius: 5px;
    }

    ::-webkit-calendar-picker-indicator {
        background-color: #ffffff;
        padding: 5px;
        cursor: pointer;
        border-radius: 3px;
    }

    /* Sembunyikan kop secara default */
    #tampil-kop {
        display: none;
    }

    #tanda-tangan {
        display: none;
    }

    /* Tampilkan kop hanya saat mencetak */
    @media print {
        #tampil-kop {
            display: block;
        }

        #tanda-tangan {
            display: block;
        }

        /* Sembunyikan elemen lain yang tidak perlu dicetak */
        body * {
            visibility: hidden;
        }

        #cetak-data,
        #cetak-data * {
            visibility: visible;
        }

        #cetak-data {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
</style>

<script>
    let transactions = []; // Data transaksi dari API
    let customers = []; // Data customer dari API
    let services = []; // Data service dari API
    let materials = []; // Data material dari API
    let categories = []; // Data kategori dari API
    let activeFilter = 'All'; // Filter status aktif
    let activeCategoryFilter = 'all'; // Filter kategori aktif
    // Dapatkan elemen dengan ID 'tanggal-sekarang'
    const tanggalElement = document.getElementById('tanggal-sekarang');

    // Buat objek Date untuk tanggal saat ini
    const dateNow = new Date();

    // Daftar nama bulan
    const namaBulan = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    // Format tanggal menjadi string (contoh: "25 Oktober 2023")
    const tanggalString = `${dateNow.getDate()} ${namaBulan[dateNow.getMonth()]} ${dateNow.getFullYear()}`;

    // Isi textContent elemen dengan tanggal yang sudah diformat
    tanggalElement.textContent = tanggalString;
    // Fungsi untuk Memuat Data dari API
    async function fetchData() {
        const response = await fetch('http://127.0.0.1:8000/get-all-data');
        const data = await response.json();
        transactions = data.transactions;
        customers = data.customer;
        services = data.service;
        materials = data.materials;
        categories = data.category;

        // Set default selector ke Transaction
        document.getElementById('dataSelector').value = 'transaction';
        updateTable('transaction'); // Tampilkan data transaksi secara default
        populateCategoryFilter(); // Isi dropdown kategori
        updateButtonStyle('All'); // Set tombol All aktif secara default
    }

    // Fungsi untuk Mengisi Dropdown Kategori
    function populateCategoryFilter() {
        const categorySelector = document.getElementById('categorySelector');
        categorySelector.innerHTML = '<option value="all">Semua Kategori</option>';
        categories.forEach(category => {
            categorySelector.innerHTML += `<option value="${category.id}">${category.category_name}</option>`;
        });
    }

    // Fungsi untuk Memfilter Data Berdasarkan Status
    function filterData(status) {
        activeFilter = status;
        updateButtonStyle(status);
        updateTable('transaction');
    }

    // Fungsi untuk Menerapkan Rentang Waktu
    function applyDateRange() {
        updateTable('transaction');
    }

    // Fungsi untuk Memfilter Data Berdasarkan Kategori
    function filterByCategory() {
        const categorySelector = document.getElementById('categorySelector');
        activeCategoryFilter = categorySelector.value;
        updateTable(document.getElementById('dataSelector').value);
    }
    // Fungsi untuk Memperbarui Tabel
    function updateTable(type, data = null) {
        const tableBody = document.querySelector('#dataTable tbody');
        const tableHead = document.querySelector('#dataTable thead tr');
        const totalPendapatanElement = document.getElementById('total-pendapatan'); // Ambil elemen total pendapatan
        tableBody.innerHTML = ''; // Kosongkan tabel
        tableHead.innerHTML = ''; // Kosongkan header tabel

        let headers = [];
        let rows = [];
        let totalPendapatan = 0; // Variabel untuk menyimpan total pendapatan

        switch (type) {
            case 'transaction':
                headers = ['ID', 'Nama Customer', 'Total Harga', 'Metode Pembayaran', 'Status', 'Tanggal'];
                let filteredTransactions = transactions;

                // Filter berdasarkan status
                if (activeFilter !== 'All') {
                    filteredTransactions = filteredTransactions.filter(transaction => transaction.status === activeFilter);
                }

                // Filter berdasarkan rentang waktu
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;
                if (startDate && endDate) {
                    filteredTransactions = filteredTransactions.filter(transaction => {
                        const transactionDate = new Date(transaction.created_at).toISOString().split('T')[0];
                        return transactionDate >= startDate && transactionDate <= endDate;
                    });
                }

                // Hitung total pendapatan dari transaksi yang difilter
                totalPendapatan = filteredTransactions.reduce((sum, transaction) => sum + parseFloat(transaction.total_price), 0);

                rows = filteredTransactions.map(transaction => `
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd;">${transaction.id}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">${transaction.customer.name}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">Rp. ${transaction.total_price}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">${transaction.payment.method}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">${transaction.status}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">${transaction.created_at}</td>
                </tr>
            `);
                break;
            case 'customer':
                headers = ['ID', 'Nama', 'Email', 'Alamat', 'Nomor Telepon'];
                rows = customers.map(customer => `
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd;">${customer.id}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">${customer.name}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">${customer.email}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">${customer.address}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">${customer.number}</td>
                </tr>
            `);
                break;
            case 'service':
                headers = ['ID', 'Nama Service', 'Kategori', 'Harga', 'Tanggal Dibuat'];
                let filteredServices = services;

                // Filter berdasarkan kategori
                if (activeCategoryFilter !== 'all') {
                    filteredServices = filteredServices.filter(service => service.id_category === activeCategoryFilter);
                }

                rows = filteredServices.map(service => {
                    const category = categories.find(cat => cat.id == service.id_category);
                    return `
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd;">${service.id}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">${service.name_services}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">${category ? category.category_name : 'N/A'}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">Rp. ${service.price}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">${service.created_at}</td>
                    </tr>
                `;
                });
                break;
            case 'material':
                headers = ['ID', 'Nama Material', 'Kategori', 'Harga', 'Stok', 'Tanggal Dibuat'];
                let filteredMaterials = materials;

                // Filter berdasarkan kategori
                if (activeCategoryFilter !== 'all') {
                    filteredMaterials = filteredMaterials.filter(material => material.id_category === activeCategoryFilter);
                }

                rows = filteredMaterials.map(material => {
                    const category = categories.find(cat => cat.id == material.id_category);
                    return `
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd;">${material.id}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">${material.material_name}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">${category ? category.category_name : 'N/A'}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">Rp. ${material.material_price}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">${material.material_stock}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">${material.created_at}</td>
                    </tr>
                `;
                });
                break;
        }

        // Update header tabel
        tableHead.innerHTML = headers.map(header => `
        <th style="padding: 10px; border: 1px solid #ddd;">${header}</th>
    `).join('');

        // Update body tabel
        tableBody.innerHTML = rows.join('');

        // Update total pendapatan
        if (type === 'transaction' && rows.length > 0) {
            totalPendapatanElement.textContent = `Total pendapatan : Rp. ${totalPendapatan.toLocaleString()}`;
            totalPendapatanElement.style.display = 'block'; // Tampilkan elemen
        } else {
            totalPendapatanElement.style.display = 'none'; // Sembunyikan elemen jika tidak ada transaksi
        }

        // Update kop berdasarkan jenis data yang dipilih
        const kopTitle = document.getElementById('kop-title');
        const periodeText = document.getElementById('periode-text');
        switch (type) {
            case 'transaction':
                kopTitle.textContent = 'TRANSAKSI NECHACORP';
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;
                if (startDate && endDate) {
                    periodeText.textContent = `PERIODE ${startDate} - ${endDate}`;
                } else {
                    periodeText.textContent = '';
                }
                break;
            case 'customer':
                kopTitle.textContent = 'DATA CUSTOMER NECHACORP';
                periodeText.textContent = '';
                break;
            case 'service':
                kopTitle.textContent = 'DATA SERVICE NECHACORP';
                periodeText.textContent = '';
                break;
            case 'material':
                kopTitle.textContent = 'DATA MATERIAL NECHACORP';
                periodeText.textContent = '';
                break;
        }
    }
    // Fungsi untuk Mengupdate Style Tombol yang Aktif
    function updateButtonStyle(activeButton) {
        const buttons = ['btnAll', 'btnPending', 'btnCompleted', 'btnCanceled'];
        buttons.forEach(button => {
            const btnElement = document.getElementById(button);
            if (button === `btn${activeButton}`) {
                btnElement.style.backgroundColor = ' rgb(200, 115, 3)';
                btnElement.style.color = 'white';
            } else {
                btnElement.style.backgroundColor = '#f0f0f0';
                btnElement.style.color = 'black';
            }
        });
    }

    // Event Listener untuk Selector Data
    document.getElementById('dataSelector').addEventListener('change', function () {
        const selectedValue = this.value;
        const statusButtons = document.getElementById('statusButtons');
        const dateRange = document.getElementById('dateRange');
        const categoryFilter = document.getElementById('categoryFilter');

        if (selectedValue === 'transaction') {
            statusButtons.style.display = 'block';
            dateRange.style.display = 'block';
            categoryFilter.style.display = 'none';
        } else if (selectedValue === 'service' || selectedValue === 'material') {
            statusButtons.style.display = 'none';
            dateRange.style.display = 'none';
            categoryFilter.style.display = 'block';
        } else {
            statusButtons.style.display = 'none';
            dateRange.style.display = 'none';
            categoryFilter.style.display = 'none';
        }

        updateTable(selectedValue);
    });

    // Event Listener untuk Filter Kategori
    document.getElementById('categorySelector').addEventListener('change', function () {
        filterByCategory();
    });

    // Fungsi untuk Mencetak Tabel
    function printTable() {
        // Tampilkan kop sebelum mencetak
        document.getElementById('tampil-kop').style.display = 'block';

        // Cetak halaman
        window.print();

        // Sembunyikan kop setelah mencetak
        document.getElementById('tampil-kop').style.display = 'none';
    }

    // Memuat Data Saat Halaman Dimuat
    fetchData();
</script>