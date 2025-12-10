@extends('admin.layout.auth')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">

        <div class="bg-white rounded-lg shadow-lg p-8 border border-gray-200">

            <h2 class="text-2xl font-semibold text-center text-gray-800 mb-8">
                Log In
            </h2>

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Username or Email Address
                    </label>
                    <input type="text" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0073aa] focus:border-[#0073aa] outline-none text-gray-800">
                    @error('email')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#0073aa] focus:border-[#0073aa] outline-none text-gray-800">
                    <button type="button" onclick="togglePassword()"
                        class="absolute inset-y-0 right-0 flex items-center pr-4 pt-7 text-gray-500 hover:text-gray-700">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                    @error('password')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-[#0073aa] focus:ring-[#0073aa]">
                        <span class="ml-2 text-gray-600">Remember Me</span>
                    </label>
                    <a href="#" class="text-[#0073aa] hover:underline">Lost your password?</a>
                </div>

                <button type="submit"
                    class="w-full mt-6 bg-[#0073aa] hover:bg-[#006799] text-white font-medium py-3 rounded-md transition duration-200">
                    Log In
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-500">
                <a href="{{ url('/') }} " class="text-[#0073aa] hover:underline">← Back to Site</a>
            </div>
        </div>

        <p class="text-center text-xs text-gray-500 mt-8">
            Powered by Resolusi
        </p>
    </div>
</div>

<script>
function togglePassword() {
    const password = document.getElementById('password');
    const eye = document.getElementById('eye-icon');

    if (password.type === 'password') {
        password.type = 'text';
        eye.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.974 9.974 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
    } else {
        password.type = 'password';
        eye.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
    }
}
</script>
@endsection
