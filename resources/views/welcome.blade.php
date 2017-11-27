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
                    <input type="text" class="time" name="hours" placeholder="23" />
                    <span class="colon">:</span>
                    <input type="text" class="time" name="minutes" placeholder="59" />
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
        <tr{!! $bet->time->lt(Carbon\Carbon::now()) ? ' style="opacity: 0.5; background: #fff"' : '' !!}>
            <td>
                {{ $bet->user->name }}
            </td>
            <td>
                {{ $bet->time->format('H:i') }}
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