<?php

use App\Models\BahanPangan;
use Flux\Flux;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

new #[Title('Bahan Pangan')] class extends Component {
    use WithFileUploads, WithPagination;

    public string $search = '';

    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public bool $showImportModal = false;

    /** @var array<int, int|string> */
    public array $selectedIds = [];

    public int $editingId = 0;

    public string $createNama = '';

    public string $createDeskripsi = '';

    public string $createTkpi = '';

    public string $createOlahan = '';

    public $createImage;

    public string $editNama = '';

    public string $editDeskripsi = '';

    public string $editTkpi = '';

    public string $editOlahan = '';

    public $editImage;

    public $importFile;

    public ?string $existingImage = null;

    public function updatedSearch(): void
    {
        $this->selectedIds = [];
        $this->resetPage();
    }

    public function updatedPaginators(int $page, string $pageName): void
    {
        $this->selectedIds = [];
    }

    #[Computed]
    public function bahanPangans(): LengthAwarePaginator
    {
        return $this->filteredBahanPanganQuery()
            ->orderByDesc('id')
            ->paginate(10);
    }

    #[Computed]
    public function pageIsSelected(): bool
    {
        $pageIds = $this->currentPageIds();

        return $pageIds !== [] && array_diff($pageIds, $this->normalizedSelectedIds()) === [];
    }

    public function openCreateModal(): void
    {
        $this->reset(['createNama', 'createDeskripsi', 'createTkpi', 'createOlahan', 'createImage']);
        $this->showCreateModal = true;
    }

    public function openImportModal(): void
    {
        $this->reset(['importFile']);
        $this->resetErrorBag('importFile');
        $this->showImportModal = true;
    }

    public function importCsv(): void
    {
        $this->validate([
            'importFile' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $stream = fopen($this->importFile->getRealPath(), 'rb');

        if ($stream === false) {
            $this->addError('importFile', __('File CSV tidak dapat dibaca.'));

            return;
        }

        try {
            $header = fgetcsv($stream, escape: '');

            if (is_array($header) && isset($header[0])) {
                $header[0] = str($header[0])->replaceStart("\xEF\xBB\xBF", '')->toString();
            }

            if ($header !== ['nama', 'deskripsi', 'tkpi', 'olahan']) {
                $this->addError('importFile', __('Header CSV harus: nama,deskripsi,tkpi,olahan.'));

                return;
            }

            $rows = [];
            $line = 1;

            while (($values = fgetcsv($stream, escape: '')) !== false) {
                $line++;

                if ($values === [null] || $values === []) {
                    continue;
                }

                if (count($values) !== count($header)) {
                    $this->addError('importFile', __('Baris :line harus memiliki empat kolom.', ['line' => $line]));

                    return;
                }

                $row = array_combine($header, array_map(fn ($value) => trim((string) $value), $values));
                $validator = Validator::make($row, [
                    'nama' => ['required', 'string', 'max:255'],
                    'deskripsi' => ['nullable', 'string'],
                    'tkpi' => ['nullable', 'string', 'max:255'],
                    'olahan' => ['nullable', 'string', 'max:255'],
                ]);

                if ($validator->fails()) {
                    $this->addError('importFile', __('Baris :line tidak valid: :message', [
                        'line' => $line,
                        'message' => $validator->errors()->first(),
                    ]));

                    return;
                }

                $validated = $validator->validated();
                $rows[] = [
                    'nama' => $validated['nama'],
                    'deskripsi' => filled($validated['deskripsi'] ?? null) ? $validated['deskripsi'] : null,
                    'tkpi' => filled($validated['tkpi'] ?? null) ? $validated['tkpi'] : null,
                    'olahan' => filled($validated['olahan'] ?? null) ? $validated['olahan'] : null,
                ];
            }
        } finally {
            fclose($stream);
        }

        DB::transaction(function () use ($rows): void {
            foreach ($rows as $row) {
                BahanPangan::query()->updateOrCreate(
                    ['nama' => $row['nama']],
                    $row,
                );
            }
        });

        $this->showImportModal = false;
        $this->reset(['importFile']);

        Flux::toast(__('Data bahan pangan berhasil diimpor.'));
    }

    public function exportCsv(): StreamedResponse
    {
        $items = $this->filteredBahanPanganQuery()
            ->orderBy('id')
            ->get(['nama', 'deskripsi', 'tkpi', 'olahan']);

        return response()->streamDownload(function () use ($items): void {
            $stream = fopen('php://output', 'wb');

            if ($stream === false) {
                return;
            }

            fputcsv($stream, ['nama', 'deskripsi', 'tkpi', 'olahan'], escape: '');

            foreach ($items as $item) {
                fputcsv($stream, [
                    $item->nama,
                    $item->deskripsi,
                    $item->tkpi,
                    $item->olahan,
                ], escape: '');
            }

            fclose($stream);
        }, 'bahan-pangan.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function togglePageSelection(): void
    {
        $pageIds = $this->currentPageIds();
        $selectedIds = $this->normalizedSelectedIds();

        if ($pageIds !== [] && array_diff($pageIds, $selectedIds) === []) {
            $this->selectedIds = array_values(array_diff($selectedIds, $pageIds));

            return;
        }

        $this->selectedIds = array_values(array_unique([...$selectedIds, ...$pageIds]));
    }

    public function bulkDelete(): void
    {
        $selectedIds = $this->normalizedSelectedIds();

        if ($selectedIds === []) {
            return;
        }

        $items = BahanPangan::query()->whereKey($selectedIds)->get();

        $imagePaths = $items->pluck('image')->filter()->all();

        DB::transaction(function () use ($items): void {
            foreach ($items as $item) {
                $item->delete();
            }
        });

        Storage::disk('public')->delete($imagePaths);

        $this->selectedIds = [];

        Flux::toast(__('Bahan pangan terpilih berhasil dihapus.'));
    }

    public function createBahanPangan(): void
    {
        $this->validate([
            'createNama' => ['required', 'string', 'max:255'],
            'createDeskripsi' => ['nullable', 'string'],
            'createTkpi' => ['nullable', 'string', 'max:255'],
            'createOlahan' => ['nullable', 'string', 'max:255'],
            'createImage' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = [
            'nama' => $this->createNama,
            'deskripsi' => $this->createDeskripsi,
            'tkpi' => $this->createTkpi,
            'olahan' => $this->createOlahan,
        ];

        if ($this->createImage) {
            $data['image'] = $this->createImage->store('bahan-pangan', 'public');
        }

        BahanPangan::create($data);

        $this->showCreateModal = false;
        $this->reset(['createNama', 'createDeskripsi', 'createTkpi', 'createOlahan', 'createImage']);

        Flux::toast(__('Bahan pangan berhasil ditambahkan.'));
    }

    public function editBahanPangan(int $id): void
    {
        $item = BahanPangan::findOrFail($id);
        $this->editingId = $item->id;
        $this->editNama = $item->nama;
        $this->editDeskripsi = $item->deskripsi ?? '';
        $this->editTkpi = $item->tkpi ?? '';
        $this->editOlahan = $item->olahan ?? '';
        $this->editImage = null;
        $this->existingImage = $item->image;
        $this->showEditModal = true;
    }

    public function updateBahanPangan(): void
    {
        $this->validate([
            'editNama' => ['required', 'string', 'max:255'],
            'editDeskripsi' => ['nullable', 'string'],
            'editTkpi' => ['nullable', 'string', 'max:255'],
            'editOlahan' => ['nullable', 'string', 'max:255'],
            'editImage' => ['nullable', 'image', 'max:2048'],
        ]);

        $item = BahanPangan::findOrFail($this->editingId);

        $data = [
            'nama' => $this->editNama,
            'deskripsi' => $this->editDeskripsi,
            'tkpi' => $this->editTkpi,
            'olahan' => $this->editOlahan,
        ];

        if ($this->editImage) {
            if ($item->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $this->editImage->store('bahan-pangan', 'public');
        }

        $item->update($data);

        $this->showEditModal = false;
        $this->reset(['editingId', 'editNama', 'editDeskripsi', 'editTkpi', 'editOlahan', 'editImage', 'existingImage']);

        Flux::toast(__('Bahan pangan berhasil diperbarui.'));
    }

    public function deleteBahanPangan(int $id): void
    {
        $item = BahanPangan::findOrFail($id);

        if ($item->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        Flux::toast(__('Bahan pangan berhasil dihapus.'));
    }

    protected function filteredBahanPanganQuery(): Builder
    {
        return BahanPangan::query()
            ->when($this->search, fn (Builder $query) => $query->where(function (Builder $query): void {
                $query->where('nama', 'like', "%{$this->search}%")
                    ->orWhere('deskripsi', 'like', "%{$this->search}%")
                    ->orWhere('tkpi', 'like', "%{$this->search}%")
                    ->orWhere('olahan', 'like', "%{$this->search}%");
            }));
    }

    /** @return array<int, int> */
    protected function currentPageIds(): array
    {
        return $this->bahanPangans->getCollection()
            ->pluck('id')
            ->map(fn (int $id): int => $id)
            ->all();
    }

    /** @return array<int, int> */
    protected function normalizedSelectedIds(): array
    {
        return collect($this->selectedIds)
            ->map(fn ($id): int => (int) $id)
            ->filter(fn (int $id): bool => $id > 0)
            ->unique()
            ->values()
            ->all();
    }
}; ?>

<section class="w-full">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Bahan Pangan') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Kelola data bahan pangan dan komposisi TKPI') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex-1 self-stretch">
        <flux:heading>{{ __('Daftar Bahan Pangan') }}</flux:heading>
        <flux:subheading>{{ __('Data bahan pangan beserta informasi TKPI dan olahan') }}</flux:subheading>

        <div class="mt-5 w-full">
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Cari bahan pangan...') }}" icon="magnifying-glass" class="max-w-sm" />
                <div class="flex flex-wrap items-center gap-2">
                    <flux:button
                        variant="danger"
                        icon="trash"
                        wire:click="bulkDelete"
                        wire:confirm="{{ __('Yakin ingin menghapus semua bahan pangan terpilih?') }}"
                        :disabled="count($selectedIds) === 0"
                    >
                        {{ __('Hapus Terpilih') }}
                        @if (count($selectedIds) > 0)
                            ({{ count($selectedIds) }})
                        @endif
                    </flux:button>
                    <flux:button wire:click="openImportModal" icon="arrow-up-tray">
                        {{ __('Import CSV') }}
                    </flux:button>
                    <flux:button wire:click="exportCsv" icon="arrow-down-tray">
                        {{ __('Export CSV') }}
                    </flux:button>
                    <flux:button variant="primary" wire:click="openCreateModal" icon="plus">
                        {{ __('Tambah Bahan Pangan') }}
                    </flux:button>
                </div>
            </div>

            <flux:table>
                <flux:table.columns>
                    <flux:table.column>
                        <flux:checkbox
                            wire:click="togglePageSelection"
                            :checked="$this->pageIsSelected"
                            aria-label="{{ __('Pilih semua pada halaman ini') }}"
                        />
                    </flux:table.column>
                    <flux:table.column>{{ __('Nama') }}</flux:table.column>
                    <flux:table.column>{{ __('Deskripsi') }}</flux:table.column>
                    <flux:table.column>{{ __('TKPI') }}</flux:table.column>
                    <flux:table.column>{{ __('Olahan') }}</flux:table.column>
                    <flux:table.column>{{ __('Gambar') }}</flux:table.column>
                    <flux:table.column>{{ __('Aksi') }}</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($this->bahanPangans as $item)
                        <flux:table.row :wire:key="$item->id">
                            <flux:table.cell>
                                <flux:checkbox
                                    wire:model.live="selectedIds"
                                    value="{{ $item->id }}"
                                    aria-label="{{ __('Pilih :name', ['name' => $item->nama]) }}"
                                />
                            </flux:table.cell>
                            <flux:table.cell class="font-medium">{{ $item->nama }}</flux:table.cell>
                            <flux:table.cell class="max-w-xs truncate">{{ Str::limit($item->deskripsi, 50) }}</flux:table.cell>
                            <flux:table.cell>
                                @if ($item->tkpi)
                                    <flux:badge size="sm">{{ $item->tkpi }}</flux:badge>
                                @else
                                    <span class="text-zinc-400">-</span>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>{{ $item->olahan ?? '-' }}</flux:table.cell>
                            <flux:table.cell>
                                @if ($item->image)
                                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->nama }}" class="h-10 w-10 rounded object-cover">
                                @else
                                    <span class="text-zinc-400">-</span>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="flex gap-2">
                                    <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="editBahanPangan({{ $item->id }})" />
                                    <flux:button size="sm" variant="ghost" icon="trash" wire:click="deleteBahanPangan({{ $item->id }})" wire:confirm="{{ __('Yakin ingin menghapus bahan pangan ini?') }}" />
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>

            <div class="mt-4">
                {{ $this->bahanPangans->links() }}
            </div>
        </div>
    </div>

    <flux:modal wire:model="showCreateModal" class="w-full max-w-lg">
        <div class="space-y-6">
            <flux:heading size="lg">{{ __('Tambah Bahan Pangan') }}</flux:heading>

            <form wire:submit="createBahanPangan" class="space-y-4">
                <flux:field>
                    <flux:label>{{ __('Nama Bahan Pangan') }}</flux:label>
                    <flux:input wire:model="createNama" />
                    <flux:error name="createNama" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Deskripsi') }}</flux:label>
                    <flux:textarea wire:model="createDeskripsi" rows="3" />
                    <flux:error name="createDeskripsi" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Kode TKPI') }}</flux:label>
                    <flux:input wire:model="createTkpi" placeholder="Contoh: A.001" />
                    <flux:error name="createTkpi" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Olahan') }}</flux:label>
                    <flux:input wire:model="createOlahan" placeholder="Contoh: Nasi, Bubur, Lontong" />
                    <flux:error name="createOlahan" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Gambar') }}</flux:label>
                    <input type="file" wire:model="createImage" accept="image/*" class="block w-full text-sm text-zinc-500 file:mr-4 file:rounded file:border-0 file:bg-zinc-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-zinc-700 hover:file:bg-zinc-200 dark:text-zinc-400 dark:file:bg-zinc-700 dark:file:text-zinc-300" />
                    <flux:error name="createImage" />
                    @if ($createImage)
                        <img src="{{ $createImage->temporaryUrl() }}" alt="Preview" class="mt-2 h-20 w-20 rounded object-cover">
                    @endif
                </flux:field>

                <div class="flex justify-end gap-2">
                    <flux:button wire:click="$set('showCreateModal', false)">{{ __('Batal') }}</flux:button>
                    <flux:button type="submit" variant="primary">{{ __('Simpan') }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <flux:modal wire:model="showImportModal" class="w-full max-w-lg">
        <div class="space-y-6">
            <div class="space-y-2">
                <flux:heading size="lg">{{ __('Import Bahan Pangan dari CSV') }}</flux:heading>
                <flux:subheading>
                    {{ __('Gunakan header: nama,deskripsi,tkpi,olahan. Data dengan nama yang sama akan diperbarui.') }}
                </flux:subheading>
            </div>

            <form wire:submit="importCsv" class="space-y-4">
                <flux:field>
                    <flux:label>{{ __('File CSV') }}</flux:label>
                    <input
                        type="file"
                        wire:model="importFile"
                        accept=".csv,text/csv,text/plain"
                        class="block w-full text-sm text-zinc-500 file:mr-4 file:rounded file:border-0 file:bg-zinc-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-zinc-700 hover:file:bg-zinc-200 dark:text-zinc-400 dark:file:bg-zinc-700 dark:file:text-zinc-300"
                    />
                    <flux:error name="importFile" />
                </flux:field>

                <div class="flex justify-end gap-2">
                    <flux:button type="button" wire:click="$set('showImportModal', false)">{{ __('Batal') }}</flux:button>
                    <flux:button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="importCsv,importFile">
                        <span wire:loading.remove wire:target="importCsv">{{ __('Import') }}</span>
                        <span wire:loading wire:target="importCsv">{{ __('Mengimpor...') }}</span>
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <flux:modal wire:model="showEditModal" class="w-full max-w-lg">
        <div class="space-y-6">
            <flux:heading size="lg">{{ __('Edit Bahan Pangan') }}</flux:heading>

            <form wire:submit="updateBahanPangan" class="space-y-4">
                <flux:field>
                    <flux:label>{{ __('Nama Bahan Pangan') }}</flux:label>
                    <flux:input wire:model="editNama" />
                    <flux:error name="editNama" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Deskripsi') }}</flux:label>
                    <flux:textarea wire:model="editDeskripsi" rows="3" />
                    <flux:error name="editDeskripsi" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Kode TKPI') }}</flux:label>
                    <flux:input wire:model="editTkpi" placeholder="Contoh: A.001" />
                    <flux:error name="editTkpi" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Olahan') }}</flux:label>
                    <flux:input wire:model="editOlahan" placeholder="Contoh: Nasi, Bubur, Lontong" />
                    <flux:error name="editOlahan" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Gambar') }}</flux:label>
                    <input type="file" wire:model="editImage" accept="image/*" class="block w-full text-sm text-zinc-500 file:mr-4 file:rounded file:border-0 file:bg-zinc-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-zinc-700 hover:file:bg-zinc-200 dark:text-zinc-400 dark:file:bg-zinc-700 dark:file:text-zinc-300" />
                    <flux:error name="editImage" />
                    @if ($editImage)
                        <img src="{{ $editImage->temporaryUrl() }}" alt="Preview" class="mt-2 h-20 w-20 rounded object-cover">
                    @elseif ($existingImage)
                        <img src="{{ Storage::url($existingImage) }}" alt="Current" class="mt-2 h-20 w-20 rounded object-cover">
                    @endif
                </flux:field>

                <div class="flex justify-end gap-2">
                    <flux:button wire:click="$set('showEditModal', false)">{{ __('Batal') }}</flux:button>
                    <flux:button type="submit" variant="primary">{{ __('Simpan') }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</section>
