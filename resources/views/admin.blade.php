@extends('master')

@section('main')
<header>
    <div class="header-inner">
        <div class="row">
            <h2>Välkommen till Adminsidan, ärade Vice Talman</h2>
        </div>
        <div class="clear"></div>
    </div>
</header>
<div id="content">
    {{ !empty($error) ? $error : '' }}
    {{ !empty(session('error')) ? session('error') : '' }}
    {{ !empty($success) ? $success : '' }}
    {{ !empty(session('success')) ? session('success') : '' }}
    <h1>Detta är adminsidan, här kan du göra saker som bara admins kan göra.</h1>
    @if ($sm->ended_at === null)
        <p>Just nu är det dag {{ $sm->day }} av SM, och SM är {{ $sm->isLive() ? 'live' : 'inte live' }}.</p>
    @else
        <p>Just nu är SM inte live, och det senaste SM som kördes var {{ $sm->name }}.</p>
    @endif
    @if ($sm->isLive() && $sm->ended_at === null) // LN
        <form action="/sm/day/{{$sm->day + 1}}" method="POST">
            @csrf
            <button type="submit" class="button theme-color">Nästa dag</button>
        </form>
        <br>
        <form action="/sm/end/" method="POST">
            @csrf
            <button type="submit" class="button theme-color">Stäng Betting</button>
        </form>
    @endif
    @if (!$sm->isLive()) // nL
        <form action="/sm/live" method="POST">
            @csrf
            <button type="submit" class="button theme-color">Live</button>
        </form>
    @endif
    @if ($sm->isLive() && $sm->ended_at === null) //LN
    @endif
    @if ($sm->isLive() && $sm->ended_at !== null) //LnN
        <form action="/sm/create/" method="POST">
            @csrf
            <label for="SM-name">Namn på SM/VM:</label><br>
            <input type="text" id="SM-name" name="name" placeholder="Budget-VM" required><br><br>
            <button type="submit" class="button theme-color">Öppna Betting</button>
        </form>
    @endif
</div>
@endsection