<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

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

    public function winningGroups() {
        $bets = $this
            ->bets()
            ->groupBy('n0lle_group')
            ->select(DB::raw("to_char(to_timestamp(avg((extract(epoch from time)))), 'YYYY-MM-DD HH24:MI:SS') as time"), 'n0lle_group', DB::raw('null as user_id'))
            ->orderBy('time')
            ->get();
        $winners = [];
        foreach ($bets as $bet) {
            if (count($winners) === 0) {
                $winners = [$bet];
                continue;
            }
            if ($bet->time->diffInSeconds($this->ended_at) < $winners[0]->time->diffInSeconds($this->ended_at)) {
                $winners = [$bet];
                continue;
            }
            if ($bet->time->diffInSeconds($this->ended_at) === $winners[0]->time->diffInSeconds($this->ended_at)) {
                $winners[] = $bet;
                continue;
            }
        }
        return $winners;
    }
}
