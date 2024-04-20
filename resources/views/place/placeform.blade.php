@extends('../layout')

@isset($place)
    @section('title', 'Edit place')
@else
@section('title', 'Create place')
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
    @isset($place)
        Edit
    @else
        Create
    @endisset place
</h1>
<hr>
<form
    action="@isset($place) {{ route('places.update', $place) }} @else {{ route('places.store') }} @endisset"
    method="POST" enctype="multipart/form-data" class="flex flex-col gap-5 mt-5">
    @csrf
    @isset($place)
        @method('PUT')
    @endisset
    <div class="input-row">
        <label for="name">Name</label>
        <input type="text" name="name" id="name"
            class="input input-bordered @error('name') input-error @enderror"
            value="{{ old('name', $place->name ?? '') }}">
    </div>
    @error('name')
        <p class="text-red-500">{{ $message }}</p>
    @enderror

    <div class="input-row">
        <label for="image">Image</label>
        <input type="file" name="image" id="image"
            class="file-input file-input-bordered @error('image') file-input-error @enderror">
    </div>
    @error('image')
        <p class="text-red-500">{{ $message }}</p>
    @enderror

    <div class="mt-5 buttons">
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="{{ route('places.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection
