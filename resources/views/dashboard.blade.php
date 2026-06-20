@php
    $metrics = [
        [
            'label' => 'Target Porsi Hari Ini',
            'value' => '2.284',
            'detail' => 'Sesuai target distribusi wilayah Cengkareng.',
            'accent' => 'from-amber-500 to-orange-400',
        ],
        [
            'label' => 'Personil Bertugas',
            'value' => '45 / 46',
            'detail' => '97,8% kehadiran pada shift operasional.',
            'accent' => 'from-lime-600 to-emerald-500',
        ],
        [
            'label' => 'Kas Operasional',
            'value' => 'Rp 14,0jt',
            'detail' => '6 transaksi pengeluaran tercatat hari ini.',
            'accent' => 'from-teal-700 to-emerald-600',
        ],
        [
            'label' => 'PO Menunggu',
            'value' => '2',
            'detail' => 'Beras, telur, dan kemasan butuh konfirmasi.',
            'accent' => 'from-rose-600 to-orange-500',
        ],
    ];

    $menus = [
        [
            'name' => 'Nasi Ayam Telur Asin',
            'portion' => 'Porsi besar · SPPG-AP003',
            'calories' => '488 Kal',
            'tone' => 'from-orange-200 via-orange-300 to-amber-500',
        ],
        [
            'name' => 'Nasi Kuning, Telur Iris, Lalapan',
            'portion' => 'Porsi kecil · SPPG-AP008',
            'calories' => '433 Kal',
            'tone' => 'from-lime-300 via-orange-400 to-rose-400',
        ],
        [
            'name' => 'Ayam Tepung, Cap Cai, Tempe',
            'portion' => 'Porsi besar · SPPG-AP011',
            'calories' => '512 Kal',
            'tone' => 'from-orange-300 via-amber-400 to-lime-500',
        ],
    ];

    $productionSteps = [
        ['name' => 'Persiapan', 'time' => '04.00-05.00', 'state' => 'done'],
        ['name' => 'Pengolahan I', 'time' => '05.00-06.00', 'state' => 'done'],
        ['name' => 'Pengolahan II', 'time' => '06.00-08.00', 'state' => 'active'],
        ['name' => 'Pemorsian & Packing', 'time' => '08.00-09.00', 'state' => 'pending'],
        ['name' => 'Distribusi', 'time' => '09.00-10.00', 'state' => 'pending'],
    ];

    $stockItems = [
        ['name' => 'Beras', 'amount' => '228,4 kg', 'width' => '78%', 'color' => 'bg-lime-600 dark:bg-lime-500'],
        ['name' => 'Ayam fillet', 'amount' => '112,1 kg', 'width' => '54%', 'color' => 'bg-amber-500 dark:bg-amber-400'],
        ['name' => 'Tempe', 'amount' => '87,2 kg', 'width' => '31%', 'color' => 'bg-rose-500 dark:bg-rose-400'],
    ];

    $financialRows = [
        ['label' => 'Penerimaan rutin', 'value' => '+ Rp 50,0jt'],
        ['label' => 'Belanja pangan', 'value' => '- Rp 15,0jt'],
        ['label' => 'Belanja operasional', 'value' => '- Rp 18,0jt'],
        ['label' => 'Biaya langganan', 'value' => '- Rp 3,0jt'],
    ];

    $distributionRows = [
        ['name' => 'SDN Tangerang I', 'segment' => 'Sekolah Dasar', 'count' => '420'],
        ['name' => 'SMPN 1 Kota Tangerang', 'segment' => 'SMP', 'count' => '840'],
        ['name' => 'SMAN 1 Kota Tangerang', 'segment' => 'SMA', 'count' => '1.000'],
        ['name' => 'Posyandu Anggrek', 'segment' => 'Balita & Bumil', 'count' => '24'],
    ];
@endphp

