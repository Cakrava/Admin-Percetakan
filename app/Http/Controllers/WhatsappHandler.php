<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;



class WhatsappHandler extends Controller
{
    public function whatsappVerification(Request $request)
    {
        

        $error = session()->get('message');
       
        // Pesan yang akan dikirim ke WhatsApp
      
        return view('filament.resources.whatsapp_verification', ['error' => $error]);
    }
    public function resendOtp(Request $request)
    {

        $randomCode = rand(100000, 999999);  // Kode OTP 6 digit
        $expirationTime = Carbon::now()->addMinutes(1);  // Kadaluarsa dalam 3 menit
        $number = session()->get('getWhatsappNumber');
        session()->put('verification_code', $randomCode);
        session()->put('verification_code_expiration', $expirationTime);
    
        $number = $request->input('number');
        session()->put('getWhatsappNumber', $number);

        $message = "Kode OTP Anda untuk verifikasi adalah $randomCode. Kode ini berlaku hanya selama 3 menit. Mohon untuk memasukkan kode tersebut sebelum waktu habis.";

        $response = Http::get("http://127.0.0.1:3000/send-message/{$number}/{$message}");
    
        // Periksa apakah permintaan berhasil
        if ($response->successful()) {
          session()->put('message', '');
        } else {
            session()->put('message', 'pastikan nomor yang dimasukkan sudah benar');
        }
    
        return redirect()->route('whatsapp.verification');
    }
        
        public function verifyOtp(Request $request)
        {
            $user = User::where('email', session('getUser'))->first();
            $number = $request->input('number');
            $otp = $request->input('otp');
            $otpCode = implode('', $otp);
            $cleanOtpCode = (int)$otpCode;  // Konversi otpCode menjadi integer
            $verification_code = session('verification_code');
            $expirationTime = session('verification_code_expiration');
            if($expirationTime >= Carbon::now()){

                if ($cleanOtpCode === $verification_code) {

                    $user->email = $user->email;
                    $user->status = 'verified';
                    $user->number = $number;
                    $user->save();
                    session()->put('user', $user->email);
                    session()->put('isLogin', 'yes');
                    session()->put('role', $user->role);
                    session()->put('status', $user->status);
                    session()->put('whatsapp_number', $user->number);
                return redirect()->route('front.index')->with('script', 'localStorage.setItem("countdown", 0);');
                }else{
                    session()->put('message', 'Kode OTP Salah');
                    return redirect()->route('whatsapp.verification');
                }

            }else{
                session()->put('message', 'Kode OTP Kadaluarsa');
                return redirect()->route('whatsapp.verification');
            }
        }
}