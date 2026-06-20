<?php

use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

new #[Title('Manajemen Permissions')] class extends Component {
    public string $search = '';

    public bool $showCreateModal = false;

    public string $createName = '';

    public bool $showEditModal = false;

    public int $editingPermissionId = 0;

    public string $editName = '';

    #[Computed]
    public function permissions()
    {
        return Permission::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->with('roles')
            ->orderBy('name')
            ->get();
    }

    public function openCreateModal(): void
    {
        $this->reset(['createName']);
        $this->showCreateModal = true;
    }

    public function createPermission(): void
    {
        $this->validate([
            'createName' => ['required', 'string', 'max:255', 'unique:permissions,name'],
        ]);

        Permission::create(['name' => $this->createName, 'guard_name' => 'web']);

        $this->showCreateModal = false;
        $this->reset(['createName']);

        Flux::toast(__('Permission berhasil dibuat.'));
    }

    public function editPermission(int $permissionId): void
    {
        $permission = Permission::findOrFail($permissionId);
        $this->editingPermissionId = $permission->id;
        $this->editName = $permission->name;
        $this->showEditModal = true;
    }

    public function updatePermission(): void
    {
        $this->validate([
            'editName' => ['required', 'string', 'max:255', 'unique:permissions,name,'.$this->editingPermissionId],
        ]);

        $permission = Permission::findOrFail($this->editingPermissionId);
        $permission->update(['name' => $this->editName]);

        $this->showEditModal = false;
        $this->reset(['editingPermissionId', 'editName']);

        Flux::toast(__('Permission berhasil diperbarui.'));
    }

    public function deletePermission(int $permissionId): void
    {
        $permission = Permission::findOrFail($permissionId);
        $permission->delete();

        Flux::toast(__('Permission berhasil dihapus.'));
    }
}; ?>

<section class="w-full">
    @include('partials.admin-heading')

    <x-pages::admin.layout :heading="__('Permissions')" :subheading="__('Kelola hak akses sistem')">
        <div class="mb-4 flex items-center justify-between gap-4">
            <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Cari permission...') }}" icon="magnifying-glass" class="max-w-sm" />
            <flux:button variant="primary" wire:click="openCreateModal" icon="plus">
                {{ __('Tambah Permission') }}
            </flux:button>
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>{{ __('Nama Permission') }}</flux:table.column>
                <flux:table.column>{{ __('Digunakan oleh Role') }}</flux:table.column>
                <flux:table.column>{{ __('Aksi') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($this->permissions as $permission)
                    <flux:table.row :wire:key="$permission->id">
                        <flux:table.cell>{{ $permission->name }}</flux:table.cell>
                        <flux:table.cell>
                            <div class="flex flex-wrap gap-1">
                                @foreach ($permission->roles as $role)
                                    <flux:badge size="sm">{{ $role->name }}</flux:badge>
                                @endforeach
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex gap-2">
                                <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="editPermission({{ $permission->id }})" />
                                <flux:button size="sm" variant="ghost" icon="trash" wire:click="deletePermission({{ $permission->id }})" wire:confirm="{{ __('Yakin ingin menghapus permission ini?') }}" />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        <flux:modal wire:model="showCreateModal" class="w-full max-w-lg">
            <div class="space-y-6">
                <flux:heading size="lg">{{ __('Tambah Permission') }}</flux:heading>

                <form wire:submit="createPermission" class="space-y-4">
                    <flux:field>
                        <flux:label>{{ __('Nama Permission') }}</flux:label>
                        <flux:input wire:model="createName" placeholder="contoh: user.create" />
                        <flux:error name="createName" />
                    </flux:field>

                    <div class="flex justify-end gap-2">
                        <flux:button wire:click="$set('showCreateModal', false)">{{ __('Batal') }}</flux:button>
                        <flux:button type="submit" variant="primary">{{ __('Simpan') }}</flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>

        <flux:modal wire:model="showEditModal" class="w-full max-w-lg">
            <div class="space-y-6">
                <flux:heading size="lg">{{ __('Edit Permission') }}</flux:heading>

                <form wire:submit="updatePermission" class="space-y-4">
                    <flux:field>
                        <flux:label>{{ __('Nama Permission') }}</flux:label>
                        <flux:input wire:model="editName" />
                        <flux:error name="editName" />
                    </flux:field>

                    <div class="flex justify-end gap-2">
                        <flux:button wire:click="$set('showEditModal', false)">{{ __('Batal') }}</flux:button>
                        <flux:button type="submit" variant="primary">{{ __('Simpan') }}</flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
    </x-pages::admin.layout>
</section>
