<div class="mx-auto max-w-lg space-y-3 p-3">
    <div class="{{ Auth::user()->street == null || Auth::user()->village == null || Auth::user()->district == null || Auth::user()->city == null || Auth::user()->state == null ? '' : 'hidden' }}">
        <x-alert color="amber" icon="light-bulb" close >
            @lang('Lengkapi data di bawah ini. Data tidak lengkap tidak diizinkan mengikuti Event.')
        </x-alert>
    </div>
    <div class="grid grid-cols-1 gap-3 ">
    <x-card color="primary">
        <x-slot:header>
            <div class="p-4">
                Edit Alamat
            </div>
        </x-slot:header>
         <form id="user-update-alamat-{{ $user->id }}" wire:submit="save_alamat" class="space-y-4">
          
            <div>
                <x-select.styled label="Provinsi"  wire:model.live="state" :options="$provinsi" searchable wire:change="saveprovinsi"/>
            </div>
            <div>
                <x-select.styled label="Kabupaten / Kota" wire:model.live="city" :options="$kota" searchable wire:change="savekota" />
            </div>
            <div>
                <x-select.styled label="Kecamatan" wire:model.live="district" :options="$kecamatan" searchable wire:change="savekecamatan" />
            </div>
            <div>
                <x-select.styled label="Desa" wire:model.live="village" :options="$desa" searchable wire:change="savedesa" />
            </div>
            <div>
                <x-input label="Jalan dan Detail Alamat" wire:model="street" hint="Gang / Jalan / Dukuh / RT / RW" wire:change="savejalan" />
            </div>
            <div>
                <x-input label="Kode Pos" wire:model="zip_code" hint="tidak wajib isi" />
            </div>
       
        </form>
        <x-slot:footer>
            <div class="space-x-2">
                <x-button href="/user/profile" wire:navigate.hover color="secondary">
                    @lang('Back')
                </x-button>
                <x-button type="submit" form="user-update-alamat-{{ $user->id  }}" >
                    @lang('Save')
                </x-button>
            </div>
        </x-slot:footer>
    </x-card>
    </div>

</div>
