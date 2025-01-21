<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\customer;
use App\Models\material;
use App\Models\service;
use App\Models\transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{

 
   
    public function getAllData()
{
     $category = category::all();
     $customer = customer::all();
        $services = service::all();
        $materials = material::all(); // Tambahkan ini untuk mengambil data material
    // Ambil semua data transaksi beserta relasinya
    $transactions = Transaction::with([
        'customer', 
        'payment', 
        'detailTransactions.service', 
        'detailTransactions.material', 
    ])->get();

    // Jika tidak ada transaksi, kembalikan response error
    if ($transactions->isEmpty()) {
        return response()->json([
            'message' => 'Tidak ada transaksi ditemukan',
        ], 404);
    }

    // Format response untuk setiap transaksi
    $formattedTransactions = $transactions->map(function ($transaction) {
        return [
            'id' => $transaction->id,
            'status' => $transaction->status,
            'created_at' => $transaction->created_at->format('Y-m-d'),
            'customer' => [
                'name' => $transaction->customer->name,
                'number' => $transaction->customer->number,
            ],
            'payment' => [
                'method' => $transaction->payment->method,
            ],
            'total_price' => $transaction->total_price,
            'detail_transactions' => $transaction->detailTransactions->map(function ($detail) {
                return [
                    'service_name' => $detail->service->name_services,
                    'service_price' => $detail->service->price,
                    'quantity' => $detail->quantity,
                    'material_name' => $detail->material ? $detail->material->material_name : 'N/A',
                    'panjang' => $detail->panjang,
                    'lebar' => $detail->lebar,
                    'image' => $detail->image,
                    'lampiran' => $detail->lampiran,
                    'created_at' => $detail->created_at->format('Y-m-d H:i:s'),
                ];
            }),
        ];
    });

    // Kembalikan response JSON
    return response()->json([
        'transactions' => $formattedTransactions,'service' =>$services , 'materials' =>$materials, 'customer' =>$customer,'category' =>$category
    ]);
}
}
