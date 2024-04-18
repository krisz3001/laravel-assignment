<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1>Karakterek száma: {{ $characters->count() }}</h1>
    <ul>
        @foreach ($characters as $character)
            <li>{{ $character->name }}</li>
        @endforeach
    </ul>
</body>

</html>
