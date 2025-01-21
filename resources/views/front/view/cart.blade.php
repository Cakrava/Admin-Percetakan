@extends('front.layout.main')

@section('content')

<html>
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>
<body class="bg-white p-4">
    <div class="border border-gray-300 p-4 rounded-lg shadow-lg" style="margin: 50px 100px;">
        @foreach ($cart as $item)
        <div class="flex items-center mb-4" id="cartItem-{{ $item->id }}" data-id="{{ $item->id }}">
            <input class="item-checkbox mr-4 w-4 h-4 shadow-sm animate-pulse" type="checkbox" data-price="{{ $item->total_price }}" />
            <img src="{{ asset('storage/'.$item->service->image) }}" alt="Image of a t-shirt with code printed on it" class="w-24 h-24 object-cover mr-4" width="100" height="100" />
            <div class="flex-1">
                <h2 class="text-lg font-semibold text-gray-600">{{ $item->service->name_services }}</h2>
                <p class="text-red-600 text-sm font-bold" data-price="{{ $item->total_price }}">Rp. {{ number_format($item->total_price, 0, ',', '.') }}</p>
                <button class="bg-orange-100 text-black px-4 py-2 mt-2 rounded-lg border border-orange-300">{{ $item->variant }}</button>
            </div>
            <div class="mb-4">
                <div class="flex items-center mt-2">
                    <button class="border border-gray-300 rounded px-4 py-2" id="minus-{{ $item->id }}">-</button>
                    <input type="text" class="border border-gray-300 rounded px-4 py-2 mx-2 w-16 text-center" value="{{ $item->qty }}" id="input-{{ $item->id }}" readonly name="quantity" />
                    <button class="border border-gray-300 rounded px-4 py-2" id="plus-{{ $item->id }}">+</button>
                </div>
            </div>
            <button class="bg-red-500 text-white p-4 ml-4 rounded-lg active:bg-red-700 hover:bg-red-600" id="trashButton-{{ $item->id }}" data-id="{{ $item->id }}">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        
        <div class="flex justify-between border border-gray-300 rounded-lg p-1 ml-8">
            <div class="w-1/2 max-w-lg rounded-lg">
                <div class="flex items-center">
                    <button id="uploadButton-{{ $item->id }}" class="bg-orange-300 text-white p-5 mr-4 rounded-lg active:bg-orange-500 hover:bg-orange-400">
                        <i class="fas fa-upload"></i>
                    </button>
                    <div>
                      
                        <div class="flex items-center">
                            <label class="block">
                                <span class="sr-only">Pilih File</span>
                                <input type="file" id="file-{{ $item->id }}" name="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:text-sm file:font-semibold file:bg-gray-800 file:text-white hover:file:bg-gray-700" accept=".jpg, .png, .pdf, .docx">
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            @if($item->lampiran)
            @php
                $fileExtension = pathinfo($item->lampiran, PATHINFO_EXTENSION);
            @endphp
        
            @if(in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif']))
                <!-- Gambar lampiran (png, jpg, jpeg, gif) -->
                <a href="{{ asset('storage/lampiran/'.$item->lampiran) }}" target="_blank">
                    <img src="{{ asset('storage/lampiran/'.$item->lampiran) }}" alt="Lampiran" class="object-cover mr-4 cursor-pointer" style="width: 50px; height: 50px;">
                </a>
            @elseif(in_array(strtolower($fileExtension), ['pdf', 'doc', 'docx']))
                <!-- Lampiran PDF atau Word, tampilkan gambar dokumen -->
                <a href="{{ asset('storage/lampiran/'.$item->lampiran) }}" target="_blank">
                    <img src="{{ asset('img/dokumen.jpg') }}" alt="Lampiran Dokumen" class="object-cover mr-4 cursor-pointer" style="width: 50px; height: 50px;">
                </a>
            @else
                <!-- Tampilkan placeholder lain jika file format lainnya -->
                <a href="{{ asset('storage/lampiran/'.$item->lampiran) }}" target="_blank" class="text-blue-500 underline">
                    Lihat Lampiran
                </a>
            @endif
        @endif
        
        </div>
        <br>
        <br>
        @endforeach
    </div>

    <div class="flex justify-between w-full bg-gray-50 p-4 mb-10 items-center">
        <p class="text-xl font-bold">Total Harga: <span id="total-price">Rp. 0</span></p>
        <button class="bg-red-500 text-white p-4 rounded-lg">Checkout</button>
    </div>

    <script>
document.querySelectorAll('[id^="minus-"]').forEach(button => {
    button.addEventListener('click', function() {
        const itemId = this.id.split('-')[1];
        const input = document.getElementById(`input-${itemId}`);
        let value = parseInt(input.value);
        if (value > 1) {
            value--;
            input.value = value;
            updateTotalPrice(itemId, value);  // Kirim nilai qty yang baru
        }
    });
});

document.querySelectorAll('[id^="plus-"]').forEach(button => {
    button.addEventListener('click', function() {
        const itemId = this.id.split('-')[1];
        const input = document.getElementById(`input-${itemId}`);
        let value = parseInt(input.value);
        value++;
        input.value = value;
        updateTotalPrice(itemId, value);  // Kirim nilai qty yang baru
    });
});

function updateTotalPrice(itemId, qty) {
    fetch('/update-cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'  // Tambahkan token CSRF jika diperlukan
        },
        body: JSON.stringify({
            id: itemId,
            qty: qty  // Kirim qty yang baru
        })
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            // Memperbarui total harga tanpa perlu reload halaman
            const totalPriceElement = document.getElementById('total-price');
            totalPriceElement.textContent = `Rp. ${data.total_price}`;  // Sesuaikan dengan respons yang diterima
        } else {
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
      window.location.reload();
    });
}


