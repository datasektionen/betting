<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Sm;

Route::get('/logout', 'AuthController@getLogout')->middleware('auth');
Route::get('/login', 'AuthController@getLogin');
Route::get('/login-complete/{token}', 'AuthController@getLoginComplete');

Route::get('/sm/end', function () {
	$sm = Sm::select('*')->orderBy('id', 'DESC')->first();
	$sm->ended_at = Carbon::now('Europe/Stockholm');
	$sm->save();
    return redirect('/');
});

Route::get('/sm/create/{name}', function ($name) {
	$sm = Sm::create(['name' => $name]);
    return redirect('/');
});

Route::get('/', function () {
	$sm = Sm::select('*')->orderBy('id', 'DESC')->first();
	if ($sm === null) {
		$sm = Sm::create(['name' => 'Budget-SM']);
	}
	if (!empty($sm->ended_at)) {
	    return view('ended')
	    	->with('bets', $sm->bets()->orderBy('time')->get())
	    	->with('sm', $sm);
	}



	if (Auth::user()) {
		$bet = Auth::user()->bet();
	} else {
		$bet = null;
	}

    return view('welcome')
    	->with('bet', $bet)
    	->with('bets', App\Bet::select('*')->orderBy('time')->get())
    	->with('sm', $sm);
});

Route::post('/', function (Request $request) {
	if (empty($request->input('hours')) || empty($request->input('minutes'))) {
		return redirect('/')->with('error', 'Fyll i tiden!');
	}
	if (Auth::user()->hasBetted()) {
		return redirect('/')->with('error', 'Du kan ju inte betta när du redan har bettat!');
	}
	$sm = Sm::select('*')->orderBy('id', 'DESC')->first();
	$bet = App\Bet::create([
		'user_id' => Auth::user()->id, 
		'time' => Carbon::create(
			date('Y'),
			date('m'),
			(intval($request->input('hours')) < 17 ? date('d', strtotime('+1 day')) : date('d')),
			intval($request->input('hours')), 
			intval($request->input('minutes')),
			0,
			'Europe/Stockholm'
		),
		'sm_id' => $sm->id
	]);
	return redirect('/')->with('Ditt bett sparades.');
})->middleware('auth');
