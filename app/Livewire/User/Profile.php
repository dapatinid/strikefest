<?php

namespace App\Livewire\User;

use App\Livewire\Traits\Alert;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Livewire\Component;

class Profile extends Component
{
    use Alert, WithFileUploads;

    public User $user;

    #[Validate('image|max:4000|mimes:png,jpg,jpeg|nullable')]
    public $image;
    public ?string $password = null;
    public ?string $password_confirmation = null;

    public function mount(): void
    {
        $this->user = Auth::user();
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
            'password' => [
                'nullable',
                'string',
                'confirmed',
                Rules\Password::defaults()
            ]
        ];
    }

    public function render(): View
    {
        return view('livewire.user.profile');
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

        $this->user->password = when($this->password !== null, Hash::make($this->password), $this->user->password);
        $this->user->save();

        $this->dispatch('updated', name: $this->user->name);

        $this->resetExcept('user');

        $this->success();
    }
}
