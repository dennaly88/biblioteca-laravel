<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            ['Cien años de soledad', 'Gabriel García Márquez', 1967, 'Novela', 'La saga de la familia Buendía en Macondo.'],
            ['El amor en los tiempos del cólera', 'Gabriel García Márquez', 1985, 'Novela', null],
            ['Dune', 'Frank Herbert', 1965, 'Ciencia Ficción', 'Épica espacial en el planeta Arrakis.'],
            ['Fundación', 'Isaac Asimov', 1951, 'Ciencia Ficción', null],
            ['Sapiens', 'Yuval Noah Harari', 2011, 'Historia', 'Breve historia de la humanidad.'],
            ['Cosmos', 'Carl Sagan', 1980, 'Ciencia', null],
            ['El Hobbit', 'J. R. R. Tolkien', 1937, 'Fantasía', 'La aventura de Bilbo Bolsón.'],
        ];

        foreach ($books as [$title, $author, $year, $category, $description]) {
            Book::firstOrCreate(
                ['title' => $title, 'author' => $author],
                [
                    'published_year' => $year,
                    'description' => $description,
                    'category_id' => Category::where('name', $category)->value('id'),
                ]
            );
        }
    }
}
