<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-[#f5efe2] text-stone-900 antialiased dark:bg-[#111714] dark:text-zinc-100">
        <flux:sidebar
            sticky
            collapsible="mobile"
            class="border-e-0 bg-[#1f3027] text-[#f3ecdf] dark:bg-[#0c130f]"
        >
            <flux:sidebar.header class="min-h-0 px-2 pt-3">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-2xl px-3 py-3" wire:navigate>
                    <span class="flex size-11 items-center justify-center rounded-2xl bg-[#f0e4c8] text-[#1f3027] shadow-sm dark:bg-[#d7c69d]">
                        <x-app-logo-icon class="size-6 fill-current" />
                    </span>

                    <div class="min-w-0 flex-1 in-data-flux-sidebar-collapsed-desktop:hidden">
                        <p class="truncate text-lg font-black tracking-[0.18em] text-white uppercase">
                            SIDS
                        </p>
                        <p class="truncate text-xs text-[#bfd0be]">
                            E-SPPG Polri Cengkareng
                        </p>
                    </div>
                </a>

                <flux:sidebar.collapse class="lg:hidden text-white/70 hover:text-white" />
            </flux:sidebar.header>

            <flux:sidebar.nav class="mt-4">
                <flux:sidebar.group heading="Ringkasan" class="grid">
                    <flux:sidebar.item
                        icon="home"
                        :href="route('dashboard')"
                        :current="request()->routeIs('dashboard')"
                        wire:navigate
                        class="rounded-2xl! border border-transparent! text-[#d9e1d8]! hover:bg-white/6! hover:text-white! data-current:border-[#de8b39]/20! data-current:bg-[#cf7d2f]! data-current:text-white!"
                    >
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group heading="Modul Inti" class="mt-4 grid">
                    <flux:sidebar.item
                        icon="cube"
                        :href="route('bahan-pangan.index')"
                        :current="request()->routeIs('bahan-pangan.*')"
                        wire:navigate
                        class="rounded-2xl! border border-transparent! text-[#d9e1d8]! hover:bg-white/6! hover:text-white! data-current:border-[#de8b39]/20! data-current:bg-[#cf7d2f]! data-current:text-white!"
                    >
                        {{ __('Bahan Pangan') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                @role('super_admin')
                    <flux:sidebar.group heading="Administrasi" class="mt-4 grid">
                        <flux:sidebar.item
                            icon="users"
                            :href="route('admin.users')"
                            :current="request()->routeIs('admin.users')"
                            wire:navigate
                            class="rounded-2xl! border border-transparent! text-[#d9e1d8]! hover:bg-white/6! hover:text-white! data-current:border-[#de8b39]/20! data-current:bg-[#cf7d2f]! data-current:text-white!"
                        >
                            {{ __('Users') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item
                            icon="shield-check"
                            :href="route('admin.roles')"
                            :current="request()->routeIs('admin.roles')"
                            wire:navigate
                            class="rounded-2xl! border border-transparent! text-[#d9e1d8]! hover:bg-white/6! hover:text-white! data-current:border-[#de8b39]/20! data-current:bg-[#cf7d2f]! data-current:text-white!"
                        >
                            {{ __('Roles') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item
                            icon="key"
                            :href="route('admin.permissions')"
                            :current="request()->routeIs('admin.permissions')"
                            wire:navigate
                            class="rounded-2xl! border border-transparent! text-[#d9e1d8]! hover:bg-white/6! hover:text-white! data-current:border-[#de8b39]/20! data-current:bg-[#cf7d2f]! data-current:text-white!"
                        >
                            {{ __('Permissions') }}
                        </flux:sidebar.item>
                    </flux:sidebar.group>
                @endrole
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav class="rounded-[1.75rem] border border-white/8 bg-white/6 p-2 backdrop-blur-sm">
                <flux:sidebar.item
                    icon="cog-6-tooth"
                    :href="route('profile.edit')"
                    :current="request()->routeIs('profile.edit')"
                    wire:navigate
                    class="rounded-2xl! border border-transparent! text-[#d9e1d8]! hover:bg-white/6! hover:text-white!"
                >
                    {{ __('Pengaturan Akun') }}
                </flux:sidebar.item>

                <flux:sidebar.item
                    icon="book-open-text"
                    href="https://laravel.com/docs/starter-kits#livewire"
                    target="_blank"
                    class="rounded-2xl! border border-transparent! text-[#d9e1d8]! hover:bg-white/6! hover:text-white!"
                >
                    {{ __('Panduan Sistem') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <flux:header class="border-b border-stone-200/80 bg-white/85 px-4 py-3 backdrop-blur lg:hidden dark:border-white/5 dark:bg-[#101612]/90">
            <flux:sidebar.toggle class="text-stone-600 dark:text-zinc-300" icon="bars-2" inset="left" />

            <div class="ms-3 min-w-0">
                <p class="truncate font-serif text-lg font-black text-stone-900 dark:text-white">
                    Dashboard Operasional
                </p>
                <p class="truncate text-xs text-stone-500 dark:text-zinc-400">
                    {{ now()->translatedFormat('d F Y') }}
                </p>
            </div>

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                    class="rounded-full border border-stone-200 bg-stone-50 px-1 dark:border-white/10 dark:bg-white/5"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
