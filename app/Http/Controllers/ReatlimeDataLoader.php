<?php

namespace App\Http\Controllers;

use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Storage;
use App\Models\Balance;
use App\Models\BalanceHistory;
use App\Models\banner;
use App\Models\Cart;
use App\Models\category;
use App\Models\customer;
use App\Models\DetailTransaction;
use App\Models\material;
use App\Models\payment;
use App\Models\service;
use App\Models\TempDataTransaction;
use App\Models\transaction;
use App\Models\User;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;

class ReatlimeDataLoader extends Controller
{


    public function saveIdToJson(Request $request)
    {
        // Ambil data dari body request
        $data = $request->all();
        $uniqueID = $data['uniqueID'];

        // Path ke file JSON di public/storage
        $filePath = storage_path('app/public/temp/temp_unique_id.json'); // Simpan di storage Laravel
        // $filePath = storage_path('app/public/custom/temp/temp_unique_id.json'); // Simpan di storage Laravel

        // Simpan kode unik ke file JSON
        $result = file_put_contents($filePath, json_encode(['uniqueID' => $uniqueID]));

        // Berikan response
        if ($result !== false) {
            return response()->json(['message' => 'Kode unik berhasil disimpan.']);
        } else {
            return response()->json(['message' => 'Gagal menyimpan kode unik.'], 500);
        }
    }


    
    public function saveTotalHargaToJson(Request $request)
    {
        // Ambil data dari body request
        $data = $request->all();
        $totalHarga = $data['totalHarga'];

        // Path ke file JSON di public/storage
        $filePath = storage_path('app/public/temp/temp_total_harga.json'); // Simpan di storage Laravel

        // Simpan kode unik ke file JSON
        $result = file_put_contents($filePath, json_encode(['totalHarga' => $totalHarga]));

        // Berikan response
        if ($result !== false) {
            return response()->json(['message' => 'Kode unik berhasil disimpan.']);
        } else {
            return response()->json(['message' => 'Gagal menyimpan kode unik.'], 500);
        }
    }
    public function tabelPreviewTransaction()
    {
        $data = TempDataTransaction::with('service')->get();
        return response()->json($data);
    }



    // Method untuk menghapus data
    public function hapusTransaction($itemId)
    {
        $id = $itemId;
        $transaction = TempDataTransaction::find($id); // Cari data berdasarkan ID
        if ($transaction) {
            $transaction->delete(); // Hapus data
            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }
    }

    public function serviceFormTransaction()
    {
        $customer = customer::all();
        $services = service::with('category', 'material')->get();
        $payment = payment::all();
        $materials = material::all(); // Tambahkan ini untuk mengambil data material
        return response()->json(['services' => $services, 'materials' => $materials,'customer' => $customer,'payment' => $payment ]);
    }
    public function saveTempData(Request $request)
    {
        // Validasi data
        $request->validate([
            'group_id' => 'nullable|string',
            'service_id' => 'required|integer',
            'jumlah' => 'nullable|integer',
            'harga' => 'required|numeric',
            'quantity' => 'nullable|integer',
            'lebar' => 'nullable|numeric',
            'panjang' => 'nullable|numeric',
            'id_material' => 'nullable|integer', // Tambahkan validasi untuk id_material
            'image' => 'nullable',
            'lampiran' => 'nullable|file|mimes:png,jpg,rar,pdf|max:2048',
        ]);
    
        $filePath = null;
    
        // Simpan file jika ada
        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $filePath = $file->store('lampiran', 'public'); // Simpan file di storage/public/lampiran
        }
    
        // Cek apakah data dengan group_id, service_id, quantity, panjang, lebar, dan id_material yang sama sudah ada
        $existingData = TempDataTransaction::where('group_id', $request->group_id)
            ->where('service_id', $request->service_id)
            ->where('quantity', $request->quantity)
            ->where('panjang', $request->panjang)
            ->where('lebar', $request->lebar)
            ->where('id_material', $request->id_material) // Tambahkan pengecekan id_material
            ->first();
    
        // Kualifikasi 1: Jika semua field sama, update jumlah dan harga
        if ($existingData) {
            $existingData->update([
                'jumlah' => $existingData->jumlah + $request->jumlah,
                'harga' => $existingData->harga + $request->harga,
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate (jumlah dan harga ditambahkan).',
            ]);
        }
    
