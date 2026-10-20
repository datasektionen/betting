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
        return $this->hasMany('App\Bet');
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
        $lower = $this->ended_at->timestamp - 5 * 60;
        $upper = $this->ended_at->timestamp + 5 * 60;

        $time = $bet->time->timestamp;

        if ($lower <= $time && $time <= $upper) return true;
        else return false;
    }

    public function winningBets() {
        $winners = [];

        // Add every bet within winning interval
        foreach ($this->bets as $bet) {
            if ($this->isWinner($bet)) $winners[] = $bet;
        }
        // if (count($winners) == 0) {
        //     // Sort by time
        //     $winners = $this->bets->sortBy(function ($bet) {
        //         return abs($bet->time->timestamp - $this->ended_at->timestamp);
        //     })->take(1);
        // }
        return $winners;
    }
}
