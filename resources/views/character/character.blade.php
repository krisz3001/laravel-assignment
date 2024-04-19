@extends('../layout')

@section('title', 'Character details')

@section('content')
    <h1 class="text-2xl">{{ $character->name }}</h1>
    <div class="ms-5 mb-5">
        <p>Enemy: {{ $character->enemy }}</p>
        <p>Defence: {{ $character->defence }}</p>
        <p>Strength: {{ $character->strength }}</p>
        <p>Accuracy: {{ $character->accuracy }}</p>
        <p>Magic: {{ $character->magic }}</p>
    </div>
    <div class="buttons">
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
        <ul>
            @foreach ($character->contests as $contest)
                <li>
                    Place: <a href="{{ route('contests.show', $contest) }}" class="link">{{ $contest->place->name }}</a>
                    <br>
                    Enemy: {{ $contest->enemy->first()->pivot }}
                </li>
            @endforeach
        </ul>
    @endif
@endsection
