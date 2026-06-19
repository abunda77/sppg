<x-layouts::auth.login :title="__('Log in')">
    <section class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-[#0d1310]/95 px-6 py-8 shadow-2xl shadow-black/40 backdrop-blur-xl sm:px-10 sm:py-10">
        <div class="pointer-events-none absolute inset-x-8 top-0 h-px bg-linear-to-r from-transparent via-[#d6ad4b]/80 to-transparent"></div>
        <img
            src="{{ asset('images/welcome/logo.png') }}"
            alt=""
            class="pointer-events-none absolute top-[26%] left-1/2 w-[80%] -translate-x-1/2 opacity-[0.08] grayscale"
            aria-hidden="true"
        >

        <div class="relative z-10 flex flex-col gap-7">
            <a href="{{ route('home') }}" class="group mx-auto flex items-center justify-center gap-4" wire:navigate aria-label="{{ __('Return to the home page') }}">
                <span class="grid h-20 w-20 place-items-center rounded-2xl border border-white/10 bg-white/95 p-2.5 shadow-lg shadow-black/20 transition duration-300 group-hover:-translate-y-1 group-hover:border-[#d6ad4b]/60">
                    <img src="{{ asset('images/welcome/logo_PMJ.png') }}" alt="Logo Polda Metro Jaya" class="size-full object-contain">
                </span>
                <span class="grid h-20 w-20 place-items-center rounded-2xl border border-white/10 bg-white/95 p-2.5 shadow-lg shadow-black/20 transition duration-300 group-hover:-translate-y-1 group-hover:border-[#d6ad4b]/60">
                    <img src="{{ asset('images/welcome/logo_LOGISTIK_PMJ.png') }}" alt="Logo Logistik Polda Metro Jaya" class="size-full object-contain">
                </span>
            </a>

            <header class="text-center">
                <p class="text-[0.65rem] font-semibold tracking-[0.24em] text-[#d6ad4b] uppercase">Satuan Pelayanan Pemenuhan Gizi</p>
                <h1 class="mt-3 text-2xl font-semibold tracking-[-0.035em] text-white sm:text-3xl">E-SPPG POLRI Cengkareng</h1>
                <p class="mt-2 text-sm leading-6 text-white/55">{{ __('Log in to continue to your account') }}</p>
            </header>

            <x-auth-session-status class="text-center" :status="session('status')" />

            <div class="[&_[data-flux-button]]:border-white/10 [&_[data-flux-button]]:bg-white/5 [&_[data-flux-button]]:text-white [&_[data-flux-button]]:shadow-none hover:[&_[data-flux-button]]:bg-white/10">
                <x-passkey-verify />
            </div>

            <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5 [--color-accent:#d6ad4b] [--color-accent-foreground:#17120a]">
                @csrf

                <flux:input
                    name="email"
                    :label="__('Email address')"
                    :value="old('email')"
                    type="email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="email@example.com"
                    class="[&_[data-flux-control]]:border-white/10 [&_[data-flux-control]]:bg-white/[0.045] [&_[data-flux-control]]:shadow-none focus-within:[&_[data-flux-control]]:border-[#d6ad4b]/60"
                />

                <div class="relative">
                    <flux:input
                        name="password"
                        :label="__('Password')"
                        type="password"
                        required
                        autocomplete="current-password"
                        :placeholder="__('Password')"
                        viewable
                        class="[&_[data-flux-control]]:border-white/10 [&_[data-flux-control]]:bg-white/[0.045] [&_[data-flux-control]]:shadow-none focus-within:[&_[data-flux-control]]:border-[#d6ad4b]/60"
                    />

                    @if (Route::has('password.request'))
                        <flux:link class="absolute top-0 text-sm text-[#d6ad4b] decoration-[#d6ad4b]/30 end-0 hover:text-[#e5c46f]" :href="route('password.request')" wire:navigate>
                            {{ __('Forgot your password?') }}
                        </flux:link>
                    @endif
                </div>

                <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

                <flux:button variant="primary" type="submit" class="w-full transition duration-200 hover:-translate-y-0.5" data-test="login-button">
                    {{ __('Log in') }}
                </flux:button>
            </form>

            <div class="space-x-1 text-center text-sm text-white/50 rtl:space-x-reverse">
                <span>{{ __('Don\'t have an account?') }}</span>
                <flux:link class="text-[#d6ad4b] decoration-[#d6ad4b]/30 hover:text-[#e5c46f]" :href="route('register')" wire:navigate>{{ __('Sign up') }}</flux:link>
            </div>
        </div>
    </section>

    <p class="mt-5 text-center text-xs tracking-wide text-white/35">&copy; {{ now()->year }} E-SPPG POLRI Cengkareng</p>
</x-layouts::auth.login>
