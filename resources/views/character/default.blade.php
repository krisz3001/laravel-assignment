@extends('../layout')

@section('title', 'Default')

@section('content')
    <p class="mb-5">
        Welcome to a singleplayer game where you can create characters and let them fight against each other!
    </p>

    <ul class="mb-5">
        <li>You can create, edit and delete a character with a name, and set their stats: defence, strength, accuracy, and
            magic.</li>
        <li>Admins can create enemies and manage places.</li>
        <li>You can start a contest between two characters, fight with different attacks, and see the results.</li>
    </ul>

    <h1>Characters created so far: {{ $users_count }}</h1>
    <h1>Played contests: {{ $contests_count }}</h1>
@endsection
