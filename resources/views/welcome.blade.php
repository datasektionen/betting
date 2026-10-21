@extends('master')

@section('main')
<header>
    <div class="header-inner">
        <div class="row">
            <h2>När tror du {{ $sm->name }} kommer sluta?</h2>
        </div>
        <div class="clear"></div>
    </div>
</header>
<div id="content">
    {{ !empty($error) ? $error : '' }}
    {{ !empty(session('error')) ? session('error') : '' }}
    {{ !empty($success) ? $success : '' }}
    {{ !empty(session('success')) ? session('success') : '' }}
    @if (empty(Auth::user()))
    <p><a href="/login">Logga in</a> för att betta på när SM slutar.</p>
    @else
        @if (!empty($bet))
        <div class="form">
            <h4>Din gissning, {{ Auth::user()->name }}:</h4>
            <div class="input">
                <h2 for="day">Dag</h2>
                <input type="text" class="time" name="day" value="{{ $bet->day }}" disabled="disabled" />
                <br/>
                <h2 for="hours">Klockslag</h2>
                <input type="text" class="time" name="hours" value="{{ $bet->time->hour < 10 ? '0' : '' }}{{ $bet->time->hour }}" disabled="disabled" />
                <span class="colon">:</span>
                <input type="text" class="time" name="minutes" value="{{ $bet->time->minute < 10 ? '0' : '' }}{{ $bet->time->minute }}" disabled="disabled" />
            </div>
        </div>
        @elseif (!$sm->isLive())
        <form method="post">
            {{ csrf_field() }}
            <div class="form">
                <div class="input">
                    <h1 for="day">Dag</h1>
                    <input type="number" min="1" max="5" class="time" name="day" placeholder="" />
                    <br/>
                    <h1 for="hours">Klockslag</h1>
                    <input type="number" min="0" max="23" class="time" name="hours" placeholder="" />
                    <span class="colon">:</span>
                    <input type="number" min="0" max="59" class="time" name="minutes" placeholder="" />
                </div>
                <div class="input">
                    <input type="submit" class="button theme-color" value="Betta" placeholder="" />
                    <br/>
                    <br/>
                    <br/>
                    <p>Du kan inte ändra ditt bet efter att du lagt det. Den som vinner får räkna med att dess namn står på denna sida fram till nästa SM, som en trofé.</p>
                </div>
            </div>
        </form>
    @endif
    @endif
    @if ($sm->isLive())
    <h2>Alla gissningar</h2>
    <table class="onehunna">
        <thead>
            <tr>
                <th>Namn</th>
                <th>Gissning</th>
            </tr>
        </thead>
        @forelse ($bets as $bet) 
        <tr{!! $bet->day < $sm->day || ($bet->day == $sm->day && $bet->time->lt(Carbon\Carbon::now())) ? ' style="opacity: 0.5; background: #fff"' : '' !!}>
            <td>
                {{ $bet->user->name }}
            </td>
            <td>
                &emsp; kl. {{ $bet->time->format('H:i') }} &emsp; Dag {{ $bet->day }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="2">Det finns inga gissningar att visa.</td>
        </tr>
        @endforelse
    </table>
    @endif
</div>
@endsection