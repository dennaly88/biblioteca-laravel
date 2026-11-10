@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ $book ? route('books.update', $book->id) : route('books.store') }}" method="POST" class="card card-body" data-ajax data-method="{{ $book ? 'PUT' : 'POST' }}" data-success="Libro guardado correctamente." data-redirect="{{ route('books.index') }}">
    @csrf
    @if ($book) @method('PUT') @endif

    <div class="mb-3">
        <label class="form-label">Título *</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $book->title ?? '') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Autor *</label>
        <input type="text" name="author" class="form-control" value="{{ old('author', $book->author ?? '') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="description" rows="3" class="form-control">{{ old('description', $book->description ?? '') }}</textarea>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Año de publicación *</label>
            <input type="number" name="published_year" class="form-control" value="{{ old('published_year', $book->published_year ?? '') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Categoría *</label>
            <select name="category_id" class="form-select">
                <option value="">Selecciona...</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $book->category_id ?? '') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div>
        <button class="btn btn-primary">Guardar</button>
        <a href="{{ route('books.index') }}" class="btn btn-link">Cancelar</a>
    </div>
</form>