document.querySelectorAll('[id^="uploadButton-"]').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.id.split('-')[1];
                const fileInput = document.getElementById(`file-${itemId}`);
                if (fileInput.files.length > 0) {
                    const formData = new FormData();
                    formData.append('file', fileInput.files[0]);
                    formData.append('id', itemId);

                    fetch('{{ route("uploadFile") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message === 'File uploaded successfully') {
                            window.location.reload();
                        } else {
                            alert('Terjadi kesalahan: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengupload file');
                    });
                } else {
                    alert('Pilih file terlebih dahulu');
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const trashButtons = document.querySelectorAll('[id^="trashButton-"]');
            trashButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const itemId = this.getAttribute('data-id');
                    if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                        fetch(`/cart/${itemId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            window.location.reload();
                            if (data.success) {
                                document.getElementById(`cartItem-${itemId}`).remove();
                            } else {
                               
                                window.location.reload();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            window.location.reload();
                        });
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
    // Array untuk menyimpan ID dari checkbox yang dipilih
    let selectedIds = [];

    // Fungsi untuk menghitung total harga dan menyimpan id berdasarkan checkbox yang dicentang
    function updateTotalPrice() {
        let totalPrice = 0;
        selectedIds = [];  // Reset array selectedIds

        // Looping setiap checkbox yang ada di dalam cart
        document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
            // Ambil harga dan id dari data-price dan data-id yang ada pada checkbox
            const id = parseInt(checkbox.getAttribute('data-id'));
            const price = parseInt(checkbox.getAttribute('data-price'));
            

            // Menambahkan harga sesuai jumlah barang (qty)
            totalPrice += price ;

            // Menyimpan id item yang dicentang
            selectedIds.push(id);
        });

        // Menampilkan total harga di elemen dengan id total-price
        const totalPriceElement = document.getElementById('total-price');
        totalPriceElement.textContent = `Rp. ${totalPrice.toLocaleString()}`;

        // Untuk debug: Menampilkan array selectedIds di console
        console.log('Selected IDs:', selectedIds);
    }

    // Menambahkan event listener untuk setiap checkbox
    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateTotalPrice);
    });

    // Menghitung total harga saat halaman pertama kali dimuat
    updateTotalPrice();
});


    </script>

@include('component.ServicesList')

</body>
</html>

@endsection
