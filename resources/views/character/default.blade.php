@extends('../layout')

@section('title', 'Default')

@section('content')
    <h1>Eddig létrehozott karakterek száma: {{ $users_count }}</h1>
    <h1>Lejátszott meccsek száma: {{ $contests_count }}</h1>
@endsection
