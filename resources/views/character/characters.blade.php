@extends('../layout')

@section('title', 'Characters')

@section('content')
    <a href="{{ route('characters.create') }}" class="btn btn-primary mb-3">New character</a>
    <table class="table table-pin-rows">
        <thead>
            <tr>
                <th>Name</th>
                <th>Enemy</th>
                <th>Defence</th>
                <th>Strength</th>
                <th>Accuracy</th>
                <th>Magic</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($characters as $character)
                <tr>
                    <td><a href="{{ route('characters.show', $character) }}" class="link">{{ $character->name }}</a></td>
                    <td>{{ $character->enemy ? 'Yes' : 'No' }}</td>
                    <td>{{ $character->defence }}</td>
                    <td>{{ $character->strength }}</td>
                    <td>{{ $character->accuracy }}</td>
                    <td>{{ $character->magic }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
