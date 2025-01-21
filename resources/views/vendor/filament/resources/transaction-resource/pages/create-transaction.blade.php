@extends('filament::pages.create-record')

@section('content')
    <form wire:submit.prevent="submit">
        <!-- Field lainnya -->
        <input type="text" wire:model="author_id" hidden>
        <input type="text" wire:model="customer_name" disabled>

        <!-- Tombol untuk membuka modal -->
        <button type="button" onclick="openCustomerModal()" class="bg-blue-500 text-white p-2 rounded">
            Pilih Customer
        </button>

        <!-- Tombol submit form -->
        <button type="submit" class="bg-green-500 text-white p-2 rounded">
            Simpan
        </button>
    </form>

    <script>
        // Fungsi untuk membuka modal
        function openCustomerModal() {
            window.dispatchEvent(new CustomEvent('openCustomerModal'));
        }

        // Fungsi untuk menangani event customerSelected
        window.addEventListener('customerSelected', (event) => {
            const { customerId, customerName } = event.detail;

            // Isi field di form utama
            Livewire.emit('setCustomer', customerId, customerName);
        });

        // Fungsi untuk menutup modal
        window.addEventListener('closeModal', () => {
            // Tutup modal
            // (Anda bisa menambahkan logika untuk menutup modal di sini)
        });
    </script>
@endsection