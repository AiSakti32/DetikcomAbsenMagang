<x-guest-layout>
    <!-- Eyebrow & Title -->
    <div class="mb-8 text-left">
        <span class="text-xs font-semibold uppercase tracking-wider text-[#5B6472] block mb-2 font-heading">
            Portal peserta &amp; admin
        </span>
        <h2 class="font-heading font-bold text-2xl sm:text-3xl text-brand-navy tracking-tight">
            Masuk ke akun kamu
        </h2>
        <p class="text-xs text-[#5B6472] mt-2 leading-relaxed">
            Gunakan kredensial yang telah didaftarkan oleh admin program magang.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-sm text-emerald-600" :status="session('status')" />

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email -->
        <div class="space-y-1">
            <label for="email" class="block text-xs font-medium text-[#5B6472] uppercase tracking-wide">
                Alamat Email
            </label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                placeholder="nama@detik.com atau email magang"
                class="underline-input"
                autocomplete="username"
            >
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="space-y-1 relative">
            <label for="password" class="block text-xs font-medium text-[#5B6472] uppercase tracking-wide">
                Kata Sandi
            </label>
            <div class="relative">
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                    class="underline-input pr-8"
                    autocomplete="current-password"
                >
                <button
                    type="button"
                    id="toggle-password"
                    aria-label="Lihat kata sandi"
                    aria-pressed="false"
                    class="absolute right-0 top-1/2 -translate-y-1/2 text-slate-400 hover:text-brand-blue transition-colors p-1"
                >
                    <svg id="icon-eye" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg id="icon-eye-off" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember me -->
        <div class="flex items-center text-xs pt-1">
            <label class="flex items-center gap-2 cursor-pointer text-[#5B6472] hover:text-brand-navy select-none transition-colors">
                <input
                    type="checkbox"
                    name="remember"
                    class="w-4 h-4 rounded border-slate-300 text-brand-blue focus:ring-brand-blue focus:ring-offset-0 transition-colors accent-[#0857C3] cursor-pointer"
                >
                <span>Ingat saya</span>
            </label>
        </div>

        <!-- Submit -->
        <div class="pt-2">
            <button
                type="submit"
                class="w-full py-3.5 px-4 bg-brand-navy hover:bg-brand-blue active:bg-[#051329] text-white font-semibold text-sm rounded-lg transition-all duration-200 shadow-sm hover:shadow active:scale-[0.99] flex items-center justify-center gap-2 group font-heading tracking-wide"
            >
                <span>Masuk</span>
                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </div>

        <!-- Helper text -->
        <div class="pt-4 text-center">
            <p class="text-[11.5px] leading-relaxed text-[#5B6472]">
                Belum punya akun? Hubungi admin program magang untuk didaftarkan.
            </p>
        </div>
    </form>

    <script>
        document.getElementById('toggle-password').addEventListener('click', function () {
            const input = document.getElementById('password');
            const eyeIcon = document.getElementById('icon-eye');
            const eyeOffIcon = document.getElementById('icon-eye-off');
            const willShow = input.type === 'password';

            input.type = willShow ? 'text' : 'password';
            eyeIcon.classList.toggle('hidden', willShow);
            eyeOffIcon.classList.toggle('hidden', !willShow);
            this.setAttribute('aria-label', willShow ? 'Sembunyikan kata sandi' : 'Lihat kata sandi');
            this.setAttribute('aria-pressed', String(willShow));
        });
    </script>
</x-guest-layout>
