<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('books')->orderBy('name')->get();

        return $this->wantsJson($request)
            ? response()->json($categories)
            : view('categories.index', compact('categories'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return $this->wantsJson($request)
            ? response()->json($category, 201)
            : redirect()->route('categories.index')->with('success', 'Categoría creada correctamente.');
    }

    public function update(UpdateCategoryRequest $request, string $id)
    {
        $category = Category::find($id);

        if (! $category) {
            return $this->notFound($request);
        }

        $category->update($request->validated());

        return $this->wantsJson($request)
            ? response()->json($category)
            : redirect()->route('categories.index')->with('success', 'Categoría actualizada.');
    }

    public function destroy(Request $request, string $id)
    {
        $category = Category::find($id);

        if (! $category) {
            return $this->notFound($request);
        }

        if ($category->books()->exists()) {
            $message = 'No se puede eliminar la categoría porque tiene libros asociados.';

            return $this->wantsJson($request)
                ? response()->json(['message' => $message], 409)
                : redirect()->route('categories.index')->with('error', $message);
        }

        $category->delete();

        return $this->wantsJson($request)
            ? response()->json(['message' => 'Categoría eliminada correctamente.'])
            : redirect()->route('categories.index')->with('success', 'Categoría eliminada correctamente.');
    }

    private function wantsJson(Request $request): bool
    {
        return $request->is('api/*') || ($request->expectsJson() && ! $request->has('_token'));
    }

    private function notFound(Request $request)
    {
        $message = 'La categoría solicitada no existe.';

        return $this->wantsJson($request)
            ? response()->json(['message' => $message], 404)
            : redirect()->route('categories.index')->with('error', $message);
    }
}
