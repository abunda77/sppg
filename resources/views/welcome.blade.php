<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Satuan Pelayanan Pemenuhan Gizi POLRI Cengkareng, Kota Tangerang.">

        <title>E-SPPG POLRI Cengkareng</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#f7f8f3] font-sans text-[#18392c] antialiased selection:bg-[#e3b84c] selection:text-[#17382a]">
        <header class="sticky top-0 z-50 border-b border-[#17382a]/10 bg-white/95 backdrop-blur">
            <div class="mx-auto flex h-20 max-w-7xl items-center justify-between gap-6 px-5 sm:px-8 lg:px-10">
                <a href="#home" class="flex items-center gap-3" aria-label="Kembali ke beranda">
                    <img src="{{ asset('images/welcome/logo.png') }}" alt="Logo E-SPPG POLRI Cengkareng" class="size-10 rounded-full object-cover">
                    <span class="text-lg font-bold tracking-[-0.04em] text-[#17382a]">E-SPPG POLRI Cengkareng</span>
                </a>

                <nav class="hidden items-center gap-7 text-sm font-medium text-[#365646] lg:flex" aria-label="Navigasi utama">
                    <a href="#home" class="border-b-2 border-[#d7ad43] py-7 text-[#17382a]">Home</a>
                    <a data-placeholder-link="organogram" href="#" class="py-7 transition-colors hover:text-[#17382a]">Organogram</a>
                    <a data-placeholder-link="galeri-sop" href="#" class="py-7 transition-colors hover:text-[#17382a]">Galeri SOP</a>
                    <a data-placeholder-link="aplikasi-simpel" href="#" class="py-7 transition-colors hover:text-[#17382a]">Aplikasi SIMPEL</a>
                </nav>

                <div class="hidden items-center gap-3 sm:flex">
                    <a href="{{ route('login') }}" class="rounded-full px-4 py-2 text-sm font-semibold text-[#1d5a3c] transition-colors hover:bg-[#eef4ee]">Login</a>
                    <a href="{{ route('register') }}" class="rounded-full bg-[#1d5a3c] px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-[#174b32]">Register</a>
                </div>

                <details class="group relative lg:hidden">
                    <summary class="flex size-11 cursor-pointer list-none items-center justify-center rounded-full border border-[#17382a]/15 text-[#17382a] [&::-webkit-details-marker]:hidden" aria-label="Buka menu">
                        <svg class="size-5 group-open:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg class="hidden size-5 group-open:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                    </summary>
                    <div class="absolute right-0 mt-3 w-64 rounded-2xl border border-[#17382a]/10 bg-white p-3 shadow-xl">
                        <nav class="grid text-sm font-medium" aria-label="Navigasi seluler">
                            <a href="#home" class="rounded-xl px-4 py-3 hover:bg-[#eef4ee]">Home</a>
                            <a href="#" class="rounded-xl px-4 py-3 hover:bg-[#eef4ee]">Organogram</a>
                            <a href="#" class="rounded-xl px-4 py-3 hover:bg-[#eef4ee]">Galeri SOP</a>
                            <a href="#" class="rounded-xl px-4 py-3 hover:bg-[#eef4ee]">Aplikasi SIMPEL</a>
                            <span class="my-2 h-px bg-[#17382a]/10"></span>
                            <a href="{{ route('login') }}" class="rounded-xl px-4 py-3 hover:bg-[#eef4ee]">Login</a>
                            <a href="{{ route('register') }}" class="rounded-xl bg-[#1d5a3c] px-4 py-3 text-white">Register</a>
                        </nav>
                    </div>
                </details>
            </div>
        </header>

        <main id="home">
            <section class="relative isolate flex min-h-[calc(100svh-5rem)] items-end overflow-hidden bg-[#17382a]">
                <img src="{{ asset('images/welcome/hero.jpg') }}" alt="Dapur pelayanan gizi E-SPPG POLRI Cengkareng" class="absolute inset-0 -z-20 size-full object-cover">
                <div class="absolute inset-0 -z-10 bg-linear-to-r from-[#102f23]/95 via-[#17382a]/75 to-[#17382a]/15"></div>
                <div class="mx-auto w-full max-w-7xl px-5 pb-16 pt-28 sm:px-8 sm:pb-20 lg:px-10 lg:pb-24">
                    <div class="max-w-3xl motion-safe:animate-[welcome-in_.8s_ease-out_both]">
                        <p class="mb-5 flex items-center gap-3 text-xs font-semibold tracking-[0.22em] text-[#f1cf73] uppercase">
                            <span class="h-px w-10 bg-[#f1cf73]"></span>
                            Satuan Pelayanan Pemenuhan Gizi
                        </p>
                        <h1 class="text-6xl leading-[0.88] font-bold tracking-[-0.075em] text-white sm:text-7xl lg:text-[7.5rem]">E-SPPG<br>POLRI Cengkareng</h1>
                        <!-- <p class="mt-8 max-w-xl text-base leading-7 text-white/85 sm:text-lg">Jl. Ks. Tubun nomor 1 Pasar Baru Karawaci Kota Tangerang Banten.</p> -->
                    </div>
                </div>
                <a href="#profil" class="absolute right-5 bottom-6 grid size-12 place-items-center rounded-full border border-white/35 text-white transition-transform hover:translate-y-1 sm:right-8 lg:right-10" aria-label="Lihat profil E-SPPG">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 5v14m-6-6 6 6 6-6"/></svg>
                </a>
            </section>

            <section id="profil" class="bg-white py-20 sm:py-28">
                <div class="mx-auto grid max-w-7xl gap-10 px-5 sm:px-8 lg:grid-cols-[0.8fr_1.2fr] lg:gap-20 lg:px-10">
                    <div>
                        <p class="text-xs font-semibold tracking-[0.2em] text-[#9a6d11] uppercase">Tentang Kami</p>
                        <h2 class="mt-5 max-w-lg text-4xl leading-tight font-semibold tracking-[-0.045em] text-[#17382a] sm:text-5xl">Satuan Pelayanan Pemenuhan Gizi (E-SPPG)</h2>
                    </div>
                    <div class="flex flex-col justify-end gap-7 text-base leading-8 text-[#527061] sm:text-lg">
                        <p>Satuan Pelayanan Pemenuhan Gizi (E-SPPG) adalah jaringan dapur dan unit layanan terpusat yang didirikan di bawah program Makan Bergizi Gratis (MBG) Presiden Prabowo Subianto. Dikelola oleh Badan Gizi Nasional (BGN), unit-unit ini berfungsi sebagai infrastruktur utama untuk menyiapkan dan mendistribusikan makanan bergizi di seluruh Indonesia.</p>
                        <p class="border-l-2 border-[#d7ad43] pl-6 font-medium text-[#254b39]">Program prioritas yang bertujuan meningkatkan gizi dan kesehatan generasi muda Indonesia untuk menciptakan generasi emas.</p>
                    </div>
                </div>
            </section>

            <section aria-labelledby="layanan-heading" class="bg-[#f7f8f3] py-20 sm:py-28">
                <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">
                    <div class="mb-14 flex flex-col justify-between gap-5 border-b border-[#17382a]/15 pb-8 sm:flex-row sm:items-end">
                        <div>
                            <p class="text-xs font-semibold tracking-[0.2em] text-[#9a6d11] uppercase">Program MBG</p>
                            <h2 id="layanan-heading" class="mt-4 text-4xl font-semibold tracking-[-0.045em] text-[#17382a] sm:text-5xl">Pelayanan kami</h2>
                        </div>
                        <p class="max-w-md text-sm leading-6 text-[#60786c]">Dari perencanaan menu hingga distribusi, setiap tahap dijalankan untuk menjaga mutu dan keamanan pangan.</p>
                    </div>

                    <div class="grid gap-16 sm:gap-24">
                        <article class="grid items-center gap-8 lg:grid-cols-2 lg:gap-16">
                            <div class="group relative aspect-[4/3] overflow-hidden bg-[#dce6dc]">
                                <img src="{{ asset('images/welcome/menu.jpg') }}" alt="Menu Makan Bergizi Gratis" class="size-full object-cover transition duration-700 group-hover:scale-105">
                                <span class="absolute top-5 left-5 grid size-11 place-items-center rounded-full bg-white text-sm font-semibold text-[#17382a]">01</span>
                            </div>
                            <div class="lg:pr-10">
                                <h3 class="text-3xl font-semibold tracking-[-0.04em] text-[#17382a] sm:text-4xl">Menu MBG</h3>
                                <p class="mt-6 text-base leading-8 text-[#577165]">Menu makanan bergizi yang disajikan dalam program nasional Makan Bergizi Gratis (MBG) oleh pemerintah Indonesia, bertujuan meningkatkan gizi anak sekolah, balita, ibu hamil, dan menyusui dengan makanan seimbang sesuai pedoman “Isi Piringku”. Menu memanfaatkan bahan pangan lokal seperti nasi, lauk pauk, sayuran, buah, dan susu agar tumbuh kembang optimal serta mengurangi stunting.</p>
                            </div>
                        </article>

                        <article class="grid items-center gap-8 lg:grid-cols-2 lg:gap-16">
                            <div class="group relative aspect-[4/3] overflow-hidden bg-[#e8dfd0] lg:order-2">
                                <img src="{{ asset('images/welcome/delivery.jpg') }}" alt="Proses distribusi makanan MBG" class="size-full object-cover transition duration-700 group-hover:scale-105">
                                <span class="absolute top-5 left-5 grid size-11 place-items-center rounded-full bg-white text-sm font-semibold text-[#17382a]">02</span>
                            </div>
                            <div class="lg:pl-10">
                                <h3 class="text-3xl font-semibold tracking-[-0.04em] text-[#17382a] sm:text-4xl">Delivery MBG</h3>
                                <p class="mt-6 text-base leading-8 text-[#577165]">Proses delivery MBG melibatkan pengiriman paket makanan dari dapur produksi atau Satuan Pelayanan Pemenuhan Gizi (E-SPPG) ke titik distribusi atau langsung ke rumah penerima manfaat, terutama saat libur sekolah atau dalam skema distribusi khusus. Pengiriman memastikan makanan tiba tepat waktu, dalam kondisi aman, dan higienis.</p>
                            </div>
                        </article>

                        <article class="grid items-center gap-8 lg:grid-cols-2 lg:gap-16">
                            <div class="group relative aspect-[4/3] overflow-hidden bg-[#d8e3e4]">
                                <img src="{{ asset('images/welcome/quality.jpg') }}" alt="Pemeriksaan kualitas makanan MBG" class="size-full object-cover transition duration-700 group-hover:scale-105">
                                <span class="absolute top-5 left-5 grid size-11 place-items-center rounded-full bg-white text-sm font-semibold text-[#17382a]">03</span>
                            </div>
                            <div class="lg:pr-10">
                                <h3 class="text-3xl font-semibold tracking-[-0.04em] text-[#17382a] sm:text-4xl">Quality Control</h3>
                                <p class="mt-6 text-base leading-8 text-[#577165]">Program Makan Bergizi Gratis (MBG) adalah program strategis nasional untuk meningkatkan kualitas sumber daya manusia Indonesia melalui pemenuhan gizi anak-anak sekolah, balita, serta ibu hamil dan menyusui. Proses Quality Control (QC) yang ketat diterapkan di setiap tahap, mulai dari hulu atau penyediaan bahan baku hingga hilir atau distribusi makanan siap saji.</p>
                            </div>
                        </article>
                    </div>
                </div>
            </section>
        </main>

        <footer class="bg-[#123326] text-white">
            <div class="mx-auto grid max-w-7xl gap-10 px-5 py-14 sm:px-8 md:grid-cols-2 md:items-end lg:px-10">
                <div>
                    <p class="text-3xl font-semibold tracking-[-0.045em]">E-SPPG POLRI Cengkareng</p>
                    <!-- <p class="mt-4 max-w-md text-sm leading-6 text-white/65">Jl. Ks. Tubun nomor 1 Pasar Baru Karawaci Kota Tangerang Banten.</p> -->
                </div>
                <div class="flex flex-col gap-5 md:items-end">
                    <!-- <div class="flex gap-4 text-sm font-medium">
                        <a href="{{ route('login') }}" class="hover:text-[#f1cf73]">Login</a>
                        <a href="{{ route('register') }}" class="hover:text-[#f1cf73]">Register</a>
                    </div> -->
                    <p class="text-xs tracking-wide text-white/55">© Copyright E-SPPG POLRI Cengkareng 2025 All rights reserved</p>
                </div>
            </div>
        </footer>

        <style>
            @keyframes welcome-in {
                from { opacity: 0; transform: translateY(24px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </body>
</html>
