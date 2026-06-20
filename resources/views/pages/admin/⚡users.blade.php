<?php

use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

new #[Title('Manajemen Users')] class extends Component {
    use WithPagination;

    public string $search = '';

    public int $editingUserId = 0;

    public string $editName = '';

    public string $editEmail = '';

    public string $editPassword = '';

    /** @var list<string> */
    public array $editRoles = [];

    public bool $showEditModal = false;

    public bool $showCreateModal = false;

    public string $createName = '';

    public string $createEmail = '';

    public string $createPassword = '';

    /** @var list<string> */
    public array $createRoles = [];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function users()
    {
        return User::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%"))
            ->with('roles')
            ->orderByDesc('id')
            ->paginate(10);
    }

    #[Computed]
    public function roles()
    {
        return Role::orderBy('name')->get();
    }

    public function editUser(int $userId): void
    {
        $user = User::findOrFail($userId);
        $this->editingUserId = $user->id;
        $this->editName = $user->name;
        $this->editEmail = $user->email;
        $this->editPassword = '';
        $this->editRoles = $user->roles->pluck('name')->toArray();
        $this->showEditModal = true;
    }

    public function updateUser(): void
    {
        $rules = [
            'editName' => ['required', 'string', 'max:255'],
            'editEmail' => ['required', 'email', 'max:255', 'unique:users,email,'.$this->editingUserId],
            'editRoles' => ['array'],
        ];

        if ($this->editPassword !== '') {
            $rules['editPassword'] = ['string', 'min:8'];
        }

        $this->validate($rules);

        $user = User::findOrFail($this->editingUserId);
        $data = [
            'name' => $this->editName,
            'email' => $this->editEmail,
        ];

        if ($this->editPassword !== '') {
            $data['password'] = Hash::make($this->editPassword);
        }

        $user->update($data);
        $user->syncRoles($this->editRoles);

        $this->showEditModal = false;
        $this->reset(['editingUserId', 'editName', 'editEmail', 'editPassword', 'editRoles']);

        Flux::toast(__('User berhasil diperbarui.'));
    }

    public function openCreateModal(): void
    {
        $this->reset(['createName', 'createEmail', 'createPassword', 'createRoles']);
        $this->showCreateModal = true;
    }

    public function createUser(): void
    {
        $this->validate([
            'createName' => ['required', 'string', 'max:255'],
            'createEmail' => ['required', 'email', 'max:255', 'unique:users,email'],
            'createPassword' => ['required', 'string', 'min:8'],
            'createRoles' => ['array'],
        ]);

        $user = User::create([
            'name' => $this->createName,
            'email' => $this->createEmail,
            'password' => $this->createPassword,
        ]);

        if (count($this->createRoles) > 0) {
            $user->syncRoles($this->createRoles);
        }

        $this->showCreateModal = false;
        $this->reset(['createName', 'createEmail', 'createPassword', 'createRoles']);

        Flux::toast(__('User berhasil dibuat.'));
    }

    public function deleteUser(int $userId): void
    {
        $user = User::findOrFail($userId);

        if ($user->id === auth()->id()) {
            Flux::toast(__('Tidak dapat menghapus akun sendiri.'), variant: 'danger');

            return;
        }

        $user->delete();

        Flux::toast(__('User berhasil dihapus.'));
    }
}; ?>

