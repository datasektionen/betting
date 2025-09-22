<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

use App\User;
use Auth;
use Session;

/**
* Authentication controller. Handles login via login2.datasektionen.se.
*
* @author Jonas Dahl <jonas@jdahl.se>
* @version 2016-11-23
*/
class AuthController {
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
	* The login page. Just redirects to login2.
	* 
	* @return redirect to login2.datasektionen.se
	*/
	public function getLogin(Request $request) {
		return redirect(env('LOGIN_FRONTEND_URL') . '/login?callback=' . url('/login-complete') . '/');
	}

	/**
	* When login is complete, login2 will redirect us here. Now verify the login.
	* 
	* @param  string $token the token from login2
	* @return redirect to main page or intended page
	*/
	public function getLoginComplete($token) {
		// Send get request to login server
		$client = new Client();
		$res = $client->request('GET', env('LOGIN_API_URL') . '/verify/' . $token . '.json', [
			'form_params' => [
				'format' => 'json',
				'api_key' => env('LOGIN_API_KEY')
			]
		]);

		// We now have a response. If it is good, parse the json and login user
		if ($res->getStatusCode() == 200) {
			$body = json_decode($res->getBody());
			$user = User::createIfNotExistsOrFail($body->ugkthid, $body);

			Auth::login($user);
		} else {
			Auth::logout();
			return redirect('/')->with('error', 'Du loggades inte in.');
		}

		return redirect()->intended('/')->with('success', 'Du loggades in.');
	}
}
