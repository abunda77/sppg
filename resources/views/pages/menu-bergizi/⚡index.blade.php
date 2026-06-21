<?php

use App\Models\MenuBergizi;
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

new #[Title('Menu Bergizi')] class extends Component {
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

    public $createImage;

    public string $editNama = '';

    public string $editDeskripsi = '';

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
    public function menuBergizis(): LengthAwarePaginator
    {
        return $this->filteredMenuBergiziQuery()
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
        $this->reset(['createNama', 'createDeskripsi', 'createImage']);
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

            if ($header !== ['nama', 'deskripsi']) {
                $this->addError('importFile', __('Header CSV harus: nama,deskripsi.'));

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
                    $this->addError('importFile', __('Baris :line harus memiliki dua kolom.', ['line' => $line]));

                    return;
                }

                $row = array_combine($header, array_map(fn ($value) => trim((string) $value), $values));
                $validator = Validator::make($row, [
                    'nama' => ['required', 'string', 'max:255'],
                    'deskripsi' => ['nullable', 'string'],
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
                ];
            }
        } finally {
            fclose($stream);
        }

        DB::transaction(function () use ($rows): void {
            foreach ($rows as $row) {
                MenuBergizi::query()->updateOrCreate(
                    ['nama' => $row['nama']],
                    $row,
                );
            }
        });

        $this->showImportModal = false;
        $this->reset(['importFile']);

        Flux::toast(__('Data menu bergizi berhasil diimpor.'));
    }

    public function exportCsv(): StreamedResponse
    {
        $items = $this->filteredMenuBergiziQuery()
            ->orderBy('id')
            ->get(['nama', 'deskripsi']);

        return response()->streamDownload(function () use ($items): void {
            $stream = fopen('php://output', 'wb');

            if ($stream === false) {
                return;
            }

            fputcsv($stream, ['nama', 'deskripsi'], escape: '');

            foreach ($items as $item) {
                fputcsv($stream, [
                    $item->nama,
                    $item->deskripsi,
                ], escape: '');
            }

            fclose($stream);
        }, 'menu-bergizi.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
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

        $items = MenuBergizi::query()->whereKey($selectedIds)->get();

        $imagePaths = $items->pluck('image')->filter()->all();

        DB::transaction(function () use ($items): void {
            foreach ($items as $item) {
                $item->delete();
            }
        });

        Storage::disk('public')->delete($imagePaths);

        $this->selectedIds = [];

        Flux::toast(__('Menu bergizi terpilih berhasil dihapus.'));
    }

    public function createMenuBergizi(): void
    {
        $this->validate([
            'createNama' => ['required', 'string', 'max:255'],
            'createDeskripsi' => ['nullable', 'string'],
            'createImage' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = [
            'nama' => $this->createNama,
            'deskripsi' => $this->createDeskripsi,
        ];

        if ($this->createImage) {
            $data['image'] = $this->createImage->store('menu-bergizi', 'public');
        }

        MenuBergizi::create($data);

        $this->showCreateModal = false;
        $this->reset(['createNama', 'createDeskripsi', 'createImage']);

        Flux::toast(__('Menu bergizi berhasil ditambahkan.'));
    }

    public function editMenuBergizi(int $id): void
    {
        $item = MenuBergizi::findOrFail($id);
        $this->editingId = $item->id;
        $this->editNama = $item->nama;
        $this->editDeskripsi = $item->deskripsi ?? '';
        $this->editImage = null;
        $this->existingImage = $item->image;
        $this->showEditModal = true;
    }

    public function updateMenuBergizi(): void
    {
        $this->validate([
            'editNama' => ['required', 'string', 'max:255'],
            'editDeskripsi' => ['nullable', 'string'],
            'editImage' => ['nullable', 'image', 'max:2048'],
        ]);

        $item = MenuBergizi::findOrFail($this->editingId);

        $data = [
            'nama' => $this->editNama,
            'deskripsi' => $this->editDeskripsi,
        ];

        if ($this->editImage) {
            if ($item->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $this->editImage->store('menu-bergizi', 'public');
        }

        $item->update($data);

        $this->showEditModal = false;
        $this->reset(['editingId', 'editNama', 'editDeskripsi', 'editImage', 'existingImage']);

        Flux::toast(__('Menu bergizi berhasil diperbarui.'));
    }

    public function deleteMenuBergizi(int $id): void
    {
        $item = MenuBergizi::findOrFail($id);

        if ($item->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        Flux::toast(__('Menu bergizi berhasil dihapus.'));
    }

    protected function filteredMenuBergiziQuery(): Builder
    {
        return MenuBergizi::query()
            ->when($this->search, fn (Builder $query) => $query->where(function (Builder $query): void {
                $query->where('nama', 'like', "%{$this->search}%")
                    ->orWhere('deskripsi', 'like', "%{$this->search}%");
            }));
    }

    /** @return array<int, int> */
    protected function currentPageIds(): array
    {
        return $this->menuBergizis->getCollection()
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
        <flux:heading size="xl" level="1">{{ __('Menu Bergizi') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Kelola data menu bergizi SPPG') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex-1 self-stretch">
        <flux:heading>{{ __('Daftar Menu Bergizi') }}</flux:heading>
        <flux:subheading>{{ __('Data menu bergizi beserta deskripsi dan gambar') }}</flux:subheading>

        <div class="mt-5 w-full">
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Cari menu bergizi...') }}" icon="magnifying-glass" class="max-w-sm" />
                <div class="flex flex-wrap items-center gap-2">
                    <flux:button
                        variant="danger"
                        icon="trash"
                        wire:click="bulkDelete"
                        wire:confirm="{{ __('Yakin ingin menghapus semua menu bergizi terpilih?') }}"
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
                        {{ __('Tambah Menu Bergizi') }}
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
                    <flux:table.column>{{ __('Gambar') }}</flux:table.column>
                    <flux:table.column>{{ __('Aksi') }}</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($this->menuBergizis as $item)
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
                                @if ($item->image)
                                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->nama }}" class="h-10 w-10 rounded object-cover">
                                @else
                                    <span class="text-zinc-400">-</span>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="flex gap-2">
                                    <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="editMenuBergizi({{ $item->id }})" />
                                    <flux:button size="sm" variant="ghost" icon="trash" wire:click="deleteMenuBergizi({{ $item->id }})" wire:confirm="{{ __('Yakin ingin menghapus menu bergizi ini?') }}" />
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>

            <div class="mt-4">
                {{ $this->menuBergizis->links() }}
            </div>
        </div>
    </div>

    <flux:modal wire:model="showCreateModal" class="w-full max-w-lg">
        <div class="space-y-6">
            <flux:heading size="lg">{{ __('Tambah Menu Bergizi') }}</flux:heading>

            <form wire:submit="createMenuBergizi" class="space-y-4">
                <flux:field>
                    <flux:label>{{ __('Nama Menu Bergizi') }}</flux:label>
                    <flux:input wire:model="createNama" />
                    <flux:error name="createNama" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Deskripsi') }}</flux:label>
                    <flux:textarea wire:model="createDeskripsi" rows="3" />
                    <flux:error name="createDeskripsi" />
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
                <flux:heading size="lg">{{ __('Import Menu Bergizi dari CSV') }}</flux:heading>
                <flux:subheading>
                    {{ __('Gunakan header: nama,deskripsi. Data dengan nama yang sama akan diperbarui.') }}
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
            <flux:heading size="lg">{{ __('Edit Menu Bergizi') }}</flux:heading>

            <form wire:submit="updateMenuBergizi" class="space-y-4">
                <flux:field>
                    <flux:label>{{ __('Nama Menu Bergizi') }}</flux:label>
                    <flux:input wire:model="editNama" />
                    <flux:error name="editNama" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Deskripsi') }}</flux:label>
                    <flux:textarea wire:model="editDeskripsi" rows="3" />
                    <flux:error name="editDeskripsi" />
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
