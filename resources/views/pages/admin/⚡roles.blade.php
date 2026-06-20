<?php

use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

new #[Title('Manajemen Roles')] class extends Component {
    public string $search = '';

    public bool $showCreateModal = false;

    public string $createName = '';

    /** @var list<string> */
    public array $createPermissions = [];

    public bool $showEditModal = false;

    public int $editingRoleId = 0;

    public string $editName = '';

    /** @var list<string> */
    public array $editPermissions = [];

    #[Computed]
    public function roles()
    {
        return Role::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->with('permissions')
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function permissions()
    {
        return Permission::orderBy('name')->get();
    }

    public function openCreateModal(): void
    {
        $this->reset(['createName', 'createPermissions']);
        $this->showCreateModal = true;
    }

    public function createRole(): void
    {
        $this->validate([
            'createName' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'createPermissions' => ['array'],
        ]);

        $role = Role::create(['name' => $this->createName, 'guard_name' => 'web']);

        if (count($this->createPermissions) > 0) {
            $role->syncPermissions($this->createPermissions);
        }

        $this->showCreateModal = false;
        $this->reset(['createName', 'createPermissions']);

        Flux::toast(__('Role berhasil dibuat.'));
    }

    public function editRole(int $roleId): void
    {
        $role = Role::findOrFail($roleId);
        $this->editingRoleId = $role->id;
        $this->editName = $role->name;
        $this->editPermissions = $role->permissions->pluck('name')->toArray();
        $this->showEditModal = true;
    }

    public function updateRole(): void
    {
        $this->validate([
            'editName' => ['required', 'string', 'max:255', 'unique:roles,name,'.$this->editingRoleId],
            'editPermissions' => ['array'],
        ]);

        $role = Role::findOrFail($this->editingRoleId);
        $role->update(['name' => $this->editName]);
        $role->syncPermissions($this->editPermissions);

        $this->showEditModal = false;
        $this->reset(['editingRoleId', 'editName', 'editPermissions']);

        Flux::toast(__('Role berhasil diperbarui.'));
    }

    public function deleteRole(int $roleId): void
    {
        $role = Role::findOrFail($roleId);

        if ($role->name === 'super_admin') {
            Flux::toast(__('Role super_admin tidak dapat dihapus.'), variant: 'danger');

            return;
        }

        $role->delete();

        Flux::toast(__('Role berhasil dihapus.'));
    }
}; ?>

<section class="w-full">
    @include('partials.admin-heading')

    <x-pages::admin.layout :heading="__('Roles')" :subheading="__('Kelola peran pengguna sistem')">
        <div class="mb-4 flex items-center justify-between gap-4">
            <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Cari role...') }}" icon="magnifying-glass" class="max-w-sm" />
            <flux:button variant="primary" wire:click="openCreateModal" icon="plus">
                {{ __('Tambah Role') }}
            </flux:button>
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>{{ __('Nama Role') }}</flux:table.column>
                <flux:table.column>{{ __('Permissions') }}</flux:table.column>
                <flux:table.column>{{ __('Jumlah User') }}</flux:table.column>
                <flux:table.column>{{ __('Aksi') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($this->roles as $role)
                    <flux:table.row :wire:key="$role->id">
                        <flux:table.cell>
                            <flux:badge>{{ $role->name }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex flex-wrap gap-1">
                                @if ($role->name === 'super_admin')
                                    <flux:badge size="sm" color="amber">{{ __('Semua Akses') }}</flux:badge>
                                @else
                                    @foreach ($role->permissions->take(3) as $permission)
                                        <flux:badge size="sm">{{ $permission->name }}</flux:badge>
                                    @endforeach
                                    @if ($role->permissions->count() > 3)
                                        <flux:badge size="sm" color="zinc">+{{ $role->permissions->count() - 3 }}</flux:badge>
                                    @endif
                                @endif
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>{{ $role->users_count ?? $role->users()->count() }}</flux:table.cell>
                        <flux:table.cell>
                            <div class="flex gap-2">
                                <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="editRole({{ $role->id }})" />
                                @if ($role->name !== 'super_admin')
                                    <flux:button size="sm" variant="ghost" icon="trash" wire:click="deleteRole({{ $role->id }})" wire:confirm="{{ __('Yakin ingin menghapus role ini?') }}" />
                                @endif
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        <flux:modal wire:model="showCreateModal" class="w-full max-w-lg">
            <div class="space-y-6">
                <flux:heading size="lg">{{ __('Tambah Role') }}</flux:heading>

                <form wire:submit="createRole" class="space-y-4">
                    <flux:field>
                        <flux:label>{{ __('Nama Role') }}</flux:label>
                        <flux:input wire:model="createName" />
                        <flux:error name="createName" />
                    </flux:field>

                    <flux:field>
                        <flux:label>{{ __('Permissions') }}</flux:label>
                        <div class="max-h-60 space-y-1 overflow-y-auto">
                            @foreach ($this->permissions as $permission)
                                <flux:checkbox wire:model="createPermissions" value="{{ $permission->name }}" label="{{ $permission->name }}" />
                            @endforeach
                        </div>
                        <flux:error name="createPermissions" />
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
                <flux:heading size="lg">{{ __('Edit Role') }}</flux:heading>

                <form wire:submit="updateRole" class="space-y-4">
                    <flux:field>
                        <flux:label>{{ __('Nama Role') }}</flux:label>
                        <flux:input wire:model="editName" />
                        <flux:error name="editName" />
                    </flux:field>

                    <flux:field>
                        <flux:label>{{ __('Permissions') }}</flux:label>
                        <div class="max-h-60 space-y-1 overflow-y-auto">
                            @foreach ($this->permissions as $permission)
                                <flux:checkbox wire:model="editPermissions" value="{{ $permission->name }}" label="{{ $permission->name }}" />
                            @endforeach
                        </div>
                        <flux:error name="editPermissions" />
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
