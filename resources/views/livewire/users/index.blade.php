<div class="space-y-3 p-3">
    {{-- <x-alert color="amber" icon="light-bulb" close>
        @lang('Remember to take a look at the source code to understand how the components in this area were built and are being used.')
    </x-alert> --}}
    {{-- <x-card> --}}
        <div class="mb-2 flex justify-between items-center">
            <x-dropdown icon="bolt" position="bottom-start">
                {{-- <x-dropdown.items text="Ganti Level" /> --}}
                <x-dropdown.items text="Hapus" separator wire:click="hapussekaligus"/>
            </x-dropdown>
            {{-- <h1 class="text-2xl text-black dark:text-white">Users</h1> --}}
            <livewire:users.create @created="$refresh" />
        </div>

        <x-table :$headers :$sort :rows="$this->rows" paginate persistent filter loading selectable wire:model="selected" :quantity="[5, 15, 25, 50, 100, 500, 1000]">
            @interact('column_image', $row)
            @if ($row->image)
                <img src="{{ url('storage/'.$row->image) }}" alt="image" class="object-cover size-[50px] rounded-full">
            @endif
            @endinteract

            @interact('column_created_at', $row)
             <x-badge text="{{ $row->created_at->diffForHumans() }}" color="cyan" />
            @endinteract

            @interact('column_action', $row)
            <div class="flex gap-1">
                <x-button.circle icon="eye" wire:click="$dispatch('load::user', { 'user' : '{{ $row->id }}'})" color="sky" />
                <x-button.circle icon="pencil" href="{{ route('users.edit', ['userid' => $row->id]) }}" color="yellow" wire:navigate.hover/> 
                <x-button.circle icon="map-pin" href="{{ route('users.editalamat', ['userid' => $row->id]) }}" color="green" wire:navigate.hover/> 
                <x-button.circle icon="identification" href="{{ route('users.editdatadiri', ['userid' => $row->id]) }}" color="teal" wire:navigate.hover/> 
                <livewire:users.delete :user="$row" :key="uniqid('', true)" @deleted="$refresh" />
            </div>
            @endinteract
        </x-table>
    {{-- </x-card> --}}

    <livewire:users.update @updated="$refresh" />
</div>
