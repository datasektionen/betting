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
        'ended_at'
    ];

    public function bets() {
        return $this->hasMany('App\Bet');
    }

    public function winningBets() {
        $winners = [];
        foreach ($this->bets as $bet) {
            if (count($winners) === 0) {
                $winners = [$bet];
                continue;
            }
            if ($bet->time->diffInMinutes($sm->ended_at) < $winners[0]->time->diffInMinutes($sm->ended_at)) {
                $winners = [$bet];
                continue;
            }
            if ($bet->time->diffInMinutes($sm->ended_at) === $winners[0]->time->diffInMinutes($sm->ended_at)) {
                $winners[] = $bet;
                continue;
            }
        }
        return $winners;
    }
}
