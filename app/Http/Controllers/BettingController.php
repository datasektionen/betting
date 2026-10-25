<?php

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Models\Sm;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BettingController extends Controller
{
    public function index(): View {
        $sm = Sm::active();

        if (!empty($sm->ended_at)) {
            return view('ended')
                ->with('bets', $sm->bets()->get())
                ->with('sm', $sm);
        }

        if (Auth::user()) {
            $bet = Auth::user()->bet();
        } else {
            $bet = null;
        }

        return view('welcome')
            ->with('bet', $bet)
            ->with('bets', $sm->isLive() ? $sm->bets()->get() : collect([]))
            ->with('sm', $sm);
    }

    public function bet(Request $request): RedirectResponse {
        if (empty($request->input('hours')) || empty($request->input('minutes'))) {
            return redirect('/')->with('error', 'Fyll i tiden!');
        }
        if (empty($request->input('day'))) {
            return redirect('/')->with('error', 'Fyll i dagen!');
        }
        $sm = Sm::active();
        if (Auth::user()->hasBetted()) {
            return redirect('/')->with('error', 'Du kan ju inte betta när du redan har bettat!');
        }
        if ($sm->isLive()) {
            return redirect('/')->with('error', 'Tyvärr är du för sent ute för att få vara med i leken');
        }
        $bet = Bet::create([
            'user_id' => Auth::user()->id,
            'day' => intval($request->input('day')),
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

    }

    public function end(): RedirectResponse {
        if (!Auth::check()) { // Add AdminCheck here! Typ !Auth::user()->isAdmin()
			return redirect('/')->with('error', 'Du måste vara inloggad för att komma åt adminsidan din skurk.');
		}
        $sm = Sm::active();
        $sm->ended_at = Carbon::now('Europe/Stockholm');
        $sm->save();
        return redirect('admin')->with('success', 'Betting är stängd!');
    }

    // Update the day of the current SM, default is 1 when created.
    // This is used to keep track of the current day in the SM.
    // Update to next day when the current day is over.
    public function day(int $day): RedirectResponse {
        if (!Auth::check()) { // Add AdminCheck here! Typ !Auth::user()->isAdmin()
			return redirect('/')->with('error', 'Du måste vara inloggad för att komma åt adminsidan din skurk.');
		}
        $sm = Sm::active();
        $sm->day = $day;
        $sm->save();
        return redirect('admin')->with('success', 'Uppdaterade dag till: ' . $day);
    }

    public function create(Request $request): RedirectResponse {
        if (!Auth::check()) { // Add AdminCheck here! Typ !Auth::user()->isAdmin()
			return redirect('/')->with('error', 'Du måste vara inloggad för att komma åt adminsidan din skurk.');
		}
        $sm = Sm::create(['name' => $request->input('name'), 'day' => 1]);
        return redirect('admin')->with('success', 'Skapade SM med namn: ' . $request->input('name'));
    }

    public function live(): RedirectResponse {
		if (!Auth::check()) { // Add AdminCheck here! Typ !Auth::user()->isAdmin()
			return redirect('/')->with('error', 'Du måste vara inloggad för att komma åt adminsidan din skurk.');
		}
        $sm = Sm::active();
        $sm->live_at = \Carbon\Carbon::now();
        $sm->save();
        return redirect('admin')->with('success', 'SM är nu live! Inga mer bets!!!');
    }

    public function skurk(): RedirectResponse {
        return redirect('/')->with('error', 'Du kan inte göra det här som GET, skurk!');
    }

	public function getAdmin(): RedirectResponse|\Illuminate\Contracts\View\View {
		if (!Auth::check()) { // Add AdminCheck here! Typ !Auth::user()->isAdmin()
			return redirect('/')->with('error', 'Du måste vara inloggad för att komma åt adminsidan din skurk.');
		}

		// TODO: Uncomment this when we have a proper admin system in place. For now, everyone can access the admin page.
		// if (!Auth::user()->isAdmin()) {
		// 	return view('welcome')->with('error', 'Du har inte behörighet att komma åt adminsidan.');
		// }

		return view('admin')
            ->with('sm', Sm::active());
	}
}
