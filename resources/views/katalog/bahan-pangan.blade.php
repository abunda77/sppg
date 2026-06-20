<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Katalog Bahan Pangan - E-SPPG POLRI Cengkareng">

        <title>Katalog Bahan Pangan — E-SPPG POLRI Cengkareng</title>

        <link rel="icon" href="/favicon.png" type="image/png">
        <link rel="apple-touch-icon" href="/favicon.png">

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    </head>
    <body class="bg-[#f7f8f3] font-sans text-[#18392c] antialiased selection:bg-[#e3b84c] selection:text-[#17382a]">
        <header class="sticky top-0 z-50 border-b border-[#17382a]/10 bg-white/95 backdrop-blur">
            <div class="mx-auto flex h-20 max-w-7xl items-center justify-between gap-6 px-5 sm:px-8 lg:px-10">
                <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="Kembali ke beranda">
                    <img src="{{ asset('images/welcome/logo.png') }}" alt="Logo E-SPPG POLRI Cengkareng" class="size-10 rounded-full object-cover">
                    <span class="text-lg font-bold tracking-[-0.04em] text-[#17382a]">E-SPPG POLRI Cengkareng</span>
                </a>

                <div class="hidden items-center gap-3 sm:flex">
                    <a href="{{ route('home') }}" class="rounded-full px-4 py-2 text-sm font-semibold text-[#1d5a3c] transition-colors hover:bg-[#eef4ee]">Beranda</a>
                    <a href="{{ route('login') }}" class="rounded-full bg-[#1d5a3c] px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-[#174b32]">Login</a>
                </div>
            </div>
        </header>

        <main>
            <section class="bg-[#17382a] py-16 sm:py-20">
                <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">
                    <p class="mb-4 text-xs font-semibold tracking-[0.22em] text-[#f1cf73] uppercase">Katalog</p>
                    <h1 class="text-4xl font-bold tracking-[-0.045em] text-white sm:text-5xl">Bahan Pangan</h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-white/80">Daftar bahan pangan beserta informasi TKPI dan ragam olahan yang tersedia di E-SPPG POLRI Cengkareng.</p>
                </div>
            </section>

            <section class="py-16 sm:py-24">
                <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">
                    @if ($items->isEmpty())
                        <div class="rounded-2xl border border-dashed border-[#17382a]/15 py-20 text-center">
                            <p class="text-base text-[#60786c]">Belum ada data bahan pangan.</p>
                        </div>
                    @else
                        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($items as $item)
                                <article class="group flex flex-col overflow-hidden rounded-2xl border border-[#17382a]/10 bg-white transition-shadow hover:shadow-lg" x-data="{ open: false }">
                                    <div class="aspect-[4/3] overflow-hidden bg-[#eef4ee]">
                                        @if ($item->image)
                                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->nama }}" class="size-full object-cover transition duration-700 group-hover:scale-105">
                                        @else
                                            <img src="{{ asset('images/welcome/fallback.png') }}" alt="{{ $item->nama }}" class="size-full object-cover transition duration-700 group-hover:scale-105">
                                        @endif
                                    </div>
                                    <div class="flex flex-1 flex-col justify-between p-5 sm:p-6">
                                        <div>
                                            <button
                                                type="button"
                                                class="text-left text-lg font-semibold tracking-[-0.035em] text-[#17382a] transition-colors hover:text-[#d7ad43]"
                                                @click="open = true"
                                                @keydown.escape.window="open = false"
                                            >{{ $item->nama }}</button>
                                            @if ($item->tkpi)
                                                <span class="mt-2 inline-block rounded-full border border-[#d7ad43]/40 px-3 py-0.5 text-xs font-medium text-[#9a6d11]">TKPI {{ $item->tkpi }}</span>
                                            @endif
                                        </div>
                                        @if ($item->olahan)
                                            <p class="mt-3 text-sm leading-6 text-[#60786c]">Olahan: {{ $item->olahan }}</p>
                                        @endif
                                    </div>

                                    <div
                                        x-show="open"
                                        x-cloak
                                        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 px-4"
                                        x-transition.opacity
                                        @click.self="open = false"
                                    >
                                        <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl sm:p-8">
                                            <button type="button" class="absolute top-4 right-4 grid size-9 place-items-center rounded-full border border-[#17382a]/15 text-[#17382a] hover:bg-[#eef4ee]" @click="open = false" aria-label="Tutup popup">
                                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                                            </button>

                                            <h2 class="text-2xl font-bold tracking-[-0.04em] text-[#17382a]">{{ $item->nama }}</h2>

                                            @if ($item->tkpi)
                                                <span class="mt-3 inline-block rounded-full border border-[#d7ad43]/40 px-3 py-0.5 text-xs font-medium text-[#9a6d11]">TKPI {{ $item->tkpi }}</span>
                                            @endif

                                            @if ($item->deskripsi)
                                                <div class="mt-4 border-t border-[#17382a]/10 pt-4">
                                                    <p class="text-sm font-semibold tracking-[0.08em] text-[#9a6d11] uppercase">Deskripsi</p>
                                                    <p class="mt-2 text-base leading-7 text-[#527061]">{{ $item->deskripsi }}</p>
                                                </div>
                                            @endif

                                            @if ($item->olahan)
                                                <div class="mt-4 border-t border-[#17382a]/10 pt-4">
                                                    <p class="text-sm font-semibold tracking-[0.08em] text-[#9a6d11] uppercase">Olahan</p>
                                                    <p class="mt-2 text-base leading-7 text-[#527061]">{{ $item->olahan }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-12 text-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-full bg-[#eef4ee] px-6 py-3 text-sm font-semibold text-[#1d5a3c] transition-colors hover:bg-[#dce6dc]">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m19 12-7-7-7 7M5 19V5"/></svg>
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </section>
        </main>

        <footer class="bg-[#123326] text-white">
            <div class="mx-auto grid max-w-7xl gap-10 px-5 py-14 sm:px-8 md:grid-cols-2 md:items-end lg:px-10">
                <div>
                    <p class="text-3xl font-semibold tracking-[-0.045em]">E-SPPG POLRI Cengkareng</p>
                </div>
                <div class="flex flex-col gap-5 md:items-end">
                    <p class="text-xs tracking-wide text-white/55">© Copyright E-SPPG POLRI Cengkareng 2025 All rights reserved</p>
                </div>
            </div>
        </footer>

        @livewireScripts
    </body>
</html>
