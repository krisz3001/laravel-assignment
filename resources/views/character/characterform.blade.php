@extends('../layout')

@isset($character)
    @section('title', 'Edit character')
@else
@section('title', 'Create character')
@endisset

<style>
.input-row {
    display: flex;
    flex-direction: column;
    align-items: start;
}
</style>

@section('content')
<h1 class="text-2xl mb-2">
    @isset($character)
        Edit
    @else
        Create
    @endisset character
</h1>
<hr>
<form
    action="@isset($character) {{ route('characters.update', ['character' => $character->id]) }} @else {{ route('characters.store') }} @endisset"
    method="POST" class="mt-5">
    @csrf
    @isset($character)
        @method('PUT')
    @endisset
    <div class="input-row">
        <label for="name">Name</label>
        <input type="text" name="name" id="name"
            class="input input-bordered @error('name') input-error @enderror"
            value="{{ old('name', $character->name ?? '') }}">
    </div>
    @error('name')
        <p class="text-red-500">{{ $message }}</p>
    @enderror
    <div class="input-row">
        <label for="defence">Defence</label>
        <input type="text" name="defence" id="defence"
            class="input input-bordered @error('defence') input-error @enderror"
            value="{{ old('defence', $character->defence ?? '') }}">
    </div>
    @error('defence')
        <p class="text-red-500">{{ $message }}</p>
    @enderror
    <div class="input-row">
        <label for="strength">Strength</label>
        <input type="text" name="strength" id="strength"
            class="input input-bordered @error('strength') input-error @enderror"
            value="{{ old('strength', $character->strength ?? '') }}">
    </div>
    @error('strength')
        <p class="text-red-500">{{ $message }}</p>
    @enderror
    <div class="input-row">
        <label for="accuracy">Accuracy</label>
        <input type="text" name="accuracy" id="accuracy"
            class="input input-bordered @error('accuracy') input-error @enderror"
            value="{{ old('accuracy', $character->accuracy ?? '') }}">
    </div>
    @error('accuracy')
        <p class="text-red-500">{{ $message }}</p>
    @enderror
    <div class="input-row">
        <label for="magic">Magic</label>
        <input type="text" name="magic" id="magic"
            class="input input-bordered @error('magic') input-error @enderror"
            value="{{ old('magic', $character->magic ?? '') }}">
    </div>
    @error('magic')
        <p class="text-red-500">{{ $message }}</p>
    @enderror
    @if (Auth::user()->admin)
        <div class="ms-3 mt-3">
            <div class="md:w-1/3"></div>
            <label class="md:w-2/3 block text-gray-200 font-bold">
                <input type="hidden" name="enemy" value="0">
                <input class="mr-2 leading-tight" value="1" type="checkbox" name="enemy"
                    @checked(old('enemy', $character->enemy ?? false))>
                <span class="text-sm">Enemy?</span>
            </label>
        </div>
    @endif
    @error('sum')
        <p class="text-red-500">{{ $message }}</p>
    @enderror
    <div class="mt-5 buttons">
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="{{ route('characters.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection
