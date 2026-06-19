<x-layouts::auth.login :title="$title ?? null">
    <section class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-[#0d1310]/95 px-6 py-8 shadow-2xl shadow-black/40 backdrop-blur-xl sm:px-10 sm:py-10">
        <div class="pointer-events-none absolute inset-x-8 top-0 h-px bg-linear-to-r from-transparent via-[#d6ad4b]/80 to-transparent"></div>
        <img
            src="{{ asset('images/welcome/logo.png') }}"
            alt=""
            class="pointer-events-none absolute top-[26%] left-1/2 w-[80%] -translate-x-1/2 opacity-[0.08] grayscale"
            aria-hidden="true"
        >

        <div class="relative z-10 flex flex-col gap-7 [--color-accent:#d6ad4b] [--color-accent-foreground:#17120a] [&_[data-flux-button]]:shadow-none [&_[data-flux-control]]:border-white/10 [&_[data-flux-control]]:bg-white/[0.045] [&_[data-flux-control]]:shadow-none [&_[data-flux-label]]:text-white [&_[data-flux-subheading]]:text-white/55 [&_[data-flux-text]]:text-white/60">
            <a href="{{ route('home') }}" class="group mx-auto flex items-center justify-center gap-4" wire:navigate aria-label="{{ __('Return to the home page') }}">
                <span class="grid h-20 w-20 place-items-center rounded-2xl border border-white/10 bg-white/95 p-2.5 shadow-lg shadow-black/20 transition duration-300 group-hover:-translate-y-1 group-hover:border-[#d6ad4b]/60">
                    <img src="{{ asset('images/welcome/logo_PMJ.png') }}" alt="Logo Polda Metro Jaya" class="size-full object-contain">
                </span>
                <span class="grid h-20 w-20 place-items-center rounded-2xl border border-white/10 bg-white/95 p-2.5 shadow-lg shadow-black/20 transition duration-300 group-hover:-translate-y-1 group-hover:border-[#d6ad4b]/60">
                    <img src="{{ asset('images/welcome/logo_LOGISTIK_PMJ.png') }}" alt="Logo Logistik Polda Metro Jaya" class="size-full object-contain">
                </span>
            </a>

            {{ $slot }}
        </div>
    </section>

    <p class="mt-5 text-center text-xs tracking-wide text-white/35">&copy; {{ now()->year }} E-SPPG POLRI Cengkareng</p>
</x-layouts::auth.login>
