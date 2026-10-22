<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents a person. This person can be a nØfflan, personal or other human being.
 * Cats are not allowed.
 *
 * @author Jonas Dahl <jonadahl@kth.se>
 */
class Sm extends Model {
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $fillable = [
        'day',
        'name'
    ];
    protected $dates = [
        'ended_at',
        'live_at'
    ];

    public static function active() {
        return self::select('*')->orderBy('id', 'DESC')->first();
    }

    public function bets() {
        return $this->hasMany('App\Bet')
            ->orderBy('day', 'asc')
            ->orderBy('time', 'asc');
    }

    public function isLive() {
        return $this->live_at !== null;
    }

    /**
     * Check whether a bet is within the winning interval
     *
     */
    private function isWinner($bet) {
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

    public function winningBets() {
        $winners = [];

        // Add every bet within winning interval
        foreach ($this->bets as $bet) {
            if ($this->isWinner($bet)) $winners[] = $bet;
        }
        return $winners;
    }
}
