@extends('filament::page')

@section('content')
<link rel="stylesheet" href="{{ asset('custom/style.css') }}">
<script src="{{ asset('custom/script-temp-transaction.js') }}"></script>


@include('filament.view.component_transaction.customer-form-transaction')
@include('filament.view.component_transaction.service-form-transaction')
@include('filament.view.component_transaction.tabel-preview-transaction')

@endsection
{{-- 
@extends('filament::page')

@section('title', 'Halaman Create Transaction')

@section('content')
<div style="text-align: center; margin-top: 20px;">
    <h1>Halaman Create Transaction</h1>
    <button 
        onclick="window.location='{{ route('transaction.view') }}'" 
        style="border-radius: 5px; background-color: blue; color: white; padding: 10px 20px; border: none; cursor: pointer;">
        Pergi ke Halaman Preview Transaction
    </button>
</div>
@endsection --}}
