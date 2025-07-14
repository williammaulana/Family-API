@extends('layouts.guest') {{-- We’ll create this layout next --}}

@section('content')
<div class="flex min-h-screen">
    {{-- Left Section --}}
    <div class="w-1/2 bg-gray-50 flex flex-col items-center justify-center text-center p-10 hidden md:flex">
        <img src="{{ asset('images/rocket.png') }}" alt="Rocket" class="w-32 h-32 mb-6">
        <h2 class="text-2xl font-bold mb-2">Pengiriman Cepat</h2>
        <p class="text-gray-600">Family Store memastikan pengiriman barang dalam jangka waktu<br>2×24 jam*</p>
    </div>

    {{-- Right Section --}}
    <div class="w-full md:w-1/2 flex items-center justify-center p-10">
        <div class="w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6">Selamat Datang!</h2>
            <p class="text-gray-600 mb-6">Sebelum berbelanja, mohon untuk masuk dengan akun terlebih dahulu.</p>

            @if(session('status'))
                <div class="text-green-600 mb-4">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <input type="email" name="email" required placeholder="Email"
                    class="w-full border px-4 py-2 rounded">

                <div class="relative">
                    <input type="password" name="password" required placeholder="Kata Sandi"
                        class="w-full border px-4 py-2 rounded" id="password-input">
                    <span onclick="togglePassword()" class="absolute right-3 top-2 cursor-pointer text-gray-500">
                        👁️
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember">
                        <span class="text-sm">Tetap Masuk</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-teal-700">Lupa Kata Sandi?</a>
                </div>

                <button type="submit"
                    class="w-full bg-teal-700 hover:bg-teal-800 text-white py-2 px-4 rounded font-semibold">
                    Masuk
                </button>
            </form>

            <p class="mt-6 text-sm text-center text-gray-600">
                Belum punya akun? <a href="{{ route('register') }}" class="text-teal-700 font-semibold">Daftar Sekarang</a>
            </p>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password-input');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endsection
