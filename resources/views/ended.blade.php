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
	@foreach ($sm->winningGroups() as $wg)
        <h1 style="text-align: center">
            @if ($wg->n0lle_group == "apollo") Apollo
            @elseif ($wg->n0lle_group == "boomer-aang") Boomer-Aang
            @elseif ($wg->n0lle_group == "cosmetisk-comet") Cosmetisk Comet
            @elseif ($wg->n0lle_group == "drake") Drake
            @elseif ($wg->n0lle_group == "ett-oidentifierat-objekt") Ett oidentifierat objekt
            @elseif ($wg->n0lle_group == "fyrpropellerdrivet-flugplan") Fyrpropellerdrivet Flugplan
            @elseif ($wg->n0lle_group == "gravitation-ingen") GRAVITATION, INGEN
            @elseif ($wg->n0lle_group == "he-he-helium") He-He-Helium
            @elseif ($wg->n0lle_group == "internationella-strutsstationen") Internationella Strutsstationen (I.S.S)
            @elseif ($wg->n0lle_group == "jorden-runt-jetesnabbt") Jorden runt JETesnabbt
            @elseif ($wg->n0lle_group == "kvantresande-korp") Kvantresande Korp
            @elseif ($wg->n0lle_group == "luzz-bightyear") Luzz Bightyear
            @elseif ($wg->n0lle_group == "maxad-matta") Maxad Matta
            @else {{ $wg->n0lle_group }} @endif
            vann med bettet {{ $wg->time->format('H:i:s') }}.
        </h1>
    @endforeach
	@foreach ($sm->winningBets() as $wb) 
	<div class="crop" style="margin: 0 auto;float:none;position:static;display:block;width: 100px; height: 100px; background-image: url('https://zfinger.datasektionen.se/user/{{ $wb->user->kth_username }}/image')"></div>
    <h1 style="text-align: center">{{ $wb->user->name }} vann med bettet {{ $wb->time->format('H:i') }}.</h1>
    @endforeach
    <h2>Alla gissningar</h2>
    <table class="onehunna">
        <thead>
            <tr>
                <th>Namn</th>
                <th>NØllegrupp</th>
                <th>Gissning</th>
            </tr>
        </thead>
        @foreach ($bets as $bet) 
        <tr style="{!!
            ($bet->time->lt(Carbon\Carbon::now()) ? 'opacity: 0.6; background: #fff;' : '') .
            (empty($bet->user) ? 'font-weight: bold;' : '')
        !!}">
            <td>
                @if (isset($bet->user))
                    {{ $bet->user->name }}
                @endif
            </td>
            <td>
                @if ($bet->n0lle_group == "apollo") Apollo
                @elseif ($bet->n0lle_group == "boomer-aang") Boomer-Aang
                @elseif ($bet->n0lle_group == "cosmetisk-comet") Cosmetisk Comet
                @elseif ($bet->n0lle_group == "drake") Drake
                @elseif ($bet->n0lle_group == "ett-oidentifierat-objekt") Ett oidentifierat objekt
                @elseif ($bet->n0lle_group == "fyrpropellerdrivet-flugplan") Fyrpropellerdrivet Flugplan
                @elseif ($bet->n0lle_group == "gravitation-ingen") GRAVITATION, INGEN
                @elseif ($bet->n0lle_group == "he-he-helium") He-He-Helium
                @elseif ($bet->n0lle_group == "internationella-strutsstationen") Internationella Strutsstationen (I.S.S)
                @elseif ($bet->n0lle_group == "jorden-runt-jetesnabbt") Jorden runt JETesnabbt
                @elseif ($bet->n0lle_group == "kvantresande-korp") Kvantresande Korp
                @elseif ($bet->n0lle_group == "luzz-bightyear") Luzz Bightyear
                @elseif ($bet->n0lle_group == "maxad-matta") Maxad Matta
                @else {{ $bet->n0lle_group }} @endif
            </td>
            <td>
                {{ isset($bet->user) ? $bet->time->format('H:i') : $bet->time->format('H:i:s') }}
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
