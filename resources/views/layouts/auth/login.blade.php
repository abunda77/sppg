<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-[#070b09] font-sans text-white antialiased selection:bg-[#d6ad4b] selection:text-[#17120a]">
        <div class="relative isolate flex min-h-svh items-center justify-center overflow-hidden px-4 py-8 sm:px-6 sm:py-12">
            <div class="pointer-events-none absolute inset-0 -z-20 bg-[radial-gradient(circle_at_50%_-10%,rgba(214,173,75,0.15),transparent_34%),linear-gradient(180deg,#0c120f_0%,#070b09_100%)]"></div>
            <div class="pointer-events-none absolute -top-32 -left-32 -z-10 size-80 rounded-full bg-[#1d5a3c]/15 blur-3xl"></div>
            <div class="pointer-events-none absolute -right-40 -bottom-40 -z-10 size-96 rounded-full bg-[#d6ad4b]/8 blur-3xl"></div>

            <main class="mx-auto w-full max-w-md motion-safe:animate-[login-panel-in_.65s_cubic-bezier(.22,1,.36,1)_both]">
                {{ $slot }}
            </main>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts

        <style>
            @keyframes login-panel-in {
                from { opacity: 0; transform: translateY(18px) scale(.985); }
                to { opacity: 1; transform: translateY(0) scale(1); }
            }
        </style>
    </body>
</html>
