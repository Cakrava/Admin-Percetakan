<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class WhatsAppController extends Controller
{
    public function sendFile(Request $request)
    {
        // Data yang akan dikirim ke server Node.js
        $data = [
            'phoneNumber' => '6282297068911', // Nomor telepon tujuan
            'fileUrl' => $request->fileUrl,   // URL file (PDF atau gambar)
            'caption' => $request->caption,   // Caption (opsional)
        ];

        // Buat instance Guzzle Client
        $client = new Client();

        try {
            // Kirim request POST ke server Node.js
            $response = $client->post('http://127.0.0.1:3000/send-file', [
                'json' => $data, // Kirim data dalam format JSON
            ]);

            // Ambil respons dari server Node.js
            $responseBody = $response->getBody()->getContents();
            return response()->json(json_decode($responseBody, true));
        } catch (\Exception $e) {
            // Tangani error
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}