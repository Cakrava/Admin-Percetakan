@extends('front.layout.main')
@section('content')
<main class="container mx-auto px-4 py-8">

	
    <div class="flex flex-col md:flex-row items-center justify-between p-4" style="margin-bottom: 200px;">
        <!-- Teks Section -->
        <div class="flex flex-col items-center md:items-start text-center md:text-left animate-slide-in-left" style="flex: 1;">
            <h1 class="text-3xl font-bold">CUSTOMIZE</h1>
            <h2 class="text-5xl font-bold" style="font-size: 80px;">YOUR SHIRT</h2>
            <div class="mt-4">
                <p class="text-sm">Cipkatan nuansa baru dengan mengkustom kaos sesuai</p>
                <p class="text-sm">imajinasi mu</p>
            </div>
        </div>
    
        <!-- Gambar Section -->
        <div class="flex items-center justify-center md:justify-end p-4 animate-slide-in-right" style="flex: 1;">
            <img src="{{ asset('img/product/sample_baju.png') }}" alt="Customize Your Shirt" class="w-50 md:w-50">
        </div>
    </div>
    
    <style>
        /* Animasi untuk teks (slide dari kiri ke kanan) */
        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    
        /* Animasi untuk gambar (slide dari kanan ke kiri) */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    
        /* Kelas animasi untuk teks */
        .animate-slide-in-left {
            animation: slideInLeft 1s ease-out forwards;
        }
    
        /* Kelas animasi untuk gambar */
        .animate-slide-in-right {
            animation: slideInRight 1s ease-out forwards;
        }
    </style>
    
@include('component.ServicesList')

  </main>
@endsection
