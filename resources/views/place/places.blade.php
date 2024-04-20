@extends('../layout')

@section('title', 'Places')

<style>
    .place-row {
        height: 100px;
    }

    .place-row img {
        width: 150px;
    }
</style>

@section('content')
    <a href="{{ route('places.create') }}" class="btn btn-primary mb-3">New place</a>
    <table class="table border-2 rounded">
        <thead>
            <tr class="bg-slate-800 text-slate-200">
                <th>Name</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($places as $place)
                <tr class="place-row text-slate-200 bg-slate-800">
                    <td>{{ $place->name }}</td>
                    <td><img src="{{ $place->image }}"></td>
                    <td class="flex gap-5 h-full items-center">
                        <a href="{{ route('places.edit', $place) }}" class="cursor-pointer">✏️</a>
                        <form action="{{ route('places.destroy', $place) }}" method="POST" class="m-0">
                            @csrf
                            @method('DELETE')
                            <input type="submit" value="❌" class="cursor-pointer">
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
