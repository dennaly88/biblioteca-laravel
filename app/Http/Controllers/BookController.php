<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['category' => ['nullable', 'integer', 'exists:categories,id']]);

        $books = Book::with('category')
            ->ofCategory($request->query('category'))
            ->orderBy('title')
            ->paginate(10)
            ->withQueryString();

        if ($this->wantsJson($request)) {
            return response()->json($books);
        }

        return view('books.index', [
            'books'      => $books,
            'categories' => Category::orderBy('name')->get(),
            'selected'   => $request->query('category'),
        ]);
    }

    public function create()
    {
        return view('books.create', ['categories' => Category::orderBy('name')->get()]);
    }

    public function store(StoreBookRequest $request)
    {
        $book = Book::create($request->validated())->load('category');

        if ($this->wantsJson($request)) {
            return response()->json($book, 201);
        }

        return redirect()->route('books.index')->with('success', 'Libro creado correctamente.');
    }

    public function show(Request $request, string $id)
    {
        $book = Book::with('category')->find($id);

        return $book ? response()->json($book) : $this->notFound($request);
    }

    public function edit(Request $request, string $id)
    {
        $book = Book::find($id);

        if (! $book) {
            return $this->notFound($request);
        }

        return view('books.edit', ['book' => $book, 'categories' => Category::orderBy('name')->get()]);
    }

    public function update(UpdateBookRequest $request, string $id)
    {
        $book = Book::find($id);

        if (! $book) {
            return $this->notFound($request);
        }

        $book->update($request->validated());

        if ($this->wantsJson($request)) {
            return response()->json($book->load('category'));
        }

        return redirect()->route('books.index')->with('success', 'Libro actualizado correctamente.');
    }

    public function destroy(Request $request, string $id)
    {
        $book = Book::find($id);

        if (! $book) {
            return $this->notFound($request);
        }

        $book->delete();

        if ($this->wantsJson($request)) {
            return response()->json(['message' => 'Libro eliminado correctamente.']);
        }

        return redirect()->route('books.index')->with('success', 'Libro eliminado correctamente.');
    }

    private function wantsJson(Request $request): bool
    {
        return $request->is('api/*') || ($request->expectsJson() && ! $request->has('_token'));
    }

    private function notFound(Request $request)
    {
        $message = 'El libro solicitado no existe.';

        return $this->wantsJson($request)
            ? response()->json(['message' => $message], 404)
            : redirect()->route('books.index')->with('error', $message);
    }
}
