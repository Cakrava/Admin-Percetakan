<!-- resources/views/filament/pages/actions/view-detail.blade.php -->
<div class="space-y-4">
    <!-- Card untuk Nama Layanan -->
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-sm font-medium text-gray-500">Nama Layanan:</div>
        <div class="mt-2 text-lg font-semibold text-gray-900">{{ $record->name_services }}</div>
    </div>

    <!-- Card untuk Kategori -->
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-sm font-medium text-gray-500">Kategori:</div>
        <div class="mt-2 text-sm text-gray-700">{{ $record->category->category_name }}</div>
    </div>

    <!-- Card untuk Harga Total -->
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-sm font-medium text-gray-500">Harga Total:</div>
        <div class="mt-2 text-lg font-semibold text-gray-900">Rp {{ number_format($record->price, 0, ',', '.') }}</div>
    </div>

    <!-- Card untuk Deskripsi -->
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-sm font-medium text-gray-500">Deskripsi:</div>
        <div class="mt-2 text-sm text-gray-700">{{ $record->descriptions }}</div>
    </div>

    <!-- Card untuk Gambar -->
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-sm font-medium text-gray-500">Gambar:</div>
        <div class="mt-2">
            <img src="{{ asset('storage/' . $record->image) }}" alt="Gambar Layanan"
                class="w-full h-64 object-cover rounded-lg">
        </div>
    </div>
</div>