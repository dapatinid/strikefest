<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Filament\Panel; # fungsi agar tidak dapat masuk ke /admin panel

/**
 * @property string $name
 * @property string $email
 * @property string $password
 * @property Carbon $email_verified_at
 * @property string $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'image',
        'name',
        'email',
        'email_verified_at',
        'password',
        'is_admin',
        'level',
        'phone',
        'street',
        'village',
        'district',
        'city',
        'state',
        'zip_code',
        'image_id',

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'tanggal_lahir' => 'datetime',
        ];
    }

    public function prov()
    {
        return $this->hasOne(Province::class, 'code', 'state');
    }
    public function kabkota()
    {
        return $this->hasOne(City::class, 'code', 'city');
    }
    public function kec()
    {
        return $this->hasOne(District::class, 'code', 'district');
    }
    public function desa()
    {
        return $this->hasOne(Village::class, 'code', 'village');
    }

    // fungsi agar tidak dapat masuk ke /admin panel
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin == 1;
    }
    public function getFilamentAvatarUrl(): ?string
    {
        if (isset($this->image)) {
            return url('storage/' . $this->image);
        } else {
            return url('storage/avatar/user.png');
        }
    }
}
