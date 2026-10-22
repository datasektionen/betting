<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use GuzzleHttp\Client;
use Exception;

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
    public static function findByKTHUsername(string $kthUsername): User|null {
        return User::where('kth_username', '=', $kthUsername)->first();
    }

    public static function createIfNotExistsOrFail(string $kthUsername): User {
        $user = User::findByKTHUsername($kthUsername);
        if ($user === null) {
            $client = new Client();
            try {
                $res = $client->request('GET', env('SSO_API_URL') . '/api/users?format=single&u=' . $kthUsername);
            } catch(Exception $e){
                abort(404);
            }
            if ($res->getStatusCode() !== 200) {
                abort(404);
            }
            $data = json_decode($res->getBody());

            $user = new User;
            $user->kth_username = $kthUsername;
            $user->email = $data->email;
            $user->name = $data->firstName . ' ' . $data->familyName;
        }
        $user->save();
        return $user;
    }

    public function hasBetted(): bool {
        return $this->bet() !== null;
    }

    public function bet(): Bet|null {
        return Sm::active()->bets()->where('user_id', $this->id)->first();
    }
}