<section class="w-full">
    @include('partials.admin-heading')

    <x-pages::admin.layout :heading="__('Users')" :subheading="__('Kelola data pengguna sistem')">
        <div class="mb-4 flex items-center justify-between gap-4">
            <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Cari user...') }}" icon="magnifying-glass" class="max-w-sm" />
            <flux:button variant="primary" wire:click="openCreateModal" icon="plus">
                {{ __('Tambah User') }}
            </flux:button>
        </div>

        <flux:table>
            <flux:table.columns>
                <flux:table.column>{{ __('Nama') }}</flux:table.column>
                <flux:table.column>{{ __('Email') }}</flux:table.column>
                <flux:table.column>{{ __('Role') }}</flux:table.column>
                <flux:table.column>{{ __('Aksi') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($this->users as $user)
                    <flux:table.row :wire:key="$user->id">
                        <flux:table.cell>{{ $user->name }}</flux:table.cell>
                        <flux:table.cell>{{ $user->email }}</flux:table.cell>
                        <flux:table.cell>
                            <div class="flex flex-wrap gap-1">
                                @foreach ($user->roles as $role)
                                    <flux:badge size="sm">{{ $role->name }}</flux:badge>
                                @endforeach
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex gap-2">
                                <flux:button size="sm" variant="ghost" icon="pencil-square" wire:click="editUser({{ $user->id }})" />
                                @if ($user->id !== auth()->id())
                                    <flux:button size="sm" variant="ghost" icon="trash" wire:click="deleteUser({{ $user->id }})" wire:confirm="{{ __('Yakin ingin menghapus user ini?') }}" />
                                @endif
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        <div class="mt-4">
            {{ $this->users->links() }}
        </div>

        <flux:modal wire:model="showEditModal" class="w-full max-w-lg">
            <div class="space-y-6">
                <flux:heading size="lg">{{ __('Edit User') }}</flux:heading>

                <form wire:submit="updateUser" class="space-y-4">
                    <flux:field>
                        <flux:label>{{ __('Nama') }}</flux:label>
                        <flux:input wire:model="editName" />
                        <flux:error name="editName" />
                    </flux:field>

                    <flux:field>
                        <flux:label>{{ __('Email') }}</flux:label>
                        <flux:input type="email" wire:model="editEmail" />
                        <flux:error name="editEmail" />
                    </flux:field>

                    <flux:field>
                        <flux:label>{{ __('Password Baru (Kosongkan jika tidak ingin mengubah)') }}</flux:label>
                        <flux:input type="password" wire:model="editPassword" />
                        <flux:error name="editPassword" />
                    </flux:field>

                    <flux:field>
                        <flux:label>{{ __('Roles') }}</flux:label>
                        @foreach ($this->roles as $role)
                            <flux:checkbox wire:model="editRoles" value="{{ $role->name }}" label="{{ $role->name }}" />
                        @endforeach
                        <flux:error name="editRoles" />
                    </flux:field>

                    <div class="flex justify-end gap-2">
                        <flux:button wire:click="$set('showEditModal', false)">{{ __('Batal') }}</flux:button>
                        <flux:button type="submit" variant="primary">{{ __('Simpan') }}</flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>

        <flux:modal wire:model="showCreateModal" class="w-full max-w-lg">
            <div class="space-y-6">
                <flux:heading size="lg">{{ __('Tambah User') }}</flux:heading>

                <form wire:submit="createUser" class="space-y-4">
                    <flux:field>
                        <flux:label>{{ __('Nama') }}</flux:label>
                        <flux:input wire:model="createName" />
                        <flux:error name="createName" />
                    </flux:field>

                    <flux:field>
                        <flux:label>{{ __('Email') }}</flux:label>
                        <flux:input type="email" wire:model="createEmail" />
                        <flux:error name="createEmail" />
                    </flux:field>

                    <flux:field>
                        <flux:label>{{ __('Password') }}</flux:label>
                        <flux:input type="password" wire:model="createPassword" />
                        <flux:error name="createPassword" />
                    </flux:field>

                    <flux:field>
                        <flux:label>{{ __('Roles') }}</flux:label>
                        @foreach ($this->roles as $role)
                            <flux:checkbox wire:model="createRoles" value="{{ $role->name }}" label="{{ $role->name }}" />
                        @endforeach
                        <flux:error name="createRoles" />
                    </flux:field>

                    <div class="flex justify-end gap-2">
                        <flux:button wire:click="$set('showCreateModal', false)">{{ __('Batal') }}</flux:button>
                        <flux:button type="submit" variant="primary">{{ __('Simpan') }}</flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
    </x-pages::admin.layout>
</section>