        // Kualifikasi 2: Jika group_id, service_id, quantity, dan id_material sama, tetapi panjang atau lebar berbeda
        $existingDataWithDifferentSize = TempDataTransaction::where('group_id', $request->group_id)
            ->where('service_id', $request->service_id)
            ->where('quantity', $request->quantity)
            ->where('id_material', $request->id_material) // Tambahkan pengecekan id_material
            ->where(function ($query) use ($request) {
                $query->where('panjang', '!=', $request->panjang)
                      ->orWhere('lebar', '!=', $request->lebar);
            })
            ->first();
    
        if ($existingDataWithDifferentSize) {
            // Buat data baru dengan panjang dan lebar yang berbeda
            $tempData = TempDataTransaction::create([
                'group_id' => $request->group_id,
                'service_id' => $request->service_id,
                'jumlah' => $request->jumlah,
                'harga' => $request->harga,
                'quantity' => $request->quantity,
                'lebar' => $request->lebar,
                'panjang' => $request->panjang,
                'id_material' => $request->id_material, // Tambahkan id_material
                'image' => $request->image,
                'lampiran' => $filePath,
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Data baru berhasil dibuat (panjang atau lebar berbeda).',
            ]);
        }
    
        // Kualifikasi 3: Jika group_id, service_id, panjang, lebar, dan id_material sama, tetapi quantity berbeda
        $existingDataWithDifferentQuantity = TempDataTransaction::where('group_id', $request->group_id)
            ->where('service_id', $request->service_id)
            ->where('panjang', $request->panjang)
            ->where('lebar', $request->lebar)
            ->where('id_material', $request->id_material) // Tambahkan pengecekan id_material
            ->where('quantity', '!=', $request->quantity)
            ->first();
    
        if ($existingDataWithDifferentQuantity) {
            // Buat data baru dengan quantity yang berbeda
            $tempData = TempDataTransaction::create([
                'group_id' => $request->group_id,
                'service_id' => $request->service_id,
                'jumlah' => $request->jumlah,
                'harga' => $request->harga,
                'quantity' => $request->quantity,
                'lebar' => $request->lebar,
                'panjang' => $request->panjang,
                'id_material' => $request->id_material, // Tambahkan id_material
                'image' => $request->image,
                'lampiran' => $filePath,
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Data baru berhasil dibuat (quantity berbeda).',
            ]);
        }
    
        // Jika tidak ada kondisi di atas, buat data baru
        $tempData = TempDataTransaction::create([
            'group_id' => $request->group_id,
            'service_id' => $request->service_id,
            'jumlah' => $request->jumlah,
            'harga' => $request->harga,
            'quantity' => $request->quantity,
            'lebar' => $request->lebar,
            'panjang' => $request->panjang,
            'id_material' => $request->id_material, // Tambahkan id_material
            'image' => $request->image,
            'lampiran' => $filePath,
        ]);
    
