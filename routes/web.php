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

Route::get('/sm/live', function () {
	$sm = Sm::select('*')->orderBy('id', 'DESC')->first();
	$sm->live_at = \Carbon\Carbon::now();
	$sm->save();
    return redirect('/');
});

Route::get('/', function () {
	$sm = Sm::active();
	if ($sm === null) {
		$sm = Sm::create(['name' => 'Sektionsrundvandringen']);
	}

	if (!empty($sm->ended_at)) {
        $bets = $sm->bets()->select(DB::raw("to_char(time, 'YYYY-MM-DD HH24:MI:SS') as time"), 'n0lle_group', 'user_id');
        $bets = $sm
            ->bets()
            ->groupBy('n0lle_group')
            ->select(DB::raw("to_char(to_timestamp(avg((extract(epoch from time)))), 'YYYY-MM-DD HH24:MI:SS') as time"), 'n0lle_group', DB::raw('null as user_id'))
            ->union($bets)
            ->orderBy('time')
            ->get();
	    return view('ended')
	    	->with('bets', $bets)
	    	->with('sm', $sm);
	}

	if (Auth::user()) {
		$bet = $sm->bets()->where('user_id', Auth::user()->id)->first();
	} else {
		$bet = null;
	}

    if ($sm->isLive()) {
        $bets = $sm->bets()->select(DB::raw("to_char(time, 'YYYY-MM-DD HH24:MI:SS') as time"), 'n0lle_group', 'user_id');
        $bets = $sm
            ->bets()
            ->groupBy('n0lle_group')
            ->select(DB::raw("to_char(to_timestamp(avg((extract(epoch from time)))), 'YYYY-MM-DD HH24:MI:SS') as time"), 'n0lle_group', DB::raw('null as user_id'))
            ->union($bets)
            ->orderBy('time')
            ->get();
    } else {
        $bets = collect([]);
    }

    return view('welcome')
    	->with('bet', $bet)
    	->with('bets', $bets)
    	->with('sm', $sm);
});

Route::post('/', function (Request $request) {
	if (empty($request->input('hours')) || empty($request->input('minutes'))) {
		return redirect('/')->with('error', 'Fyll i tiden!');
	}
	if (empty($request->input('n0lle_group'))) {
		return redirect('/')->with('error', 'Fyll i din nØllegrupp!');
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
			(intval($request->input('hours')) < 10 ? date('d', strtotime('+1 day')) : date('d')),
			intval($request->input('hours')), 
			intval($request->input('minutes')),
			0,
			'Europe/Stockholm'
		),
        'sm_id' => $sm->id,
        'n0lle_group' => $request->input('n0lle_group')
	]);
	return redirect('/')->with('Ditt bett sparades.');
})->middleware('auth');
