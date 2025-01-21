<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nechacorp - Percetakan online</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    html {
      scroll-behavior: smooth;
    }

    body {
      background-color: #070F2B;
      color: #E0E0E0;
      font-family: 'Roboto', sans-serif;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .fade-in {
      animation: fadeIn 1s ease-in-out;
    }

    .banner-slider img {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: opacity 1s ease-in-out;
    }

    .card {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      backdrop-filter: blur(10px);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-10px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .dropdown-menu {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .footer {
      background: rgba(0, 0, 0, 0.2);
      border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="bg-[#070F2B] shadow-lg fixed w-full top-0 z-50">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">
      <div class="flex items-center">
        <div class="bg-white w-auto rounded-lg">
          <img alt="Nechacorp logo" class="h-10" src="{{ asset('img/logo.png') }}" width="auto">
        </div>
      </div>

      <div class="flex items-center space-x-4">
        <nav class="flex space-x-4">
          <a class="text-gray-300 hover:text-white transition-colors duration-300" href="{{ route('front.index') }}">Beranda</a>
          @foreach($categories as $category)
            <div class="relative group">
              <a class="text-gray-300 hover:text-white transition-colors duration-300" href="#">
                {{ $category->category_name }}
              </a>
              <div class="dropdown-menu absolute left-0 hidden group-hover:block mt-2 w-48 rounded-md shadow-lg bg-gray-800 space-y-2 p-2 z-50">
                @foreach($category->services as $service)
                  <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition ease-in-out duration-200">
                    {{ $service->name_services }}
                  </a>
                @endforeach
              </div>
            </div>
          @endforeach
        </nav>

        <div class="relative">
          <input class="border border-gray-600 rounded-full py-2 px-4 pl-10 w-64 bg-transparent text-gray-300 placeholder-gray-500 focus:outline-none focus:border-white" placeholder="Cari di Nechacorp" type="text">
          <i class="fas fa-search absolute left-3 top-3 text-gray-500"></i>
        </div>

        <a class="text-gray-300 hover:text-white transition-colors duration-300" href="#">
          <i class="fas fa-heart"></i>
        </a>
        <a class="text-gray-300 hover:text-white transition-colors duration-300" href="#">
          <i class="fas fa-bell"></i>
        </a>
        <a class="text-gray-300 hover:text-white relative transition-colors duration-300" href="{{ route('viewCart') }}">
          <i class="fas fa-shopping-cart"></i>
          <span class="badge absolute -top-2 -right-2 text-xs bg-red-600 text-white rounded-full px-2 py-1" id="cart-badge" style="display: none;">0</span>
        </a>

        <ul class="navbar">
          <li class="relative">
            <a href="{{ session('isLogin') == 'yes' ? '#' : route('login') }}" class="text-gray-300 hover:text-white flex items-center space-x-2 transition-colors duration-300" id="dropdown-toggle">
              <i class="{{ session('isLogin') == 'yes' ? 'fas fa-user' : 'fas fa-sign-in-alt' }}"></i>
              <span>{{ session('isLogin') == 'yes' ? ($user->name ?? 'Masuk') : 'Masuk' }}</span>
            </a>
            @if(session('isLogin') == 'yes')
            <ul class="dropdown-menu absolute left-0 hidden mt-2 w-48 rounded-md shadow-lg space-y-2 p-2 z-50">
              @if(session('role') == 'admin')
              <li>
                <a href="{{ route('filament.admin.pages.dashboard') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition ease-in-out duration-200">Open Panel</a>
              </li>
              @endif
              <li>
                <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition ease-in-out duration-200">Profile</a>
              </li>
              <li>
                <a href="#" id="logoutBtn" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition ease-in-out duration-200">Logout</a>
                <div id="logoutModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                  <div class="bg-[#070F2B] rounded-lg w-96 p-6 border border-gray-700">
                    <h2 class="text-xl font-semibold mb-4 text-white">Apakah Anda yakin ingin logout?</h2>
                    <div class="flex justify-end space-x-4">
                      <button id="cancelBtn" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors duration-300">Batal</button>
                      <a href="{{ route('logout') }}" id="confirmLogoutBtn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-300">Logout</a>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
            @endif
          </li>
        </ul>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="container mx-auto px-4 py-8 mt-20">
    @yield('content') <!-- Yield untuk konten dinamis -->
  </main>

  <!-- Footer -->
  <footer class="footer py-8">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div>
          <h3 class="text-white font-bold mb-4">Informasi</h3>
          <ul class="text-gray-300 space-y-2">
            <li><a href="#" class="hover:text-white transition-colors duration-300">Tentang Nechacorp</a></li>
            <li><a href="#" class="hover:text-white transition-colors duration-300">CSR</a></li>
            <li><a href="#" class="hover:text-white transition-colors duration-300">Hubungi Kami</a></li>
            <li><a href="#" class="hover:text-white transition-colors duration-300">Lokasi Toko</a></li>
          </ul>
        </div>
        <div>
          <h3 class="text-white font-bold mb-4">Bantuan & Dukungan</h3>
          <ul class="text-gray-300 space-y-2">
            <li><a href="#" class="hover:text-white transition-colors duration-300">Lacak Pesanan Saya</a></li>
            <li><a href="#" class="hover:text-white transition-colors duration-300">Pengembalian Barang & Dana</a></li>
            <li><a href="#" class="hover:text-white transition-colors duration-300">Bagaimana Cara Belanja</a></li>
            <li><a href="#" class="hover:text-white transition-colors duration-300">FAQ</a></li>
          </ul>
        </div>
        <div>
          <h3 class="text-white font-bold mb-4">Temui Kami</h3>
          <div class="flex space-x-4">
            <a href="#" class="text-gray-300 hover:text-white transition-colors duration-300"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-gray-300 hover:text-white transition-colors duration-300"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-gray-300 hover:text-white transition-colors duration-300"><i class="fab fa-instagram"></i></a>
            <a href="#" class="text-gray-300 hover:text-white transition-colors duration-300"><i class="fab fa-youtube"></i></a>
          </div>
        </div>
        <div>
          <h3 class="text-white font-bold mb-4">Langganan Newsletter</h3>
          <div class="flex">
            <input class="border border-gray-600 rounded-l-full py-2 px-4 w-full bg-transparent text-gray-300 placeholder-gray-500 focus:outline-none focus:border-white" placeholder="Masukkan alamat email" type="email">
            <button class="bg-red-600 text-white rounded-r-full py-2 px-4 hover:bg-red-700 transition-colors duration-300">Langganan</button>
          </div>
        </div>
      </div>
      <div class="mt-8 flex items-center justify-between border-t border-gray-700 pt-6">
        <div class="flex space-x-4">
          <img alt="Payment Method" class="h-6" src="https://storage.googleapis.com/a1aa/image/CSN1tox4WR4YJZLf6D8SPMWrQQLji96t0zhVpoDXVdqp5o4JA.jpg">
          <img alt="Payment Method" class="h-6" src="https://storage.googleapis.com/a1aa/image/CSN1tox4WR4YJZLf6D8SPMWrQQLji96t0zhVpoDXVdqp5o4JA.jpg">
          <img alt="Payment Method" class="h-6" src="https://storage.googleapis.com/a1aa/image/CSN1tox4WR4YJZLf6D8SPMWrQQLji96t0zhVpoDXVdqp5o4JA.jpg">
          <img alt="Payment Method" class="h-6" src="https://storage.googleapis.com/a1aa/image/CSN1tox4WR4YJZLf6D8SPMWrQQLji96t0zhVpoDXVdqp5o4JA.jpg">
        </div>
        <div class="text-gray-300 text-sm">
          <a href="#" class="hover:text-white transition-colors duration-300">nechacorp.com</a> |
          <a href="#" class="hover:text-white transition-colors duration-300">Syarat Ketentuan</a> |
          <a href="#" class="hover:text-white transition-colors duration-300">Kebijakan Privasi</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script>
    // Banner Carousel
    const banners = @json($banner);
    const bannerImage = document.getElementById('bannerImage');
    const nextBannerImage = document.getElementById('nextBannerImage');
    const dots = document.querySelectorAll('#dotsContainer button');
    let currentIndex = 0;

    function goToImage(index) {
      currentIndex = index;
      bannerImage.src = `{{ asset('storage/') }}/${banners[currentIndex].image}`;
      nextBannerImage.src = `{{ asset('storage/') }}/${banners[(currentIndex + 1) % banners.length].image}`;
      updateDots();
    }

    function changeImage() {
      const nextIndex = (currentIndex + 1) % banners.length;
      nextBannerImage.src = `{{ asset('storage/') }}/${banners[nextIndex].image}`;
      bannerImage.style.opacity = 0;

      setTimeout(() => {
        currentIndex = nextIndex;
        bannerImage.src = `{{ asset('storage/') }}/${banners[currentIndex].image}`;
        bannerImage.style.opacity = 1;
        nextBannerImage.src = `{{ asset('storage/') }}/${banners[(currentIndex + 1) % banners.length].image}`;
      }, 1000);

      updateDots();
    }

    function updateDots() {
      dots.forEach((dot, index) => {
        dot.classList.toggle('bg-white', index === currentIndex);
      });
    }

    setInterval(changeImage, 5000);

    // Dropdown Menu
    document.addEventListener('DOMContentLoaded', function() {
      const dropdownToggle = document.getElementById('dropdown-toggle');
      const dropdownMenu = document.getElementById('dropdown-menu');

      dropdownToggle.addEventListener('click', function(event) {
        event.preventDefault();
        dropdownMenu.classList.toggle('hidden');
      });

      document.addEventListener('click', function(event) {
        if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
          dropdownMenu.classList.add('hidden');
        }
      });
    });

    // Logout Modal
    const logoutBtn = document.getElementById('logoutBtn');
    const logoutModal = document.getElementById('logoutModal');
    const cancelBtn = document.getElementById('cancelBtn');

    logoutBtn.addEventListener('click', function(event) {
      event.preventDefault();
      logoutModal.classList.remove('hidden');
    });

    cancelBtn.addEventListener('click', function() {
      logoutModal.classList.add('hidden');
    });

    // Real-time Cart Badge
    function realtimeData() {
      fetch('/realtime-data')
        .then(response => response.json())
        .then(data => {
          const cartCount = data.length;
          const badge = document.getElementById('cart-badge');

          if (cartCount > 0) {
            badge.innerText = cartCount;
            badge.style.display = 'inline-block';
          } else {
            badge.style.display = 'none';
          }
        })
        .catch(error => console.log('Error fetching cart data:', error));
    }

    setInterval(realtimeData, 5000);
  </script>
</body>
</html>