@extends('master')

@section('main')
<header>
    <div class="header-inner">
        <div class="row px-2">
            <h2 class="!text-2xl md:!text-4xl">När tror du {{ $sm->name }} kommer sluta?</h2>
        </div>
        <div class="clear"></div>
    </div>
</header>
<div id="content" class="flex flex-col items-center !px-0 !pt-10">
    <div class="!text-red-500 rounded p-2 px-5">
    {{ !empty($error) ? $error : '' }}
    {{ !empty(session('error')) ? session('error') : '' }}
    {{ !empty($success) ? $success : '' }}
    {{ !empty(session('success')) ? session('success') : '' }}
    </div>
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
                    <input type="number" min="0" max="23" class="time !text-xl !w-32 sm:!w-60 sm:!text-6xl" name="hours" placeholder="" />
                    <span class="colon !text-2xl">:</span>
                    <input type="number" min="0" max="59" class="time !text-xl !w-32 sm:!w-60 sm:!text-6xl" name="minutes" placeholder="" />
                </div>
                <select class="my-10 max-w-[20rem]" name="n0lle_group" id="n0lle_group" required>
                    <option value="">Välj n0llegrupp</option>
                    <option value="apollo">Apollo</option>
                    <option value="boomer-aang">Boomer-Aang</option>
                    <option value="cosmetisk-comet">Cosmetisk Comet</option>
                    <option value="drake">Drake</option>
                    <option value="ett-oidentifierat-objekt">Ett oidentifierat objekt</option>
                    <option value="fyrpropellerdrivet-flugplan">Fyrpropellerdrivet Flugplan</option>
                    <option value="gravitation-ingen">GRAVITATION, INGEN</option>
                    <option value="he-he-helium">He-He-Helium</option>
                    <option value="internationella-strutsstationen">Internationella Strutsstationen (I.S.S)</option>
                    <option value="jorden-runt-jetesnabbt">Jorden runt JETesnabbt</option>
                    <option value="kvantresande-korp">Kvantresande Korp</option>
                    <option value="luzz-bightyear">Luzz Bightyear</option>
                    <option value="maxad-matta">Maxad Matta</option>
                </select>
                <div class="input">
                    <input type="submit" class="button theme-color" value="Betta" placeholder="" />
                    <br/>
                    <br/>
                    <br/>
                    <p>Du kan inte ändra ditt bet efter att du lagt det. Den som vinner får räkna med att dess namn står kvar, som en trofé.</p>
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