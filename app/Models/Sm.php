<?php

namespace App\Models;

use App\Models\Bet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Represents a person. This person can be a nØfflan, personal or other human being.
 * Cats are not allowed.
 *
 * @author Jonas Dahl <jonadahl@kth.se>
 */
class Sm extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'ended_at',
        'live_at',
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
            'ended_at' => 'datetime',
            'live_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'day' => 'integer',
        ];
    }

    public static function active(): Sm {
        return Sm::latest('id')->first() ?? Sm::create([
            'name' => 'Budget-SM'
        ]);
    }

    public function bets(): HasMany {
        return $this->hasMany(Bet::class)
            ->orderBy('day', 'asc')
            ->orderBy('time', 'asc');
    }

    public function isLive(): bool {
        return $this->live_at !== null;
    }

    /**
     * Check whether a bet is within the winning interval
     */
    private function isWinner(Bet $bet): bool {
        if ($bet->day !== $this->day) return false;

        $betTime = $bet->time->hour * 60 + $bet->time->minute;
        $endTime = $this->ended_at->hour * 60 + $this->ended_at->minute;

        // Calculate the difference in minutes between the bet and the end time, taking into account that the day wraps around at midnight.
        $diff = abs($betTime - $endTime);
        $diff = min($diff, 1440 - $diff);

        // Now someone wins if they are within ±5 minutes of the end time.
        // Change here if you want to make it easier – or harder >:) – to win.
        return $diff <= 5;
    }

    public function winningBets(): array {
        $winners = [];

        // Add every bet within winning interval
        foreach ($this->bets as $bet) {
            if ($this->isWinner($bet)) $winners[] = $bet;
        }
        return $winners;
    }
}
