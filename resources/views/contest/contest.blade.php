@extends('../layout')

@section('title', 'Contest')

<style>
    body {
        @if (!str_contains($contest->place->image, 'http'))
            background-image: url('{{ Request::root() . '/' . $contest->place->image }}');
        @else
            background-image: url('{{ $contest->place->image }}');
        @endif
        /* background-repeat: no-repeat;
        background-size: cover;
        background-position: center; */
        background-repeat: repeat;
        background-size: 30%;
    }

    .players {
        display: flex;
    }

    .player {
        padding: 15px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    .player img {
        width: 50%;
        height: 100%;
    }

    tr td:nth-child(2) {
        text-align: right;
    }

    .winner {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>

@section('content')
    <h1 class="text-2xl mb-2">Contest</h1>
    <hr>
    <div class="players">
        @if ($contest->win !== null)
            <div class="winner text-center">
                <p>Game is over!</p>
                <p>🏆 The winner is {{ $contest->win ? $hero->name : $enemy->name }}</p>
            </div>
        @endif
        <div class="player w-6/12">
            <h1 class="text-2xl mb-5">{{ $hero->name }}</h1>
            <img src="/kokut.gif"
                class="border-2 border-slate-900 rounded shadow-2xl shadow-slate-800 hover:shadow-slate-400 transition-shadow">
            <div class="attributes mt-5 border border-slate-300 flex flex-col rounded bg-slate-800 p-5 items-center">
                <div>
                    <table class="table-sm">
                        <tr>
                            <td>❤️ Health</td>
                            <td>{{ $contest->characters->first()->pivot->hero_hp }}</td>
                        </tr>
                        <tr>
                            <td>🛡️ Defence</td>
                            <td>{{ $hero->defence }}</td>
                        </tr>
                        <tr>
                            <td>💪 Strength</td>
                            <td>{{ $hero->strength }}</td>
                        </tr>
                        <tr>
                            <td>🎯 Accuracy</td>
                            <td>{{ $hero->accuracy }}</td>
                        </tr>
                        <tr>
                            <td>🪄 Magic</td>
                            <td>{{ $hero->magic }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="player w-6/12">
            <h1 class="text-2xl mb-5">{{ $enemy->name }}</h1>
            <img src="/cica.png"
                class="border-2 border-slate-900 rounded shadow-2xl shadow-slate-800 hover:shadow-slate-400 transition-shadow">
            <div class="attributes mt-5 border border-slate-300 flex flex-col rounded bg-slate-800 p-5 items-center">
                <table class="table-sm">
                    <tr>
                        <td>❤️ Health</td>
                        <td>{{ $contest->characters->first()->pivot->enemy_hp }}</td>
                    </tr>
                    <tr>
                        <td>🛡️ Defence</td>
                        <td>{{ $enemy->defence }}</td>
                    </tr>
                    <tr>
                        <td>💪 Strength</td>
                        <td>{{ $enemy->strength }}</td>
                    </tr>
                    <tr>
                        <td>🎯 Accuracy</td>
                        <td>{{ $enemy->accuracy }}</td>
                    </tr>
                    <tr>
                        <td>🪄 Magic</td>
                        <td>{{ $enemy->magic }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    @if ($contest->win === null)
        <div class="actions buttons mt-3 w-6/12 justify-center">
            <a href="{{ route('contests.attack', ['contest' => $contest, 'type' => 'melee']) }}"
                class="btn btn-primary">Melee</a>
            <a href="{{ route('contests.attack', ['contest' => $contest, 'type' => 'ranged']) }}"
                class="btn btn-primary">Ranged</a>
            <a href="{{ route('contests.attack', ['contest' => $contest, 'type' => 'magic']) }}"
                class="btn btn-primary">Magic</a>
        </div>
    @endif

    <div class="border border-slate-300 flex flex-col rounded bg-slate-800 p-5 mt-5">
        <h1 class="text-xl">History</h1>
        <hr>
        <p class="mt-5">{!! $contest->history !!}</p>
    </div>
@endsection
