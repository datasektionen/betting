<?php namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use GuzzleHttp\Client;
use Exception;
use DB;
use Auth;
use Carbon\Carbon;
use StdClass;

/**
 * Represents a person. This person can be a nØfflan, personal or other human being.
 * Cats are not allowed.
 *
 * @author Jonas Dahl <jonadahl@kth.se>
 */
class User extends Authenticatable {
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token'
    ];

    /**
     * Finds user by KTH username.
     * 
     * @param  string $kthUsername the kth username
     * @return User or null
     */
    public static function findByKTHUsername($kthUsername) {
        return User::where('kth_username', '=', $kthUsername)->first();
    }

    /**
     * Finds user by KTH username.
     * 
     * @param  string $kthUsername the kth username
     * @return User or null
     */
    public static function findByUgKthId($ugKthId) {
        return User::where('ugkthid', '=', $ugKthId)->first();
    }

    public static function createIfNotExistsOrFail($ugKthId) {
        $user = User::findByUgKthId($ugKthId);
        if ($user === null) {
            $client = new Client();
            try {
                $res = $client->request('GET', env('HODIS_API_URL') . '/ugkthid/' . $ugKthId);
            } catch(Exception $e){
                abort(404);
            }
            if ($res->getStatusCode() !== 200) {
                abort(404);
            }
            $data = json_decode($res->getBody());

            $user = new User;
            $user->ugkthid = $ugKthId;
            $user->kth_username = $data->uid;
            $user->email = $data->uid . "@kth.se";
            $user->name = $data->cn;
        }
        $user->save();
        return $user;
    }

    public function hasBetted() {
        return Bet::where('user_id', $this->id)->count() > 0;
    }

    public function bet() {
        return Bet::where('user_id', $this->id)->first();
    }
}
