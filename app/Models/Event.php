<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'subtitle',
        'slug',
        'cover',
        'body',
        'price',
        'embed_videos',
        'categories',
        'tags',
        'date_from',
        'date_until',
        'date_published',
        'date_created',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }
    public function participants(): MorphMany
    {
        return $this->morphMany(Participant::class, 'participantable');
    }

    protected static function boot()
    {
        parent::boot();
        static::updating(function ($model) {
            if ($model->isDirty('cover') && ($model->getOriginal('cover') !== null)) {
                Storage::disk('public')->delete($model->getOriginal('cover'));
            }
        });
        static::deleted(function ($model) {
            if ($model->isForceDeleting()) {
                $directory = Str::of(storage_path('app/public/events/' . Auth::user()->branch_id . '/' . Auth::user()->id . '/' . $model->date_created))->replace("/", '\\');
                File::deleteDirectory($directory);
            }
            if ($model->isForceDeleting()) {
                $model->comments()->forceDelete();
            } else {
                $model->comments()->delete();
            }
        });
        static::restored(function ($model) {
            $model->comments()->restore();
        });
    }
}
