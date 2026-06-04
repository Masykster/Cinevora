@php $movie = $movie ?? null; @endphp

<div class="grid grid-2 gap-3">
    <div class="form-group">
        <label class="form-label" for="title">Judul Film *</label>
        <input type="text" id="title" name="title" class="form-input" value="{{ old('title', $movie?->title) }}" required>
        @error('title')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="director">Sutradara *</label>
        <input type="text" id="director" name="director" class="form-input" value="{{ old('director', $movie?->director) }}" required>
        @error('director')<p class="form-error">{{ $message }}</p>@enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label" for="synopsis">Sinopsis *</label>
    <textarea id="synopsis" name="synopsis" class="form-textarea" required>{{ old('synopsis', $movie?->synopsis) }}</textarea>
    @error('synopsis')<p class="form-error">{{ $message }}</p>@enderror
</div>

<div class="grid grid-2 gap-3">
    <div class="form-group">
        <label class="form-label" for="genre">Genre *</label>
        <input type="text" id="genre" name="genre" class="form-input" value="{{ old('genre', $movie?->genre) }}" required placeholder="Action, Drama">
        @error('genre')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="cast">Pemeran</label>
        <input type="text" id="cast" name="cast" class="form-input" value="{{ old('cast', $movie?->cast) }}" placeholder="Nama1, Nama2">
    </div>
</div>

<div class="grid grid-4 gap-3">
    <div class="form-group">
        <label class="form-label" for="duration">Durasi (menit) *</label>
        <input type="number" id="duration" name="duration" class="form-input" value="{{ old('duration', $movie?->duration) }}" required min="1">
        @error('duration')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="rating">Rating (0-10) *</label>
        <input type="number" id="rating" name="rating" class="form-input" value="{{ old('rating', $movie?->rating) }}" required step="0.1" min="0" max="10">
        @error('rating')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="release_date">Tanggal Rilis *</label>
        <input type="date" id="release_date" name="release_date" class="form-input" value="{{ old('release_date', $movie?->release_date?->format('Y-m-d')) }}" required>
        @error('release_date')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="age_rating">Usia *</label>
        <select id="age_rating" name="age_rating" class="form-select" required>
            @foreach(['SU', '13+', '17+', '21+'] as $ar)
                <option value="{{ $ar }}" {{ old('age_rating', $movie?->age_rating) === $ar ? 'selected' : '' }}>{{ $ar }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="grid grid-2 gap-3">
    <div class="form-group">
        <label class="form-label" for="status">Status *</label>
        <select id="status" name="status" class="form-select" required>
            @foreach(['now_playing' => 'Now Playing', 'coming_soon' => 'Coming Soon', 'ended' => 'Ended'] as $val => $label)
                <option value="{{ $val }}" {{ old('status', $movie?->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label" for="trailer_url">URL Trailer</label>
        <input type="url" id="trailer_url" name="trailer_url" class="form-input" value="{{ old('trailer_url', $movie?->trailer_url) }}" placeholder="https://youtube.com/...">
    </div>
</div>

<div class="grid grid-2 gap-3">
    <div class="form-group">
        <label class="form-label" for="poster">URL Poster</label>
        <input type="text" id="poster" name="poster" class="form-input" value="{{ old('poster', $movie?->poster) }}" placeholder="https://image.tmdb.org/t/p/...">
        @error('poster')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="banner">URL Banner</label>
        <input type="text" id="banner" name="banner" class="form-input" value="{{ old('banner', $movie?->banner) }}" placeholder="https://image.tmdb.org/t/p/...">
        @error('banner')<p class="form-error">{{ $message }}</p>@enderror
    </div>
</div>
