<?php

use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Karyawan;
use Barryvdh\DomPDF\Facade\Pdf;
use Flux\Flux;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

new #[Title('Karyawan')] class extends Component {
    use WithFileUploads, WithPagination;

    public string $search = '';

    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public bool $showImportModal = false;

    /** @var array<int, int|string> */
    public array $selectedIds = [];

    public int $editingId = 0;

    public string $createNama = '';

    public string $createEmail = '';

    public int $createJabatanId = 0;

    public int $createDivisiId = 0;

    public string $createNoTelp = '';

    public string $editNama = '';

    public string $editEmail = '';

    public int $editJabatanId = 0;

    public int $editDivisiId = 0;

    public string $editNoTelp = '';

    public $importFile;

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
    public function karyawans(): LengthAwarePaginator
    {
        return $this->filteredKaryawanQuery()
            ->with(['jabatan', 'divisi'])
            ->orderByDesc('id')
            ->paginate(10);
    }

    #[Computed]
    public function jabatans()
    {
        return Jabatan::query()->orderBy('nama')->get();
    }

    #[Computed]
    public function divisis()
    {
        return Divisi::query()->orderBy('nama')->get();
    }

    #[Computed]
    public function pageIsSelected(): bool
    {
        $pageIds = $this->currentPageIds();

        return $pageIds !== [] && array_diff($pageIds, $this->normalizedSelectedIds()) === [];
    }

    public function openCreateModal(): void
    {
        $this->reset(['createNama', 'createEmail', 'createJabatanId', 'createDivisiId', 'createNoTelp']);
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

            if ($header !== ['nama', 'email', 'jabatan', 'divisi', 'no_telp']) {
                $this->addError('importFile', __('Header CSV harus: nama,email,jabatan,divisi,no_telp.'));

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
                    $this->addError('importFile', __('Baris :line harus memiliki lima kolom.', ['line' => $line]));

                    return;
                }

                $row = array_combine($header, array_map(fn ($value) => trim((string) $value), $values));

                $validator = Validator::make($row, [
                    'nama' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'email', 'max:255'],
                    'jabatan' => ['required', 'string', 'max:255'],
                    'divisi' => ['required', 'string', 'max:255'],
                    'no_telp' => ['required', 'string', 'max:255'],
                ]);

                if ($validator->fails()) {
                    $this->addError('importFile', __('Baris :line tidak valid: :message', [
                        'line' => $line,
                        'message' => $validator->errors()->first(),
                    ]));

                    return;
                }

                $validated = $validator->validated();

                $jabatan = Jabatan::where('nama', $validated['jabatan'])->first();
                $divisi = Divisi::where('nama', $validated['divisi'])->first();

                if (! $jabatan || ! $divisi) {
                    $this->addError('importFile', __('Baris :line: jabatan atau divisi tidak ditemukan.', ['line' => $line]));

                    return;
                }

                $rows[] = [
                    'nama' => $validated['nama'],
                    'email' => $validated['email'],
                    'jabatan_id' => $jabatan->id,
                    'divisi_id' => $divisi->id,
                    'no_telp' => $validated['no_telp'],
                ];
            }
        } finally {
            fclose($stream);
        }

        DB::transaction(function () use ($rows): void {
            foreach ($rows as $row) {
                Karyawan::query()->updateOrCreate(
                    ['email' => $row['email']],
                    $row,
                );
            }
        });

        $this->showImportModal = false;
        $this->reset(['importFile']);

        Flux::toast(__('Data karyawan berhasil diimpor.'));
    }

    public function exportCsv(): StreamedResponse
    {
        $items = $this->filteredKaryawanQuery()
            ->with(['jabatan', 'divisi'])
            ->orderBy('id')
            ->get(['nama', 'email', 'jabatan_id', 'divisi_id', 'no_telp']);

        return response()->streamDownload(function () use ($items): void {
            $stream = fopen('php://output', 'wb');

            if ($stream === false) {
                return;
            }

            fputcsv($stream, ['nama', 'email', 'jabatan', 'divisi', 'no_telp'], escape: '');

            foreach ($items as $item) {
                fputcsv($stream, [
                    $item->nama,
                    $item->email,
                    $item->jabatan?->nama,
                    $item->divisi?->nama,
                    $item->no_telp,
                ], escape: '');
            }

            fclose($stream);
        }, 'karyawan.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function exportPdf()
    {
        $items = $this->filteredKaryawanQuery()
            ->with(['jabatan', 'divisi'])
            ->orderBy('id')
            ->get(['nama', 'email', 'jabatan_id', 'divisi_id', 'no_telp']);

        $pdf = Pdf::loadView('pdfs.karyawan', ['karyawans' => $items]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'karyawan.pdf', ['Content-Type' => 'application/pdf']);
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

        Karyawan::query()->whereKey($selectedIds)->delete();

        $this->selectedIds = [];

        Flux::toast(__('Karyawan terpilih berhasil dihapus.'));
    }

    public function createKaryawan(): void
    {
        $this->validate([
            'createNama' => ['required', 'string', 'max:255'],
            'createEmail' => ['required', 'email', 'max:255', 'unique:karyawans,email'],
            'createJabatanId' => ['required', 'integer', 'exists:jabatans,id'],
            'createDivisiId' => ['required', 'integer', 'exists:divisis,id'],
            'createNoTelp' => ['required', 'string', 'max:255'],
        ]);

        Karyawan::create([
            'nama' => $this->createNama,
            'email' => $this->createEmail,
            'jabatan_id' => $this->createJabatanId,
            'divisi_id' => $this->createDivisiId,
            'no_telp' => $this->createNoTelp,
        ]);

        $this->showCreateModal = false;
        $this->reset(['createNama', 'createEmail', 'createJabatanId', 'createDivisiId', 'createNoTelp']);

        Flux::toast(__('Karyawan berhasil ditambahkan.'));
    }

    public function editKaryawan(int $id): void
    {
        $item = Karyawan::findOrFail($id);
        $this->editingId = $item->id;
        $this->editNama = $item->nama;
        $this->editEmail = $item->email;
        $this->editJabatanId = $item->jabatan_id;
        $this->editDivisiId = $item->divisi_id;
        $this->editNoTelp = $item->no_telp;
        $this->showEditModal = true;
    }

    public function updateKaryawan(): void
    {
        $this->validate([
            'editNama' => ['required', 'string', 'max:255'],
            'editEmail' => ['required', 'email', 'max:255', 'unique:karyawans,email,' . $this->editingId],
            'editJabatanId' => ['required', 'integer', 'exists:jabatans,id'],
            'editDivisiId' => ['required', 'integer', 'exists:divisis,id'],
            'editNoTelp' => ['required', 'string', 'max:255'],
        ]);

        $item = Karyawan::findOrFail($this->editingId);

        $item->update([
            'nama' => $this->editNama,
            'email' => $this->editEmail,
            'jabatan_id' => $this->editJabatanId,
            'divisi_id' => $this->editDivisiId,
            'no_telp' => $this->editNoTelp,
        ]);

        $this->showEditModal = false;
        $this->reset(['editingId', 'editNama', 'editEmail', 'editJabatanId', 'editDivisiId', 'editNoTelp']);

        Flux::toast(__('Karyawan berhasil diperbarui.'));
    }

    public function deleteKaryawan(int $id): void
    {
        Karyawan::findOrFail($id)->delete();

        Flux::toast(__('Karyawan berhasil dihapus.'));
    }

    protected function filteredKaryawanQuery(): Builder
    {
        return Karyawan::query()
            ->when($this->search, function (Builder $query): void {
                $query->where(function (Builder $query): void {
                    $query->where('nama', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('no_telp', 'like', "%{$this->search}%")
                        ->orWhereHas('jabatan', fn (Builder $q) => $q->where('nama', 'like', "%{$this->search}%"))
                        ->orWhereHas('divisi', fn (Builder $q) => $q->where('nama', 'like', "%{$this->search}%"));
                });
            });
    }

    /** @return array<int, int> */
    protected function currentPageIds(): array
    {
        return $this->karyawans->getCollection()
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
        <flux:heading size="xl" level="1">{{ __('Karyawan') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Kelola data karyawan SPPG') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex-1 self-stretch">
        <flux:heading>{{ __('Daftar Karyawan') }}</flux:heading>
        <flux:subheading>{{ __('Data karyawan beserta jabatan dan divisi') }}</flux:subheading>

        <div class="mt-5 w-full">
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Cari karyawan...') }}" icon="magnifying-glass" class="max-w-sm" />
                <div class="flex flex-wrap items-center gap-2">
                    <flux:button
                        variant="danger"
                        icon="trash"
                        wire:click="bulkDelete"
                        wire:confirm="{{ __('Yakin ingin menghapus semua karyawan terpilih?') }}"
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
                    <flux:button wire:click="exportPdf" icon="document-arrow-down">
                        {{ __('Export PDF') }}
                    </flux:button>
                    <flux:button variant="primary" wire:click="openCreateModal" icon="plus">
                        {{ __('Tambah Karyawan') }}
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
                    <flux:table.column>{{ __('Email') }}</flux:table.column>
                    <flux:table.column>{{ __('Jabatan') }}</flux:table.column>
                    <flux:table.column>{{ __('Divisi') }}</flux:table.column>
                    <flux:table.column>{{ __('No. Telp') }}</flux:table.column>
                    <flux:table.column>{{ __('Aksi') }}</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($this->karyawans as $item)
                        <flux:table.row :wire:key="$item->id">
                            <flux:table.cell>
                                <flux:checkbox
                                    wire:model.live="selectedIds"
                                    value="{{ $item->id }}"
                                    aria-label="{{ __('Pilih :name', ['name' => $item->nama]) }}"
                                />
                            </flux:table.cell>
                            <flux:table.cell class="font-medium">{{ $item->nama }}</flux:table.cell>
                            <flux:table.cell>{{ $item->email }}</flux:table.cell>
                            <flux:table.cell>
                                @if ($item->jabatan)
                                    <flux:badge size="sm">{{ $item->jabatan->nama }}</flux:badge>
                                @else
                                    <span class="text-zinc-400">-</span>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>
                                @if ($item->divisi)
                                    <flux:badge size="sm">{{ $item->divisi->nama }}</flux:badge>
                                @else
                                    <span class="text-zinc-400">-</span>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>{{ $item->no_telp }}</flux:table.cell>
                            <flux:table.cell>
                                <div class="flex gap-2">
                                    <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="editKaryawan({{ $item->id }})" />
                                    <flux:button size="sm" variant="ghost" icon="trash" wire:click="deleteKaryawan({{ $item->id }})" wire:confirm="{{ __('Yakin ingin menghapus karyawan ini?') }}" />
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>

            <div class="mt-4">
                {{ $this->karyawans->links() }}
            </div>
        </div>
    </div>

    <flux:modal wire:model="showCreateModal" class="w-full max-w-lg">
        <div class="space-y-6">
            <flux:heading size="lg">{{ __('Tambah Karyawan') }}</flux:heading>

            <form wire:submit="createKaryawan" class="space-y-4">
                <flux:field>
                    <flux:label>{{ __('Nama') }}</flux:label>
                    <flux:input wire:model="createNama" />
                    <flux:error name="createNama" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Email') }}</flux:label>
                    <flux:input type="email" wire:model="createEmail" />
                    <flux:error name="createEmail" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Jabatan') }}</flux:label>
                    <flux:select wire:model="createJabatanId">
                        <option value="">{{ __('Pilih Jabatan') }}</option>
                        @foreach ($this->jabatans as $j)
                            <option value="{{ $j->id }}">{{ $j->nama }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="createJabatanId" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Divisi') }}</flux:label>
                    <flux:select wire:model="createDivisiId">
                        <option value="">{{ __('Pilih Divisi') }}</option>
                        @foreach ($this->divisis as $d)
                            <option value="{{ $d->id }}">{{ $d->nama }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="createDivisiId" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('No. Telp') }}</flux:label>
                    <flux:input wire:model="createNoTelp" placeholder="08xxxxxxxxxx" />
                    <flux:error name="createNoTelp" />
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
                <flux:heading size="lg">{{ __('Import Karyawan dari CSV') }}</flux:heading>
                <flux:subheading>
                    {{ __('Gunakan header: nama,email,jabatan,divisi,no_telp. Jabatan & divisi harus sesuai nama master. Email duplikat akan diperbarui.') }}
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
            <flux:heading size="lg">{{ __('Edit Karyawan') }}</flux:heading>

            <form wire:submit="updateKaryawan" class="space-y-4">
                <flux:field>
                    <flux:label>{{ __('Nama') }}</flux:label>
                    <flux:input wire:model="editNama" />
                    <flux:error name="editNama" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Email') }}</flux:label>
                    <flux:input type="email" wire:model="editEmail" />
                    <flux:error name="editEmail" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Jabatan') }}</flux:label>
                    <flux:select wire:model="editJabatanId">
                        <option value="">{{ __('Pilih Jabatan') }}</option>
                        @foreach ($this->jabatans as $j)
                            <option value="{{ $j->id }}">{{ $j->nama }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="editJabatanId" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Divisi') }}</flux:label>
                    <flux:select wire:model="editDivisiId">
                        <option value="">{{ __('Pilih Divisi') }}</option>
                        @foreach ($this->divisis as $d)
                            <option value="{{ $d->id }}">{{ $d->nama }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="editDivisiId" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('No. Telp') }}</flux:label>
                    <flux:input wire:model="editNoTelp" placeholder="08xxxxxxxxxx" />
                    <flux:error name="editNoTelp" />
                </flux:field>

                <div class="flex justify-end gap-2">
                    <flux:button wire:click="$set('showEditModal', false)">{{ __('Batal') }}</flux:button>
                    <flux:button type="submit" variant="primary">{{ __('Simpan') }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</section>
