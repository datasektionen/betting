@extends('master')

@section('title', 'Bettings Adminsida')

@section('main')
<header>
    <div class="header-inner">
        <div class="row">
            <h2>Välkommen&nbsp;till&nbsp;Adminsidan, ärade&nbsp;Vice&nbsp;Talman</h2>
        </div>
        <div class="clear"></div>
    </div>
</header>
<div id="content">
    <div id="flash-messages" style="height: 25px;">
        {{ !empty($error) ? $error : '' }}
        {{ !empty(session('error')) ? session('error') : '' }}
        {{ !empty($success) ? $success : '' }}
        {{ !empty(session('success')) ? session('success') : '' }}
    </div>
    <!--<h1>Detta är adminsidan, här kan du göra saker som bara admins kan göra.</h1>-->
    <div style="display: flex; justify-content: center; align-items: flex-end; gap: 10px;">
        <form action="/sm/create/" method="POST" style="display: flex; flex-direction: column; gap: 10px;">
            @csrf
            <label for="SM-name" style="margin: -5px 5;">Namn&nbsp;på SM/VM:</label>
            @if (!$sm->isEnded())
                <input type="text" id="SM-name" name="name" placeholder="{{ $sm->name }}" required style="width: 100%; box-sizing: border-box;" disabled>
            @else
                <input type="text" id="SM-name" name="name" placeholder="Schmungus-VM" required style="width: 100%; box-sizing: border-box;">
            @endif
            <button type="submit" class="button theme-color" style="width: 100%; box-sizing: border-box;" @disabled(!$sm->isEnded())>
                Öppna&nbsp;Betting
            </button>
        </form>
        <form action="/sm/live" method="POST" style="display: flex; flex-direction: column; gap: 10px;">
            @csrf
            @if (!$sm->isLive())
                <b style="text-align: center; color: green">Det&nbsp;går att&nbsp;betta!</b>
            @elseif (!$sm->isEnded())
                <b style="text-align: center; color: red">Inga&nbsp;mera&nbsp;bets!</b>
            @endif
            <button type="submit" class="button theme-color" @disabled($sm->isLive())>
                Sluta&nbsp;ta emot&nbsp;bets
            </button>
        </form>
        <div style="position: relative; display: flex; align-items: flex-end;">
            <form action="/sm/day/{{$sm->day + 1}}" method="POST" style="display: flex; flex-direction: column; gap: 10px;">
                @csrf
                @if ($sm->adjurned)
                    <b style="text-align: center; color: red">{{ $sm->name }}<br>är adjunderat!</b>
                @endif
                @if ($sm->isLive() && !$sm->isEnded())
                    <b style="text-align: center">Dag {{ $sm->day }}</b>
                @endif
                <button type="submit" class="button theme-color" @disabled(!$sm->isLive() || $sm->isEnded())>
                    &nbsp;&nbsp;Nästa&nbsp;dag&nbsp;&nbsp;
                </button>
            </form>
            <div style="position: absolute; top: 100%; margin-top: 10px; display: flex; flex-direction: column; gap: 10px; align-items: flex-start;">
                <form action="/sm/adjurn" method="POST">
                    @csrf
                    <button type="submit" class="button theme-color">
                        @if ($sm->adjurned)
                            &nbsp;&nbsp;&nbsp;&nbsp;Öppna&nbsp;&nbsp;&nbsp;
                        @else
                            Adjurnera
                        @endif
                    </button>
                </form>

                @if (!$sm->isEnded() && $sm->day > 1)
                    <form action="/sm/day/{{$sm->day - 1}}" method="POST">
                        @csrf
                        <button type="submit" class="button theme-color">
                            Oops,&nbsp;gå&nbsp;tillbaka&nbsp;en&nbsp;dag
                        </button>
                    </form>
                @endif
            </div>
        </div>
        <form action="/sm/end/" method="POST">
            @csrf
            <button type="submit" class="button theme-color" @disabled(!$sm->isLive() || $sm->isEnded())>
                Stäng Betting!
            </button>
        </form>
    </div>
</div>
<script>
    setTimeout(() => {
        const messages = document.getElementById('flash-messages');
        if (messages) {
            messages.style.transition = 'opacity 0.5s ease';
            messages.style.opacity = '0';
            setTimeout(() => {messages.replaceChildren();}, 500);
        }
    }, 5000);
</script>
@endsection