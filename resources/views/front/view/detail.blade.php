@extends('front.layout.main')
@section('content')
  <main class="container mx-auto py-8">
   <div   class="flex">
    <div class="" style="width: 500px; height: 500px;">
     <img alt="Product Image"   style="object-fit: cover" src="{{ asset('storage/'.$services->image) }}" />
    </div>
    <div class="w-1/2 pl-8">
     <h1 class="text-2xl font-bold mb-2">
        {{ $services->name_services }}
     </h1>
     <p class="text-gray-600">
      Kategori {{ $services->category->category_name }}
    
     </p>
    
   <div class="mt-2">
    <h1 class="text-gray-600 font-bold mb-2">Deskripsi</h1>
    {!! $services->descriptions !!}
    <style>
        /* Styling untuk h2 (judul utama) */
        h2 {
            font-size: 2rem; /* Ukuran font lebih besar */
            font-weight: 600; /* Berat font tebal */
            color: #2c3e50; /* Warna gelap untuk teks */
            margin-bottom: 15px; /* Jarak bawah */
            border-bottom: 2px solid #3498db; /* Garis bawah biru untuk pemisah */
            padding-bottom: 10px; /* Ruang bawah */
        }
    
        /* Styling untuk h3 (subjudul) */
        h3 {
            font-size: 1.5rem; /* Ukuran font lebih kecil dari h2 */
            font-weight: 500; /* Berat font lebih ringan */
            color: #34495e; /* Warna agak lebih terang daripada h2 */
            margin-bottom: 10px; /* Jarak bawah */
        }
    
        /* Styling untuk p (paragraf) */
        p {
            font-size: 1rem; /* Ukuran font standar */
            line-height: 1.6; /* Jarak antar baris agar lebih mudah dibaca */
            color: #7f8c8d; /* Warna teks abu-abu */
            margin-bottom: 20px; /* Jarak bawah */
        }
    
        /* Styling untuk elemen <ul> dan <ol> */
            ol.list-decimal {
        padding-left: 20px; /* Memberikan jarak pada list */
        list-style-type: decimal; /* Menampilkan nomor urut */
        margin-bottom: 20px; /* Jarak bawah */
    }

    /* Styling untuk unordered list (ul) */
    ul.list-disc {
        padding-left: 20px; /* Memberikan jarak pada list */
        list-style-type: disc; /* Menampilkan dot point */
        margin-bottom: 20px; /* Jarak bawah */
    }

    /* Styling untuk item list (li) */
    li.list-item {
        font-size: 1rem; /* Ukuran font standar */
        color: #7f8c8d; /* Warna teks */
        margin-bottom: 10px; /* Jarak bawah tiap item */
    }
    
    
        /* Styling untuk link jika ada */
        a.text-blue-500 {
            color: #3498db; /* Warna biru */
            text-decoration: none; /* Menghilangkan underline */
        }
    
        a:hover {
            text-decoration: underline; /* Menambahkan underline saat hover */
        }
    </style>
       
   </div>
   
   </div>
   
   <div class="mt-8 shadow-lg p-4 rounded-lg border border-gray-300" style="width: 30%;">
    <div class="flex ">
    <h1  class="mt-4 text-lg font-bold text-red-600 mb-4" style="font-size: 35px; " >Rp.</h1>
    <h1 id="totalPrice" class="mt-4 text-lg font-bold text-red-600 mb-4" style="font-size: 35px; " ></h1>
</div>

    <input id="hidenPrice" name="hidenPrice" type="hidden" value="tes">
    <div class="mb-4">
        <span class="text-gray-600">Pilih Variant</span>
        <div class="flex space-x-2 mt-2">
            @foreach($variants as $variant)
                <button 
                    class="border border-gray-300 rounded px-4 py-2 variant-button" 
                    data-price="{{ $variant['total_price_services'] }}" 
                    data-variant="{{ $variant['variant'] }}">
                    {{ $variant['variant'] }}
                </button>
            @endforeach
        </div>
    </div>

 
    
<div class="mb-4">
      <span class="text-gray-600">
       Kuantitas
      </span>
      <div class="flex items-center mt-2">
       <button class="border border-gray-300 rounded px-4 py-2" id="minus">
        -
       </button>
       <input class="border border-gray-300 rounded px-4 py-2 mx-2 w-16 text-center" type="text" value="1" id="input" readonly name="quantity"/>
       <button class="border border-gray-300 rounded px-4 py-2" id="plus">
        +
       </button> 
       <span class="ml-4 text-gray-600">
       </span>
      </div>
     </div>
     <script>
        const minus = document.getElementById('minus');
        const plus = document.getElementById('plus');
        const input = document.getElementById('input');
        let value = parseInt(input.value);

        minus.addEventListener('click', () => {
            if (value > 1) {    
                value--;
                input.value = value;
            }
        });
        plus.addEventListener('click', () => {
            value++;
            input.value = value;
        });
     </script>
     <div class="flex space-x-4">
      <button class="bg-orange-500 text-white rounded px-6 py-2 active:bg-red-600  active:shadow-lg hover:bg-orange-800" style="width: 50%;" id="keranjang" onclick="addToCart()">
       Keranjang
      </button>
      <button class="bg-red-500 text-white rounded px-6 py-2 active:bg-red-600  active:shadow-lg hover:bg-red-800" style="width: 50%;">
       Beli
      </button>
     </div>
    </div>
   </div>
      @include('component.ServicesList')
  </main>

  <script>
function addToCart() {
    const input = document.getElementById('input');
    const variantButton = document.querySelector('.variant-button.border-orange-500'); // Pilih varian yang dipilih
    const variant = variantButton ? variantButton.getAttribute('data-variant') : null; // Ambil data-variant dari tombol yang dipilih

    if (!variant) {
        alert("Silakan pilih varian terlebih dahulu!");
        return;
    }

    fetch('/add-to-cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'  // Tambahkan token CSRF jika diperlukan
        },
        body: JSON.stringify({
            user_id: {{ $user->id }},
            service_id: {{ $services->id }},
            quantity: input.value,  // Sesuaikan dengan nama parameter yang diterima
            variant: variant,
            total_price: hidenPrice.value,
            price_item : totalPrice.textContent
        })
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Item berhasil ditambahkan ke keranjang!");
            localStorage.setItem('addCart', 'sukses');
        } else {
            localStorage.setItem('addCart', 'gagal');
        }
    })
    .catch(error => console.error('Error:', error));
}


  </script>

<script>
    const buttons = document.querySelectorAll('.variant-button');
const totalPriceElement = document.getElementById('totalPrice');
const hidenPrice = document.getElementById('hidenPrice');
const quantity = document.getElementById('input');
buttons.forEach(button => {
    button.addEventListener('click', function() {
        buttons.forEach(btn => btn.classList.remove('border-orange-500', 'bg-orange-100'));
        this.classList.add('border-orange-500', 'bg-orange-100'); // border orange dan background sedikit oranye
        const totalPrice = this.getAttribute('data-price');
        const variant = this.getAttribute('data-variant');
totalPriceElement.textContent = `${totalPrice}`;

const minus = document.getElementById('minus');
const plus = document.getElementById('plus');

var proses = totalPrice * quantity.value;
hidenPrice.value = `${proses}`;
minus.addEventListener('click', () => {
    var proses = totalPrice * quantity.value;
    hidenPrice.value = `${proses}`;
});
plus.addEventListener('click', () => {
    var proses = totalPrice * quantity.value;
    hidenPrice.value = `${proses}`;
});


    });
});
</script>

  
@endsection
