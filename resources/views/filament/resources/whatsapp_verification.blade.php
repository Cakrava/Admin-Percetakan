<html>
<head>
    <title>Sign In - NechaCorp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <div class="text-center mb-6">
            <div class="flex justify-center items-center mb-4">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-full h-20">
            </div>
        
        </div>
        <form action="{{ route('whatsapp.verifyOtp') }}" method="post">
            @csrf
            <div class="mb-4">
                {{-- <p id="message" style="color: red;">{{ session('message') }}</p> --}}
                @if(isset($error))
                    <p id="message" style="color: red;">{{ $error }}</p>
                @endif
                <div class="text-center text-gray-500 mb-2">
                  <input type="text" class="w-full px-2 py-2 border border-orange-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" value ="{{ session('getWhatsappNumber') }}"id="number" name="number">
                </div>
                <div class="relative">
                    <div class="flex space-x-4" style="justify-content: space-between;">
                        <input type="text"  style="text-align: center" maxlength="1" class="w-12 px-2 py-2 border border-orange-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 " oninput="moveToNext(this, 'input2')" id="input1" name="otp[]">
                        <input type="text"  style="text-align: center" maxlength="1" class="w-12 px-2 py-2 border border-orange-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" oninput="moveToNext(this, 'input3')" id="input2" name="otp[]">
                        <input type="text"  style="text-align: center" maxlength="1" class="w-12 px-2 py-2 border border-orange-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" oninput="moveToNext(this, 'input4')" id="input3" name="otp[]">
                        <input type="text"  style="text-align: center" maxlength="1" class="w-12 px-2 py-2 border border-orange-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" oninput="moveToNext(this, 'input5')" id="input4" name="otp[]">
                        <input type="text"  style="text-align: center" maxlength="1" class="w-12 px-2 py-2 border border-orange-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" oninput="moveToNext(this, 'input6')" id="input5" name="otp[]">
                        <input type="text"  style="text-align: center" maxlength="1" class="w-12 px-2 py-2 border border-orange-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" oninput="moveToNext(this, null)" id="input6" name="otp[]">
                    </div>
                    
                    <script>
                    function moveToNext(current, nextFieldId) {
                        if (current.value.length === 1 && nextFieldId) {
                            document.getElementById(nextFieldId).focus();
                        }
                    }
                    </script>
                </div>
                
                <div id="countdown" style="margin-top: 10px"></div>
                <a class="text-orange-500 text-sm" onclick="saveNumber()" id="resendOtp"> Resend OTP </a>
            </div>
            
            <button type="submit" class="w-full bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 mt-4">Verify</button>
        </form>
        <p id="code">{{ session('verification_code') }}</p>
     
    </div>
</body>
</html>


<script>
    function saveNumber() {

            
            var number = document.getElementById('number').value;
            
            // Pastikan input tidak kosong
            if (number) {
                // Kirim nomor melalui query string ke route yang sudah didefinisikan
                window.location.href = '/resend-otp?number=' + encodeURIComponent(number);
            } else {
                alert('Nomor WhatsApp harus diisi!');
            }


        let countdown = 60; // 3 menit dalam detik
        localStorage.setItem('countdown', countdown);
        startCountdown(); // Memulai countdown setelah menyimpan di localStorage
    }

    function startCountdown() {
        let countdown = localStorage.getItem('countdown');
        let resendOtp = document.getElementById('resendOtp');

        if (countdown) {
            countdown = parseInt(countdown, 10);
            resendOtp.textContent = `Resend OTP dalam ${Math.floor(countdown / 60)}:${countdown % 60}`;

            let interval = setInterval(() => {
                countdown--;
                localStorage.setItem('countdown', countdown);

                if (countdown <= 0) {
                    clearInterval(interval);
                    resendOtp.textContent = 'Send OTP';
                    localStorage.removeItem('countdown');
                } else {
                    resendOtp.textContent = `Resend OTP dalam ${Math.floor(countdown / 60)}:${countdown % 60}`;
                }
            }, 1000);
        }
    }

    // Memulai countdown saat halaman dimuat ulang
    window.onload = function() {
        let countdown = localStorage.getItem('countdown');
        if (countdown && countdown > 0) {
            startCountdown();
        }
    }
</script>
