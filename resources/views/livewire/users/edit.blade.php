<div class="mx-auto max-w-lg p-3">
    <div class="grid grid-cols-1 gap-3">

    <x-card color="primary">
        <x-slot:header>
            <div class="p-4">
                Edit Akun
            </div>
        </x-slot:header>
         <form id="user-update-{{ $user->id }}" wire:submit="save" class="space-y-4">
            <div>
                @if ($user->image != null)
                    <img src="{{ url('storage/'.$user->image) }}" alt="avatar" class="object-cover text-center mx-auto size-[120px] rounded-full">
                @else
                    <img src="{{ url('storage/avatar/user.png') }}" alt="avatar" class="object-cover text-center mx-auto size-[120px] rounded-full">
                @endif
            </div>
            <div>
                <x-upload label="Avatar" wire:model="image" required />
            </div>
            <div>
                <x-input label="Nama *" x-ref="name" wire:model="user.name" required />
            </div>
            <div>
                <x-input label="{{ __('No. Whatsapp') }} *" wire:model="user.phone" required />
            </div>
            <div>
                <x-input label="{{ __('Email') }} *" wire:model="user.email" required />
            </div>
            <div>
                <x-password :label="__('Password')"
                            hint="The password will only be updated if you set the value of this field"
                            wire:model="password"
                            rules
                            generator
                            x-on:generate="$wire.set('password_confirmation', $event.detail.password)" />
            </div>
            <div>
                <x-password :label="__('Password')" wire:model="password_confirmation" rules />
            </div>       
        </form>
        <x-slot:footer>
            <div class="space-x-2">
                <x-button href="/users" wire:navigate.hover color="secondary">
                    @lang('Back')
                </x-button>
                <x-button type="submit" form="user-update-{{ $user->id  }}" >
                    @lang('Save')
                </x-button>
            </div>
        </x-slot:footer>
    </x-card>
    

    </div>
</div>
