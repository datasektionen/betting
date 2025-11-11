<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Jumbojett\OpenIDConnectClient;

/**
* Authentication controller. Handles login via sso.datasektionen.se.
*
* @author Jonas Dahl <jonas@jdahl.se>, Rasmus Söderhielm <rasmus.soderhielm@gmail.com>
* @version 2025-11-10
*/
class AuthController {
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private OpenIDConnectClient $oidc;

	function __construct() {
        $this->oidc = new OpenIDConnectClient(
            env('OIDC_PROVIDER'),
            env('OIDC_ID'),
            env('OIDC_SECRET')
        );
        $this->oidc->setRedirectURL(env('REDIRECT_URL'));
    }

	/**
	* The logout url. Redirects to main page with success message.
	* 
	* @return view the welcome view
	*/
	public function getLogout() {
		Auth::logout();
		Session::forget('admin');
		return redirect('/')
			->with('success', 'Du är nu utloggad.');
	}

	/**
	* The login page. Just redirects to sso.
	* 
	* @return redirect to sso.datasektionen.se
	*/
	public function getLogin(Request $request) {
		return $this->oidc->authenticate();
	}

	/**
	* When login is complete, sso will redirect us here. Now verify the login.
	* 
	* @return redirect to main page or intended page
	*/
	public function getLoginComplete() {
		if ($this->oidc->authenticate() === FALSE) {
			return redirect('/')->with('error', 'Du loggades inte in.');
		}

        $kthId = $this->oidc->getVerifiedClaims('sub');

		$user = User::createIfNotExistsOrFail($kthId);

		Auth::login($user);

		return redirect()->intended('/')->with('success', 'Du loggades in.');
	}
}
