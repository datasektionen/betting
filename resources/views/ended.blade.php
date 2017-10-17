@extends('master')

@section('main')
<header>
    <div class="header-inner">
        <div class="row">
            <h2>{{ $sm->name }} slutade {{ $sm->ended_at->format('H:i') }}</h2>
        </div>
        <div class="clear"></div>
    </div>
</header>
<div id="content">
	@foreach ($sm->winningBets() as $wb) 
	<div class="crop" style="margin: 0 auto;float:none;position:static;display:block;width: 100px; height: 100px; background-image: url('https://zfinger.datasektionen.se/user/{{ $wb->user->kth_username }}/image')"></div>
    <h1 style="text-align: center">{{ $wb->user->name }} vann med bettet {{ $wb->time->format('H:i') }}.</h1>
    @endforeach
    <h2>Alla gissningar</h2>
    <table class="onehunna">
        <thead>
            <tr>
                <th>Namn</th>
                <th>Gissning</th>
            </tr>
        </thead>
        @foreach ($bets as $bet) 
        <tr{!! $bet->time->lt(Carbon\Carbon::now()) ? ' style="opacity: 0.5; background: #fff"' : '' !!}>
            <td>
                {{ $bet->user->name }}
            </td>
            <td>
                {{ $bet->time->format('H:i') }}
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection