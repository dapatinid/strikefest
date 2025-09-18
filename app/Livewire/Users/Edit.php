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
use Livewire\Attributes\Url;

class Edit extends Component
{
    use Alert, WithFileUploads;

    public User $user;

    #[Validate('image|max:4000|mimes:png,jpg,jpeg|nullable')]
    public $image;
    public ?string $password = null;
    public ?string $password_confirmation = null;

    public function mount($userid)
    {
        $this->user = User::find($userid);
    }

    public function render()
    {
        return view('livewire.users.edit');
    }

    public function rules(): array
    {
        return [
            'user.name' => [
                'required',
                'string',
                'max:255'
            ],
            'user.phone' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'phone')->ignore($this->user->id),
            ],
            'user.email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user->id),
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed'
            ],
        ];
    }

    public function save(): void
    {
        $this->validate();

        if ($this->image != null) {
            Storage::disk('public')->putFile('avatar', $this->image);
            $image = Storage::disk('public')->putFile('avatar', $this->image);
            $this->user->image = $image;
        } else {
            $this->user->image = $this->user->image;
        }

        $this->user->password = when($this->password !== null, bcrypt($this->password), $this->user->password);
        $this->user->update();

        $this->toast()
            // ->persistent()
            ->position('top-right')
            ->success('Berhasil', 'Perubahan Akun disimpan')
            ->flash()
            ->send();

        $this->redirect('/users', navigate: true);
    }
}
