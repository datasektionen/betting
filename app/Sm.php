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

    public function winningBets() {
        $winners = [];
        foreach ($this->bets as $bet) {
            if (count($winners) === 0) {
                $winners = [$bet];
                continue;
            }
            if ($bet->time->diffInMinutes($this->ended_at) < $winners[0]->time->diffInMinutes($this->ended_at)) {
                $winners = [$bet];
                continue;
            }
            if ($bet->time->diffInMinutes($this->ended_at) === $winners[0]->time->diffInMinutes($this->ended_at)) {
                $winners[] = $bet;
                continue;
            }
        }
        return $winners;
    }
}
