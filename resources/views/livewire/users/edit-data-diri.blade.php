<div class="mx-auto max-w-lg p-3">
   <x-card :header="__('Edit Data Diri :nama', ['nama' => $user?->name])" color="primary">
        <form id="update-profile-data-diri" wire:submit="save_data_diri">
            <div class="space-y-6">
                <div>
                    @if ($image) 
                        <img src="/storage/livewire-tmp/{{ $image->getFilename() }}" class="object-cover text-center mx-auto size-[120px] rounded-full">
                    @else
                        @if ($user->image != null)
                            <img src="{{ url('storage/'.$user->image) }}" alt="avatar" class="object-cover text-center mx-auto size-[120px] rounded-full">
                        @else
                            <img src="{{ url('storage/avatar/user.png') }}" alt="avatar" class="object-cover text-center mx-auto size-[120px] rounded-full">
                        @endif
                    @endif
                </div>
                <div>
                    <x-upload label="Avatar" wire:model="image" required />
                </div>
                <div>
                    <x-select.styled label="{{ __('Gender') }} *" wire:model="user.gender" required :options="[
                        ['label' => 'Laki - Laki', 'value' => 'L'],
                        ['label' => 'Perempuan', 'value' => 'P'],
                    ]" />
                </div>
                <div>
                    <x-input label="{{ __('Tempat Lahir') }} *" wire:model="user.tempat_lahir" required />
                </div>
                <div>
                    <x-date label="{{ __('Tanggal Lahir') }} *" wire:model="user.tanggal_lahir" required />
                </div>

                <div>
                    @if ($image_id) 
                        <img src="/storage/livewire-tmp/{{ $image_id->getFilename() }}" class="object-cover text-center mx-auto w-50 aspect-[16/11]">
                    @else
                        @if ($user->image_id != null)
                            <img src="{{ url('storage/'.$user->image_id) }}" alt="foto_id" class="object-cover text-center mx-auto w-50 aspect-[16/11]">
                        @else
                            <img src="{{ url('storage/image_id/image_id.png') }}" alt="foto_id" class="object-cover text-center mx-auto w-50 aspect-[16/11]">
                        @endif
                    @endif
                </div>
                <div>
                    <x-upload label="Foto KTP" wire:model="image_id" required />
                </div>
                <div>
                    <x-input label="{{ __('No. ID / KTP / Passport') }} *" wire:model="user.no_id" required />
                </div>

                <div>
                    <x-select.styled label="{{ __('Ukuran Jersey') }} *" wire:model="user.ukuran_jersey" required :options="[
                        ['label' => 'S', 'value' => 'S'],
                        ['label' => 'M', 'value' => 'M'],
                        ['label' => 'L', 'value' => 'L'],
                        ['label' => 'XL', 'value' => 'XL'],
                        ['label' => 'XXL', 'value' => 'XXL'],
                        ['label' => 'XXXL', 'value' => 'XXXL'],
                    ]" />
                </div>
                <div>
                    <x-input label="{{ __('Komunitas Mancing') }}" wire:model="user.klub" hint="khusus diisi oleh Ketua, dalam kategori kelompok." />
                </div>

            </div>
            <x-slot:footer>
                <x-button type="submit">
                    @lang('Save')
                </x-button>
            </x-slot:footer>
        </form>
        <x-slot:footer>
            <x-button type="submit" form="update-profile-data-diri">
                @lang('Save')
            </x-button>
        </x-slot:footer>
    </x-card>

</div>
