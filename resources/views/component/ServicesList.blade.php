<section class="mb-8 ml-8 mr-8">
    <h2 class="text-2xl font-bold mb-4">
        Layanan Lainnya
    </h2>
    <div class="grid grid-cols-6 gap-4">
        @foreach($allServices as $service)
            <a href="{{ route('front.detailServices', ['id' => $service->id]) }}" class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition-shadow duration-300" style="height: auto; width: 100%;">
				<div style="height: 200px; width: 100%;">
                <img alt="{{ $service->name_services }}" class="w-full rounded-lg mb-2" src="{{ asset('storage/' . $service->image) }}" style="object-fit: cover; height: 100%; width: 100%;"/>
				</div>
                <h3 class="text-gray-700 font-bold">
                    {{ $service->name_services }}
                </h3>
                <p class="text-gray-500 text-sm">
                    {{ $service->category->category_name }}
                </p>

                <!-- Mengambil harga berdasarkan jumlah variant -->
                @if(isset($service->variant) && count($service->variant) > 0)
                    @php
                        // Ambil array total_price_services dari varian
                        $prices = array_column($service->variant, 'total_price_services');
                    @endphp

                    @if(count($prices) > 1)
                        <!-- Jika ada lebih dari satu variant, tampilkan rentang harga -->
                        @php
                            $minPrice = min($prices);
                            $maxPrice = max($prices);
                        @endphp
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-red-600 font-bold">
                                Rp {{ number_format($minPrice, 0, ',', '.') }} - Rp {{ number_format($maxPrice, 0, ',', '.') }}
                            </span>
                        </div>
                    @else
                        <!-- Jika hanya satu variant, tampilkan harga tunggal -->
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-red-600 font-bold">
                                Rp {{ number_format($prices[0], 0, ',', '.') }}
                            </span>
                        </div>
                    @endif
                @endif

                <div class="flex items-center justify-between mt-2">
                    <div class="flex space-x-1">
                        @foreach($service->variant as $variant)
                            @if(isset($variant['variant']))
                                <span class="w-3 h-3 rounded-full inline-block" style="background-color: orange;"></span>
                            @endif
                        @endforeach
                    </div>
                    <div class="flex space-x-2">
                        <i class="fas fa-heart text-gray-500"></i>
                        <i class="fas fa-shopping-cart text-gray-500"></i>
                    </div>
                </div>
			
            </a>
        @endforeach

  
    </div>
</section>