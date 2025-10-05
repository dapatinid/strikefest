<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Livewire\Traits\Alert;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Title;
// use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, Alert;

    // #[Url()]
    public $selected = [];

    public ?int $quantity = 50;

    public ?string $search = null;

    public array $sort = [
        'column'    => 'id',
        'direction' => 'desc',
    ];

    public array $headers = [
        ['index' => 'id', 'label' => '#'],
        ['index' => 'image', 'label' => 'Photo'],
        ['index' => 'name', 'label' => 'Name'],
        ['index' => 'phone', 'label' => 'Phone'],
        ['index' => 'email', 'label' => 'E-mail'],
        ['index' => 'created_at', 'label' => 'Created'],
        ['index' => 'action', 'sortable' => false],
    ];

    #[Title('Users')]
    public function render(): View
    {
        return view('livewire.users.index');
    }

    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        return User::query()
            ->whereNotIn('id', [Auth::id()])
            ->when($this->search !== null, fn(Builder $query) => $query->whereAny(['name', 'email'], 'like', '%' . trim($this->search) . '%'))
            ->orderBy(...array_values($this->sort))
            ->paginate($this->quantity)
            ->withQueryString();
    }

    #[Renderless]
    public function hapussekaligus()
    {
        if ($this->selected == null) {
            $this->toast()->error('Gagal', 'Tidak ada data terpilih')->send();
        } else {
            $this->question()
                ->error('Hapus Sekaligus!', count($this->selected) . ' data terpilih akan terhapus')
                ->confirm(method: 'hapussekaliguskonfirmasi')
                ->cancel()
                ->send();
        }
    }

    public function hapussekaliguskonfirmasi()
    {
        foreach ($this->selected as $key => $id) {
            User::find($id)->delete();
        }

        return $this->redirect('/users', navigate: true);
    }
}
