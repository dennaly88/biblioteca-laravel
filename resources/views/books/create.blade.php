@extends('layouts.app')

@section('content')
<h1 class="h3 mb-3">Nuevo libro</h1>
@include('books._form', ['book' => null])
@endsection
