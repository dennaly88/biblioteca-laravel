@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Categorías</h1>

@if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<form action="{{ route('categories.store') }}" method="POST" class="row g-2 mb-4" data-ajax data-method="POST" data-success="Categoría creada correctamente." data-redirect="{{ route('categories.index') }}">
    @csrf
    <div class="col-auto"><input type="text" name="name" class="form-control" placeholder="Nueva categoría" value="{{ old('name') }}"></div>
    <div class="col-auto"><button class="btn btn-primary">Agregar</button></div>
</form>

<table class="table table-striped bg-white align-middle">
    <thead><tr><th>Nombre</th><th>Libros</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
    @foreach ($categories as $category)
        <tr>
            <td>{{ $category->name }}</td>
            <td>{{ $category->books_count }}</td>
            <td class="text-end">
                <button type="button" class="btn btn-sm btn-outline-danger js-delete" data-url="{{ route('categories.destroy', $category->id) }}" data-name="{{ $category->name }}">Eliminar</button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
