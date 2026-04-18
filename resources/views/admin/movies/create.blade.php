@extends('layouts.admin')
@section('title', 'Tambah Film')

@section('content')
<div class="card" style="max-width: 800px; padding: 2rem;">
    <form method="POST" action="{{ route('admin.movies.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.movies._form')
        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">💾 Simpan Film</button>
            <a href="{{ route('admin.movies.index') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection
