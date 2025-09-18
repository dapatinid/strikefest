<?php

namespace App\Livewire\User;

use App\Livewire\Traits\Alert;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\User;
use App\Models\Village;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;

class ProfileAlamat extends Component
{
    use Alert, WithFileUploads;

    public User $user;
    public $state;
    public $city;
    public $district;
    public $village;
    public $street;
    public $zip_code;

    public function mount()
    {
        $this->user = User::find(Auth::user()->id);
        $userEdit = User::find(Auth::user()->id);
        $this->state = $userEdit->state;
        $this->city = $userEdit->city;
        $this->district = $userEdit->district;
        $this->village = $userEdit->village;
        $this->street = $userEdit->street;
        $this->zip_code = $userEdit->zip_code;
    }
    public function saveprovinsi()
    {
        $this->user->state = $this->state;
        $this->user->city = null;
        $this->user->district = null;
        $this->user->village = null;
        $this->user->update();
        $this->toast()
            ->position('top-right')
            ->success('Berhasil', 'Ubah Provinsi')
            ->send();
        $this->redirect('/user/profile-alamat', navigate: true);
    }
    public function savekota()
    {
        $this->user->city = $this->city;
        $this->user->district = null;
        $this->user->village = null;
        $this->user->update();
        $this->toast()
            ->position('top-right')
            ->success('Berhasil', 'Ubah Kabupaten / Kota')
            ->send();
        $this->redirect('/user/profile-alamat', navigate: true);
    }
    public function savekecamatan()
    {

        $this->user->district = $this->district;
        $this->user->village = null;
        $this->user->update();
        $this->toast()
            ->position('top-right')
            ->success('Berhasil', 'Ubah Kecamatan')
            ->send();
        $this->redirect('/user/profile-alamat', navigate: true);
    }
    public function savedesa()
    {

        $this->user->village = $this->village;
        $this->user->update();
        $this->toast()
            ->position('top-right')
            ->success('Berhasil', 'Ubah Desa')
            ->send();
        $this->redirect('/user/profile-alamat', navigate: true);
    }
    public function savejalan()
    {
        $this->user->street = $this->street;
        $this->user->update();
        $this->toast()
            ->position('top-right')
            ->success('Berhasil', 'Ubah Jalan / Detail Alamat')
            ->send();
        $this->redirect('/user/profile-alamat', navigate: true);
    }
    public function render()
    {
        // $search = 'jawa';
        $provinsi = Province::query()
            // ->when($search, fn(Builder $query) => $query->where('name', 'like', "%{$search}%"))
            // ->unless($search, fn(Builder $query) => $query->limit(100))
            ->get()
            ->map(fn(Province $provinsi): array => [
                'label' => $provinsi->name,
                'value' => $provinsi->code,
            ]);
        $kota = City::query()
            ->when($this->state, fn(Builder $query) => $query->where('province_code', 'like', "%{$this->state}%"))
            ->unless($this->state, fn(Builder $query) => $query->limit(200))
            ->orderByDesc('name')
            ->get()
            ->map(fn(City $kota): array => [
                'label' => $kota->name,
                'value' => $kota->code,
            ]);
        $kecamatan = District::query()
            ->when($this->city, fn(Builder $query) => $query->where('city_code', 'like', "%{$this->city}%"))
            ->unless($this->city, fn(Builder $query) => $query->limit(200))
            ->orderBy('name')
            ->get()
            ->map(fn(District $kecamatan): array => [
                'label' => $kecamatan->name,
                'value' => $kecamatan->code,
            ]);
        $desa = Village::query()
            ->when($this->district, fn(Builder $query) => $query->where('district_code', 'like', "%{$this->district}%"))
            ->unless($this->district, fn(Builder $query) => $query->limit(200))
            ->orderBy('name')
            ->get()
            ->map(fn(Village $desa): array => [
                'label' => $desa->name,
                'value' => $desa->code,
            ]);
        return view('livewire.user.profile-alamat', [
            'provinsi' => $provinsi,
            'kota' => $kota,
            'kecamatan' => $kecamatan,
            'desa' => $desa,
        ]);
    }

    public function save_alamat(): void
    {
        // $this->validate();

        // $this->user->state = $this->state;
        // $this->user->city = $this->city;
        // $this->user->district = $this->district;
        // $this->user->village = $this->village;
        // $this->user->street = $this->street;
        $this->user->zip_code = $this->zip_code;

        $this->user->update();

        $this->toast()
            // ->persistent()
            ->position('top-right')
            ->success('Berhasil', 'Perubahan alamat disimpan')
            // ->flash()
            ->send();

        $this->redirect('/user/profile-data-diri', navigate: true);
    }
}
