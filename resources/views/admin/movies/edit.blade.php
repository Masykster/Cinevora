@extends('layouts.admin')
@section('title', 'Edit Film')

@section('content')
<div class="card" style="max-width: 800px; padding: 2rem;">
    <form method="POST" action="{{ route('admin.movies.update', $movie) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.movies._form', ['movie' => $movie])
        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">💾 Update Film</button>
            <a href="{{ route('admin.movies.index') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection
