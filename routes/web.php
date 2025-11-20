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
Route::get('/login-complete', 'AuthController@getLoginComplete');

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

Route::get('/sm/live', function () {
	$sm = Sm::select('*')->orderBy('id', 'DESC')->first();
	$sm->live_at = \Carbon\Carbon::now();
	$sm->save();
    return redirect('/');
});

Route::get('/', function () {
	$sm = Sm::active();
	if ($sm === null) {
		$sm = Sm::create(['name' => 'Budget-SM']);
	}

	if (!empty($sm->ended_at)) {
	    return view('ended')
	    	->with('bets', $sm->bets()->orderBy('time')->get())
	    	->with('sm', $sm);
	}

	if (Auth::user()) {
		$bet = $sm->bets()->where('user_id', Auth::user()->id)->first();
	} else {
		$bet = null;
	}

    return view('welcome')
    	->with('bet', $bet)
    	->with('bets', $sm->isLive() ? $sm->bets()->orderBy('time')->get() : collect([]))
    	->with('sm', $sm);
});

Route::post('/', function (Request $request) {
	if (empty($request->input('hours')) || empty($request->input('minutes'))) {
		return redirect('/')->with('error', 'Fyll i tiden!');
	}
	$sm = Sm::select('*')->orderBy('id', 'DESC')->first();
	if (Auth::user()->hasBetted($sm)) {
		return redirect('/')->with('error', 'Du kan ju inte betta när du redan har bettat!');
	}
	if ($sm->isLive()) {
		return redirect('/')->with('error', 'Tyvärr är du för sent ute för att få vara med i leken');
	}
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
