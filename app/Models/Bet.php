<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a person. This person can be a nØfflan, personal or other human being.
 * Cats are not allowed.
 *
 * @author Jonas Dahl <jonadahl@kth.se>
 */
class Bet extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'sm_id',
        'time',
        'day',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'time' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'day' => 'integer',
        ];
    }

    /**
     * Get the user that made the bet
     *
     * @return User
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the sm that the bet was made on
     *
     * @return Sm
     */
    public function sm(): BelongsTo {
        return $this->belongsTo(Sm::class);
    }
}
