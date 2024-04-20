@extends('../layout')

@section('title', 'Character details')

<style>
    .contests-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    tr td:nth-child(2) {
        text-align: right;
    }
</style>

@section('content')
    <h1 class="text-2xl mb-2">{{ $character->name }}</h1>
    <hr>
    <div class="m-5 border border-slate-300 rounded bg-slate-800 p-5 flex w-2/12">
        <table class="table-sm">
            <tr>
                <td>⚔️ Enemy</td>
                <td>{{ $character->enemy ? 'Yes' : 'No' }}</td>
            </tr>
            <tr>
                <td>🛡️ Defence</td>
                <td>{{ $character->defence }}</td>
            </tr>
            <tr>
                <td>💪 Strength</td>
                <td>{{ $character->strength }}</td>
            </tr>
            <tr>
                <td>🎯 Accuracy</td>
                <td>{{ $character->accuracy }}</td>
            </tr>
            <tr>
                <td>🪄 Magic</td>
                <td>{{ $character->magic }}</td>
            </tr>
        </table>
    </div>
    <div class="buttons">
        <form action="{{ route('contests.store', ['character' => $character]) }}" method="POST">
            @csrf
            <button class="btn btn-primary">New contest</button>
        </form>
        <a href="{{ route('characters.edit', $character) }}" class="btn btn-primary">Edit</a>
        <form action="{{ route('characters.destroy', $character) }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="btn btn-error">Delete</button>
        </form>
    </div>
    <hr class="mt-5">
    <h1 class="text-xl mt-5">Contests</h1>
    @if ($character->contests->count() === 0)
        <p>No contests found.</p>
    @else
        <div class="contests-list mt-5">
            @foreach ($character->contests as $contest)
                <a href="{{ route('contests.show', $contest) }}">
                    <div
                        class="border border-slate-300 flex-auto rounded bg-slate-800 p-3 hover:bg-slate-200 cursor-pointer hover:text-slate-800">
                        <p>📍 Place: {{ $contest->place->name }}</p>
                        <p class="mt-5">
                            ⚔️ Opponent: @if ($character->enemy)
                                {{ $characters->where('id', $contest->enemy->first()->pivot->character_id)->first()->name }}
                            @else
                                {{ $characters->where('id', $contest->enemy->first()->pivot->enemy_id)->first()->name }}
                            @endif
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
