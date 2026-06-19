@props([
    'title',
    'description',
    'eyebrow' => 'Satuan Pelayanan Pemenuhan Gizi',
])

<div class="flex w-full flex-col text-center">
    <p class="text-[0.65rem] font-semibold tracking-[0.24em] text-[#d6ad4b] uppercase">{{ $eyebrow }}</p>
    <flux:heading size="xl" class="mt-3 text-white">{{ $title }}</flux:heading>
    <flux:subheading class="mt-2 leading-6">{{ $description }}</flux:subheading>
</div>
