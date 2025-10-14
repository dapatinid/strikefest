<?php

namespace App\Livewire\Users;

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

class EditDataDiri extends Component
{
    use Alert, WithFileUploads;

    public User $user;
    #[Validate('image|max:4000|mimes:png,jpg,jpeg|nullable')]
    public $image;
    public $gender;
    public $tempat_lahir;
    public $tanggal_lahir;

    #[Validate('image|max:4000|mimes:png,jpg,jpeg|nullable')]
    public $image_id;
    public $no_id;

    public $ukuran_jersey;
    public $klub;

    public function mount($userid)
    {
        $this->user = User::find($userid);
    }

    public function rules(): array
    {
        return [
            'user.gender' => [
                'required',
            ],
            'user.tempat_lahir' => [
                'required',
                'string',
                'max:255'
            ],
            'user.tanggal_lahir' => [
                'required',
            ],
            'user.no_id' => [
                'required',
                'string',
                'max:255'
            ],
            'user.ukuran_jersey' => [
                'required',
            ],
            'user.klub' => [
                'nullable',
                'string',
                'max:255'
            ],

        ];
    }

    public function render()
    {

        return view('livewire.users.edit-data-diri');
    }

    public function save_data_diri(): void
    {
        $this->validate();

        if ($this->image != null) {
            Storage::disk('public')->putFile('avatar', $this->image);
            $image = Storage::disk('public')->putFile('avatar', $this->image);
            $this->user->image = $image;
        } else {
            $this->user->image = $this->user->image;
        }
        if ($this->image_id != null) {
            Storage::disk('public')->putFile('image_id', $this->image_id);
            $image_id = Storage::disk('public')->putFile('image_id', $this->image_id);
            $this->user->image_id = $image_id;
        } else {
            $this->user->image_id = $this->user->image_id;
        }

        $this->user->update();

        $this->toast()
            // ->persistent()
            ->position('top-right')
            ->success('Berhasil', 'Perubahan data diri disimpan')
            // ->flash()
            ->send();

        // $this->redirect('/user/profile', navigate: true);
    }
}
