<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'participantable_id',
        'participantable_type',

        'name_emergency',
        'relation_emergency',
        'phone_emergency',
        'health_verification',
        'statement_of_agreement',

        'user_id',
        'created_by',
        'updated_by',
        'updated_at',
    ];

    protected $casts = [
        'health_verification' => 'array',
        'statement_of_agreement' => 'array',
    ];

    public function participantable(): MorphTo
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function userCre(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function userUpd(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