        // Berikan response
        if ($tempData) {
            return response()->json([
                'success' => true,
                'message' => 'Data baru berhasil dibuat.',
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan data.']);
        }
    }
 
    public function simpanTransaksi(Request $request)
    {
        // Validasi data
        $request->validate([
            'id_transaction' => 'required|string',
            'id_customer' => 'required|string',
            'id_payment' => 'required|string',
            'group_id' => 'required|string',
            'total_price' => 'required|numeric',
        ]);
    
        // Simpan data ke database
        try {
            // Simpan transaksi utama
            $transaction = Transaction::create([
                'id' => $request->id_transaction,
                'id_customer' => $request->id_customer,
                'id_payment' => $request->id_payment,
                'group_id' => $request->group_id,
                'total_price' => $request->total_price,
                'status' => 'Pending', // Default status
            ]);
    
            // Cek apakah ada data di temp_data_transaction dengan group_id yang sama
            $tempData = TempDataTransaction::where('group_id', $request->group_id)->get();
    
            if ($tempData->isNotEmpty()) {
                // Pindahkan data dari temp_data_transaction ke detail_transaction
                foreach ($tempData as $temp) {
                    // Jika ada kolom yang tidak diisi, berikan nilai default
                    $jumlah = $temp->jumlah ?? 0;
                    $harga = $temp->harga ?? 0;
                    $lebar = $temp->lebar ?? 0;
                    $panjang = $temp->panjang ?? 0;
                    $image = $temp->image ?? 'default.jpg';
                    $lampiran = $temp->lampiran ?? '';
                    $id_material = $temp->id_material ?? '';
    
                    // Simpan detail transaksi
                    DetailTransaction::create([
                        'service_id' => $temp->service_id,
                        'jumlah' => $jumlah,
                        'harga' => $harga,
                        'quantity' => $temp->quantity,
                        'lebar' => $lebar,
                        'panjang' => $panjang,
                        'image' => $image,
                        'group_id' => $temp->group_id,
                        'lampiran' => $lampiran,
                        'id_material' => $id_material,
                    ]);
    
                    // Update stok material
                    $getMaterial = Material::find($id_material);
                    if ($getMaterial) {
                        $stokMaterial = $getMaterial->material_stock ?? 0;
                        $panjangMaterial = $getMaterial->material_panjang ?? 0;
                        $lebarMaterial = $getMaterial->material_lebar ?? 0;
                        $quantityMaterial = $getMaterial->material_quantity ?? 0;
    
                        // Logika pengurangan stok
                        if (empty($temp->panjang) && empty($temp->lebar) && empty($temp->quantity)) {
                            // Hanya kurangi stok
                            $getMaterial->update([
                                'material_stock' => $stokMaterial - $temp->jumlah,
                            ]);
                        } elseif (!empty($temp->quantity) && empty($temp->panjang) && empty($temp->lebar)) {
                            // Kurangi quantity, dan jadikan jumlah sebagai kali lipat
                            $getMaterial->update([
                                'material_quantity' => $quantityMaterial - ($temp->jumlah * $temp->quantity),
                            ]);
                        } elseif (empty($temp->quantity) && !empty($temp->panjang)) {
                            // Kurangi panjang, dan jadikan jumlah sebagai kali lipat
                            $getMaterial->update([
                                'material_panjang' => $panjangMaterial - ($temp->jumlah * $temp->panjang),
                            ]);
                        }
                    }
    
                    // Hapus data dari temp_data_transaction setelah dipindahkan
                    $temp->delete();
                }
            }
    
            // Ambil data untuk dikirim ke endpoint
            $paymentMethod = Payment::find($request->id_payment)->method; // Ambil method dari tabel payment
            $customer = Customer::find($request->id_customer); // Ambil data customer
    
            // Data yang akan dikirim ke endpoint
            $dataToSend = [
                'phoneNumber' => $this->formatPhoneNumber($customer->number), // Format nomor telepon
                'data' => [
                    'id_transaksi' => $request->id_transaction,
                    'id_customer' => $request->id_customer,
                    'method' => $paymentMethod,
                    'total_price' => $request->total_price,
                    'status' => 'UNPAID', // Status default
                    'name' => $customer->name ?? 'Tidak tersedia', // Ambil name customer
                    'number' => $customer->number ?? 'Tidak tersedia', // Ambil number customer
                    'address' => $customer->address ?? 'Tidak tersedia', // Ambil address customer
                ],
                'caption' => 'Konfirmasi pembayaran', // Caption tetap
            ];
    
            // Kirim data ke endpoint
            $client = new \GuzzleHttp\Client();
            $response = $client->post(Env::get('WHATSAPP_BOT') . 'send-faktur-transaction', [
                'json' => $dataToSend,
            ]);
    
            // Berikan response JSON jika berhasil
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan dan data dikirim ke endpoint!',
                'data' => $transaction,
                'endpoint_response' => json_decode($response->getBody(), true),
            ], 200);
        } catch (\Exception $e) {
            // Berikan response JSON jika terjadi error
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function formatPhoneNumber($number)
    {
        // Hilangkan semua karakter non-digit
        $number = preg_replace('/[^0-9]/', '', $number);
    
        // Periksa awalan nomor telepon
        if (str_starts_with($number, '62')) {
            // Jika sudah diawali 62, kembalikan langsung
            return $number;
        } elseif (str_starts_with($number, '2')) {
            // Jika diawali 2, tambahkan 6 di depannya
            return '62' . $number;
        } elseif (str_starts_with($number, '0')) {
            // Jika diawali 0, ganti 0 dengan 62
            return '62' . substr($number, 1);
        } elseif (str_starts_with($number, '8')) {
            // Jika diawali 8, tambahkan 62 di depannya
            return '62' . $number;
        } else {
            // Jika format tidak sesuai, kembalikan nomor asli (atau bisa throw exception)
            return $number;
        }
    }
    public function clearTempData()
    {
        $transactions = TempDataTransaction::all(); // Ambil semua data
        foreach ($transactions as $transaction) {
            $transaction->delete(); // Hapus setiap data
        }
        return response()->json(['success' => true, 'message' => 'Semua data berhasil dihapus.']);
    }



    // for customer
    public function getCustomers(Request $request)
    {
        // Ambil semua customer atau cari berdasarkan kata kunci
        $query = Customer::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('number', 'like', "%{$search}%");
        }

        $customers = $query->limit(50)->get();

        // Tambahkan opsi "Tambah Customer" di akhir hasil
        $customers->push([
            'id' => 'new_customer',
            'name' => 'Tambah Customer',
        ]);

        return response()->json($customers);
    }
    public function saveCustomerData(Request $request)
    {
        // Validasi data
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'number' => 'required|string',
            'email' => 'required|email',
        ]);
    
        // Simpan data ke tabel Customer
        $customer = Customer::create([
            'name' => $request->name,
            'address' => $request->address,
            'number' => $request->number,
            'email' => $request->email,
        ]);
    
        // Berikan response
        if ($customer) {
            return response()->json([
                'success' => true,
                'message' => 'Data customer berhasil disimpan.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data customer.',
            ]);
        }
    }
    public function getDetailsTransaction($id)
    {
        // Ambil data transaksi berdasarkan ID
        $transaction = Transaction::with([
            'customer', 
            'payment', 
            'detailTransactions.service', 
            'detailTransactions.material', 
        ])->find($id);
    
        // Jika transaksi tidak ditemukan, kembalikan response error
        if (!$transaction) {
            return response()->json([
                'message' => 'Transaksi tidak ditemukan',
            ], 404);
        }
    
        // Format response
        $formattedTransaction = [
            'id' => $transaction->id,
            'status' => $transaction->status,
            'created_at' => Carbon::parse($transaction->created_at)->format('Y-m-d'),
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
                    'id' => $detail->id, // ID detail transaksi
                    'service_name' => $detail->service->name_services, // Nama layanan
                    'service_price' => $detail->service->price, // Harga layanan
                    'jumlah' => $detail->jumlah, // Jumlah
                    'quantity' => $detail->quantity, // Jumlah
                    'material_name' => $detail->material ? $detail->material->material_name : 'N/A', // Nama material
                    'panjang' => $detail->panjang, // Panjang (jika ada)
                    'lebar' => $detail->lebar, // Lebar (jika ada)
                    'image' => $detail->image, // Gambar (jika ada)
                    'lampiran' => $detail->lampiran, // Lampiran (jika ada)
                    'created_at' => Carbon::parse($detail->created_at)->format('Y-m-d H:i:s'), // Tanggal dibuat
                ];
            }),
        ];
    
        // Kembalikan response JSON
        return response()->json([
            'transaction' => $formattedTransaction,
        ]);
    }



    public function prosesBayar($id)
    {
        try {
            // Cari data transaksi berdasarkan ID
            $transaction = Transaction::find($id);
    
            // Jika data tidak ditemukan
            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data transaksi tidak ditemukan.',
                ], 404);
            }
    
            // Update kolom status menjadi "Proses"
            $transaction->update([
                'status' => 'Proses',
            ]);
    
            // Simpan total transaksi ke tabel balance
            $balance = Balance::firstOrCreate(
                ['id' => 'balance'], // Cari atau buat record dengan id 'balance'
                ['total_balance' => 0] // Jika tidak ada, inisialisasi total_balance dengan 0
            );
    
            // Tambahkan total harga transaksi ke total_balance
            $balance->total_balance += $transaction->total_price;
            $balance->save();
    
            // Simpan ke tabel balance_history
            BalanceHistory::create([
                'message_history' => "Transaksi {$transaction->id} berhasil",
                'balance' => $balance->total_balance,
            ]);
    
            // Berikan response sukses
            return response()->json([
                'success' => true,
                'message' => 'Status transaksi berhasil diubah menjadi Proses.',
            ]);
        } catch (\Exception $e) {
            // Tangani error dan kembalikan pesan error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pembayaran.',
                'error' => $e->getMessage(), // Pesan error untuk debugging
            ], 500);
        }
    }


    public function prosesSelesai($id)
    {
        try {
            // Cari data transaksi berdasarkan ID
            $transaction = Transaction::find($id);
    
            // Jika data tidak ditemukan
            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data transaksi tidak ditemukan.',
                ], 404);
            }
    
            // Update kolom status menjadi "Completed"
            $transaction->update([
                'status' => 'Completed',
            ]);
    
            // Ambil data customer
            $customer = Customer::find($transaction->id_customer);
            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data customer tidak ditemukan.',
                ], 404);
            }
    
            // Ambil data payment
            $payment = Payment::find($transaction->id_payment);
            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data payment tidak ditemukan.',
                ], 404);
            }
    
            // Ambil data detail transaksi berdasarkan group_id
            $detailTransactions = DetailTransaction::where('group_id', $transaction->group_id)->get();
    
            // Format data detail transaksi
            $detailData = $detailTransactions->map(function ($detail) {
                $service = Service::find($detail->service_id); // Ambil nama layanan dari tabel Service
                return [
                    'service_name' => $service->name_services,
                    'service_id' => $detail->service_id,
                    'jumlah' => $detail->jumlah,
                    'quantity' => $detail->quantity,
                    'panjang' => $detail->panjang,
                    'lebar' => $detail->lebar,
                    'harga' => $detail->harga,
                ];
            });
    
            // Format data untuk dikembalikan sebagai response
            $responseData = [
                'id_transaksi' => $transaction->id,
                'id_customer' => $transaction->id_customer,
                'name' => $customer->name,
                'number' => $customer->number,
                'address' => $customer->address,
                'total_price' => $transaction->total_price,
                'status' => $transaction->status,
                'method' => $payment->method,
                'detail_transaksi' => $detailData, // Data detail transaksi
            ];
    
            // Panggil fungsi generateAndSendPdf setelah semua validasi dan pemrosesan data selesai
            $this->generateAndSendPdf($id);
    
            // Berikan response sukses
            return response()->json([
                'success' => true,
                'message' => 'Status transaksi berhasil diubah menjadi Completed.',
                'data' => $responseData,
            ]);
        } catch (\Exception $e) {
            // Tangani error dan kembalikan pesan error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses penyelesaian.',
                'error' => $e->getMessage(), // Pesan error untuk debugging
            ], 500);
        }
    }


    public function getDetailTransaction($id)
    {
        // Ambil data transaksi berdasarkan ID
        $transaction = Transaction::with([
            'customer', 
            'payment', 
            'detailTransactions.service', 
            'detailTransactions.material', 
        ])->find($id);
    
        // Jika transaksi tidak ditemukan, kembalikan response error
        if (!$transaction) {
            return response()->json([
                'message' => 'Transaksi tidak ditemukan',
            ], 404);
        }
    
        // Jika snap_token belum ada, buat dan simpan ke database
        if (!$transaction->snap_token) {
            // Set konfigurasi Midtrans
            Config::$serverKey = config('midtrans.serverKey');
            Config::$isProduction = config('midtrans.isProduction');
            Config::$isSanitized = config('midtrans.isSanitized');
            Config::$is3ds = config('midtrans.is3ds');
    
            // Buat parameter untuk Snap Token
            $params = [
                'transaction_details' => [
                    'order_id' => $transaction->id, // Gunakan ID transaksi sebagai order_id
                    'gross_amount' => $transaction->total_price, // Total harga transaksi
                ],
                'customer_details' => [
                    'first_name' => $transaction->customer->name, // Nama pelanggan
                    'email' => $transaction->customer->email ?? 'email@default.com', // Email pelanggan
                    'phone' => $transaction->customer->number, // Nomor telepon pelanggan
                ],
            ];
    
            try {
                // Generate Snap Token
                $snapToken = Snap::getSnapToken($params);
    
                // Simpan snap_token ke database
                $transaction->snap_token = $snapToken;
                $transaction->save();
            } catch (\Exception $e) {
                // Tangani error dari Midtrans API
                return response()->json([
                    'message' => 'Gagal menghasilkan Snap Token',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
    
        // Format response
        
        $formattedTransaction = [
            'id' => $transaction->id,
            'status' => $transaction->status,
            'created_at' => Carbon::parse($transaction->created_at)->format('Y-m-d'),
            'customer' => [
                'name' => $transaction->customer->name,
                'number' => $transaction->customer->number,
            ],
            'payment' => [
                'method' => $transaction->payment->method,
            ],
            'total_price' => $transaction->total_price,
            'snap_token' => $transaction->snap_token, // Sertakan snap_token dalam response
            'detail_transactions' => $transaction->detailTransactions->map(function ($detail) {
                return [
                    'id' => $detail->id, // ID detail transaksi
                    'service_name' => $detail->service->name_services, // Nama layanan
                    'service_price' => $detail->service->price, // Harga layanan
                    'jumlah' => $detail->jumlah, // Jumlah
                    'quantity' => $detail->quantity, // Jumlah
                    'material_name' => $detail->material ? $detail->material->material_name : 'N/A', // Nama material
                    'panjang' => $detail->panjang, // Panjang (jika ada)
                    'lebar' => $detail->lebar, // Lebar (jika ada)
                    'image' => $detail->image, // Gambar (jika ada)
                    'lampiran' => $detail->lampiran, // Lampiran (jika ada)
                    'created_at' => Carbon::parse($detail->created_at)->format('Y-m-d H:i:s'), // Tanggal dibuat
                ];
            }),
        ];
    
        // Kembalikan response JSON
        return response()->json([
            'transaction' => $formattedTransaction,
        ]);
    }
    public function generatePdf($id)
    {
        // Ambil data transaksi berdasarkan ID dengan relasi yang lengkap
        $transaction = Transaction::with([
            'customer', 
            'payment', 
            'detailTransactions.service', 
            'detailTransactions.material', 
        ])->findOrFail($id);
    
        // Generate PDF menggunakan template di resources/views/filament/view/pdf-view.blade.php
        $pdf = PDF::loadView('filament.view.pdf-view', compact('transaction'));
    
        // Simpan PDF ke storage untuk debugging
        $pdfPath = storage_path("app/public/faktur-transaksi-{$id}.pdf");
        $pdf->save($pdfPath);
    
        // Unduh PDF
        return $pdf->download("faktur-transaksi-{$id}.pdf");
    }
    public function generateAndSendPdf($id)
{
    // Ambil data transaksi berdasarkan ID dengan relasi yang lengkap
    $transaction = Transaction::with([
        'customer', 
        'payment', 
        'detailTransactions.service', 
        'detailTransactions.material', 
    ])->findOrFail($id);

    // Generate PDF menggunakan fungsi yang sudah berfungsi
    $pdf = PDF::loadView('filament.view.pdf-view', compact('transaction'));

    // Simpan PDF ke storage untuk debugging
    $pdfPath = storage_path("app/public/faktur-transaksi-{$id}.pdf");
    $pdf->save($pdfPath);

    // Baca file PDF sebagai base64
    $pdfBase64 = base64_encode(file_get_contents($pdfPath));

    // Validasi dan konversi nomor telepon
    $phoneNumber = $this->nomorhape($transaction->customer->number);

    // Kirim data ke server Node.js
    $response = Http::post('http://localhost:3000/send-faktur', [
        'phoneNumber' => $phoneNumber, // Nomor telepon yang sudah diformat
        'pdfBase64' => $pdfBase64,
        'caption' => 'Berikut adalah faktur transaksi Anda.',
    ]);

    // Hapus file PDF sementara
    unlink($pdfPath);

    // Berikan respons ke client
    if ($response->successful()) {
        return response()->json(['success' => true, 'message' => 'Faktur berhasil dikirim']);
    } else {
        return response()->json(['success' => false, 'message' => 'Gagal mengirim faktur'], 500);
    }
}

private function nomorhape($phoneNumber)
{
    // Hapus semua karakter non-digit
    $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

    // Jika nomor diawali dengan 08, ganti 0 dengan 62
    if (strpos($phoneNumber, '08') === 0) {
        $phoneNumber = '62' . substr($phoneNumber, 1);
    }
    // Jika nomor diawali dengan 8, tambahkan 62 di depannya
    elseif (strpos($phoneNumber, '8') === 0) {
        $phoneNumber = '62' . $phoneNumber;
    }
    // Jika nomor sudah diawali dengan 62, biarkan seperti itu
    elseif (strpos($phoneNumber, '62') === 0) {
        // Nomor sudah valid
    }
    // Jika format tidak sesuai, kembalikan error
    else {
        throw new \Exception('Format nomor telepon tidak valid');
    }

    return $phoneNumber;
}
    
   
}