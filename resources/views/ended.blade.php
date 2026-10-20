@extends('master')

@section('main')
<header>
    <div class="header-inner">
        <div class="row">
            <h2>{{ $sm->name }} slutade dag {{ $sm->day }} kl. {{ $sm->ended_at->format('H:i') }}</h2>
        </div>
        <div class="clear"></div>
    </div>
</header>
<div id="content">
    @if (count($sm->winningBets()) > 0)
        @foreach ($sm->winningBets() as $wb)
            @php
                $username = $wb->user->kth_username;
                $opts = [
                    'http' => [
                        'method' => 'GET',
                        'header' => 'Authorization: Bearer ' . env('RFINGER_API_KEY')
                    ]
                ];
                $context = stream_context_create($opts);
                $link = file_get_contents(
                    env('RFINGER_API_URL') . '/' . $username,
                    false,
                    $context
                );
            @endphp

            <div class="crop"
                 style="margin: 0 auto; float: none; position: static; display: block; width: 100px; height: 100px; background-image: url('{{ $link }}')">
            </div>

            <h1 style="text-align: center">
                {{ $wb->user->name }} vann med bettet kl. {{ $wb->time->format('H:i') }} dag {{ $wb->day }}.
            </h1>
        @endforeach
    @else
        <h1 style="text-align: center">Ingen vann.</h1>
        <h3 style="text-align: center">antal rätt gissningar: {{ count($sm->winningBets()) }}</h3>
        @php
            dd($sm->day, $sm->ended_at, $sm->winningBets());
        @endphp
        <img src="{{ asset('images/pictures/image.png') }}"
             style="display: block; margin-left: auto; margin-right: auto; width: 50%;">
    @endif

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
                &emsp; kl. {{ $bet->time->format('H:i') }} &emsp; Dag {{ $bet->day }}
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection