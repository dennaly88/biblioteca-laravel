@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Libros</h1>
    <a href="{{ route('books.create') }}" class="btn btn-primary">+ Nuevo libro</a>
</div>

<form method="GET" action="{{ route('books.index') }}" class="row g-2 mb-3">
    <div class="col-auto">
        <select name="category" class="form-select">
            <option value="">Todas las categorías</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected($selected == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button class="btn btn-secondary">Filtrar</button>
        <a href="{{ route('books.index') }}" class="btn btn-link">Limpiar</a>
    </div>
</form>

<div class="table-responsive">
<table class="table table-striped bg-white align-middle">
    <thead><tr><th>Título</th><th>Autor</th><th>Año</th><th>Categoría</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
    @forelse ($books as $book)
        <tr>
            <td>{{ $book->title }}<div class="small text-muted">{{ \Illuminate\Support\Str::limit($book->description, 80) }}</div></td>
            <td>{{ $book->author }}</td>
            <td>{{ $book->published_year }}</td>
            <td><span class="badge text-bg-info">{{ $book->category->name }}</span></td>
            <td class="text-end">
                <a href="{{ route('books.edit', $book->id) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                <button type="button" class="btn btn-sm btn-outline-danger js-delete" data-url="{{ route('books.destroy', $book->id) }}" data-name="{{ $book->title }}">Eliminar</button>
            </td>
        </tr>
    @empty
        <tr><td colspan="5" class="text-center text-muted">No hay libros para mostrar.</td></tr>
    @endforelse
    </tbody>
</table>
</div>

{{ $books->links('pagination::bootstrap-5') }}
@endsection
