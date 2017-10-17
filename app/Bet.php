<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents a person. This person can be a nØfflan, personal or other human being.
 * Cats are not allowed.
 *
 * @author Jonas Dahl <jonadahl@kth.se>
 */
class Bet extends Model {
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $fillable = [
        'time', 'user_id', 'sm_id'
    ];

    protected $dates = [
        'time'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