<x-layouts::app :title="__('Dashboard Operasional')">
    <div data-dashboard-shell="operational" class="flex flex-1 flex-col gap-6">
        <section class="relative overflow-hidden rounded-[2rem] border border-stone-200/80 bg-linear-to-br from-stone-50 via-white to-amber-50 px-5 py-6 shadow-sm shadow-stone-200/40 dark:border-zinc-700/80 dark:from-zinc-900 dark:via-zinc-900 dark:to-emerald-950/60 dark:shadow-none sm:px-6 lg:px-8">
            <div class="pointer-events-none absolute inset-y-0 right-0 hidden w-72 bg-radial from-amber-200/45 via-transparent to-transparent dark:from-emerald-400/10 lg:block"></div>

            <div class="relative flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div class="max-w-3xl space-y-3">
                    <span class="inline-flex w-fit items-center rounded-full border border-emerald-800/10 bg-white/80 px-3 py-1 text-[0.7rem] font-semibold tracking-[0.22em] text-emerald-900 uppercase shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-emerald-100">
                        SPPG Polri Cengkareng
                    </span>

                    <div class="space-y-2">
                        <h1 class="font-serif text-3xl font-black tracking-tight text-stone-900 dark:text-stone-50 sm:text-4xl">
                            {{ __('Dashboard Operasional') }}
                        </h1>

                        <p class="max-w-2xl text-sm leading-6 text-stone-600 dark:text-zinc-300 sm:text-base">
                            {{ __('Ringkasan distribusi, stok, dan kesiapan layanan hari ini.') }}
                        </p>
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 lg:w-auto lg:min-w-[22rem]">
                    <div class="rounded-2xl border border-stone-200 bg-white/90 px-4 py-3 shadow-sm dark:border-white/10 dark:bg-white/5">
                        <p class="text-[0.7rem] font-semibold tracking-[0.2em] text-stone-500 uppercase dark:text-zinc-400">
                            Periode
                        </p>
                        <p class="mt-2 text-sm font-semibold text-stone-900 dark:text-white">
                            Juni 2026 · Minggu III
                        </p>
                    </div>

                    <div class="rounded-2xl border border-stone-200 bg-white/90 px-4 py-3 shadow-sm dark:border-white/10 dark:bg-white/5">
                        <p class="text-[0.7rem] font-semibold tracking-[0.2em] text-stone-500 uppercase dark:text-zinc-400">
                            Tanggal
                        </p>
                        <p class="mt-2 text-sm font-semibold text-stone-900 dark:text-white">
                            {{ now()->translatedFormat('l, d F Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-4 xl:grid-cols-4">
            @foreach ($metrics as $metric)
                <article class="rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm shadow-stone-200/50 dark:border-zinc-700 dark:bg-zinc-900/80 dark:shadow-none">
                    <div class="mb-4 h-1.5 w-full rounded-full bg-stone-100 dark:bg-white/5">
                        <div class="h-full w-28 rounded-full bg-linear-to-r {{ $metric['accent'] }}"></div>
                    </div>

                    <p class="text-sm font-medium text-stone-500 dark:text-zinc-400">
                        {{ $metric['label'] }}
                    </p>
                    <p class="mt-3 font-serif text-4xl font-black tracking-tight text-stone-900 dark:text-stone-50">
                        {{ $metric['value'] }}
                    </p>
                    <p class="mt-2 text-sm leading-6 text-stone-600 dark:text-zinc-300">
                        {{ $metric['detail'] }}
                    </p>
                </article>
            @endforeach
        </section>

        <section class="grid gap-4 xl:grid-cols-[minmax(0,1.75fr)_minmax(20rem,1fr)]">
            <article class="rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm shadow-stone-200/50 dark:border-zinc-700 dark:bg-zinc-900/80 dark:shadow-none sm:p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="font-serif text-2xl font-black tracking-tight text-stone-900 dark:text-stone-50">
                            {{ __('Menu Hari Ini') }}
                        </h2>
                        <p class="mt-1 text-sm text-stone-500 dark:text-zinc-400">
                            Komposisi menu harian dan kandungan gizi per porsi.
                        </p>
                    </div>

                    <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">
                        3 varian aktif
                    </span>
                </div>

                <div class="mt-6 space-y-4">
                    @foreach ($menus as $menu)
                        <div class="flex items-center gap-4 rounded-2xl border border-stone-100 px-4 py-4 transition hover:border-stone-200 hover:bg-stone-50/80 dark:border-white/5 dark:hover:border-white/10 dark:hover:bg-white/5">
                            <div class="size-14 shrink-0 rounded-2xl bg-linear-to-br {{ $menu['tone'] }}"></div>

                            <div class="min-w-0 flex-1">
                                <p class="truncate text-base font-semibold text-stone-900 dark:text-white">
                                    {{ $menu['name'] }}
                                </p>
                                <p class="mt-1 text-sm text-stone-500 dark:text-zinc-400">
                                    {{ $menu['portion'] }}
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="font-serif text-2xl font-black text-stone-900 dark:text-stone-50">
                                    {{ $menu['calories'] }}
                                </p>
                                <p class="text-xs tracking-[0.18em] text-stone-400 uppercase dark:text-zinc-500">
                                    per porsi
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm shadow-stone-200/50 dark:border-zinc-700 dark:bg-zinc-900/80 dark:shadow-none sm:p-6">
                <h2 class="font-serif text-2xl font-black tracking-tight text-stone-900 dark:text-stone-50">
                    {{ __('Tahapan Produksi') }}
                </h2>
                <p class="mt-1 text-sm text-stone-500 dark:text-zinc-400">
                    Monitoring tahap kerja dapur hingga distribusi.
                </p>

                <div class="mt-6 space-y-4">
                    @foreach ($productionSteps as $step)
                        @php
                            $dotClasses = match ($step['state']) {
                                'done' => 'bg-lime-600 ring-lime-200 dark:bg-lime-400 dark:ring-lime-400/20',
                                'active' => 'bg-amber-500 ring-amber-200 dark:bg-amber-400 dark:ring-amber-400/20',
                                default => 'bg-stone-200 ring-stone-100 dark:bg-zinc-600 dark:ring-zinc-700',
                            };

                            $textClasses = $step['state'] === 'pending'
                                ? 'text-stone-400 dark:text-zinc-500'
                                : 'text-stone-800 dark:text-zinc-100';
                        @endphp

                        <div class="flex items-center gap-3">
                            <span class="size-3 rounded-full ring-4 {{ $dotClasses }}"></span>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium {{ $textClasses }}">
                                    {{ $step['name'] }}
                                </p>
                            </div>
                            <span class="text-sm font-medium tracking-[0.16em] text-stone-400 uppercase dark:text-zinc-500">
                                {{ $step['time'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </article>
        </section>

        <section class="grid gap-4 xl:grid-cols-3">
            <article class="rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm shadow-stone-200/50 dark:border-zinc-700 dark:bg-zinc-900/80 dark:shadow-none sm:p-6">
                <h2 class="font-serif text-2xl font-black tracking-tight text-stone-900 dark:text-stone-50">
                    Stok bahan kritis
                </h2>

                <div class="mt-6 space-y-5">
                    @foreach ($stockItems as $item)
                        <div>
                            <div class="mb-2 flex items-center justify-between gap-3 text-sm">
                                <span class="font-medium text-stone-700 dark:text-zinc-200">{{ $item['name'] }}</span>
                                <span class="text-stone-500 dark:text-zinc-400">{{ $item['amount'] }}</span>
                            </div>
                            <div class="h-2.5 rounded-full bg-stone-100 dark:bg-white/5">
                                <div class="h-full rounded-full {{ $item['color'] }}" style="width: {{ $item['width'] }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm shadow-stone-200/50 dark:border-zinc-700 dark:bg-zinc-900/80 dark:shadow-none sm:p-6">
                <h2 class="font-serif text-2xl font-black tracking-tight text-stone-900 dark:text-stone-50">
                    {{ __('Ringkasan Keuangan') }}
                </h2>

                <div class="mt-6 space-y-4">
                    @foreach ($financialRows as $row)
                        <div class="flex items-center justify-between gap-4 border-b border-stone-100 pb-3 last:border-none last:pb-0 dark:border-white/5">
                            <span class="text-sm text-stone-500 dark:text-zinc-400">{{ $row['label'] }}</span>
                            <span class="font-medium text-stone-800 dark:text-zinc-100">{{ $row['value'] }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex items-center justify-between rounded-2xl border border-stone-200 bg-stone-50 px-4 py-4 dark:border-white/10 dark:bg-white/5">
                    <span class="font-semibold text-stone-800 dark:text-stone-50">Saldo kas</span>
                    <span class="font-serif text-3xl font-black text-stone-900 dark:text-stone-50">Rp 14,0jt</span>
                </div>
            </article>

            <article class="rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm shadow-stone-200/50 dark:border-zinc-700 dark:bg-zinc-900/80 dark:shadow-none sm:p-6">
                <h2 class="font-serif text-2xl font-black tracking-tight text-stone-900 dark:text-stone-50">
                    Distribusi penerima
                </h2>

                <div class="mt-6 space-y-4">
                    @foreach ($distributionRows as $row)
                        <div class="flex items-start gap-3 border-b border-stone-100 pb-4 last:border-none last:pb-0 dark:border-white/5">
                            <span class="mt-1 size-2.5 rounded-full bg-lime-600 dark:bg-lime-400"></span>

                            <div class="min-w-0 flex-1">
                                <p class="truncate font-medium text-stone-800 dark:text-zinc-100">
                                    {{ $row['name'] }}
                                </p>
                                <p class="text-sm text-stone-500 dark:text-zinc-400">
                                    {{ $row['segment'] }}
                                </p>
                            </div>

                            <span class="font-serif text-2xl font-black text-amber-700 dark:text-amber-300">
                                {{ $row['count'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </article>
        </section>
    </div>
</x-layouts::app>
