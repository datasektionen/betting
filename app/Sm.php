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
        $lower = $this->ended_at - 8;
        $upper = $this->ended_at + 8;

        $timeInMin = $bet->time;

        if ($timeInMin <= upper && $timeInMin >= lower) return true;
        else return false;
    }

    public function winningBets() {
        $winners = [];

        // Add every bet within winning interval
        foreach ($this->bets as $bet) {
            if (isWinner($bet)) $winners[] = $bet;
        }

        if (count($winners) !== 0) return $winners;

        // Fallback: no winner within 15 minutes means look for closest time
        foreach ($this->bets as $bet) {
            $betDiff = $bet->time->diffInMinutes($this->ended_at);

            if (count($winners) === 0) {
                $winners = [$bet];
                continue;
            }
            if ($betDiff < $winners[0]->time->diffInMinutes($this->ended_at)) {
                $winners = [$bet];
                continue;
            }
            if ($betDiff === $winners[0]->time->diffInMinutes($this->ended_at)) {
                $winners[] = $bet;
                continue;
            }
        }
        return $winners;
    }
}
