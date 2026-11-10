# Biblioteca – CRUD de libros con Laravel

Aplicación para gestionar una biblioteca de libros y categorías (CRUD + filtro por categoría).
Arquitectura MVC, migraciones, seeders, Form Requests y Eloquent. Base de datos: PostgreSQL.

## Requisitos
- PHP >= 8.2 con las extensiones `pdo_pgsql` y `pgsql`
- Composer
- PostgreSQL

## Instalación y ejecución

```bash
# 1. Clonar e instalar dependencias
git clone <URL_DEL_REPOSITORIO> biblioteca
cd biblioteca
composer install

# 2. Entorno
cp .env.example .env
php artisan key:generate

# 3. Crear la base de datos en PostgreSQL
sudo -u postgres psql -c "CREATE DATABASE biblioteca;"
```

Revisa en `.env` que los datos coincidan con tu PostgreSQL:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=biblioteca
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

```bash
# 4. Migraciones y datos de ejemplo
php artisan migrate --seed

# 5. Levantar el servidor
php artisan serve
```
Abre http://127.0.0.1:8000 (redirige a `/books`).

## Endpoints

| Método | Ruta | Descripción |
|---|---|---|
| GET | `/books` | Lista los libros (paginado) |
| GET | `/books?category={id}` | Filtra por categoría |
| POST | `/books` | Crea un libro |
| PUT | `/books/{id}` | Edita un libro |
| DELETE | `/books/{id}` | Elimina un libro |
| GET/POST/PUT/DELETE | `/categories` | Gestión de categorías |

- **Interfaz web** (Blade + Bootstrap): `/books` y `/categories`. Tras crear, editar o eliminar,
  se redirige al listado con un mensaje.
- **API JSON** (útil en Postman/curl): mismas operaciones bajo `/api/books` y `/api/categories`,
  sin CSRF. También se obtiene JSON en `/books` enviando `Accept: application/json`.

Ejemplo:
```bash
curl -X POST http://127.0.0.1:8000/api/books \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"title":"Dune","author":"Frank Herbert","published_year":1965,"category_id":3}'
```

### Códigos HTTP
`200` OK · `201` creado · `404` libro/categoría inexistente · `409` categoría con libros asociados · `422` error de validación.

## Validaciones (Form Requests)
- `title`: obligatorio, mínimo 3 caracteres
- `author`: obligatorio
- `published_year`: obligatorio, entero entre 1000 y el año actual
- `category_id`: obligatorio, debe existir en `categories`
- `categories.name`: obligatorio y único

## Lógica de negocio
- Se verifica que el libro exista antes de eliminarlo (404 si no existe).
- No se puede eliminar una categoría con libros asociados (409); además la FK usa `restrictOnDelete`.

## Estructura relevante
- `app/Models`: `Book`, `Category` (relaciones `belongsTo` / `hasMany`, scope `ofCategory`)
- `app/Http/Controllers`: `BookController`, `CategoryController`
- `app/Http/Requests`: `StoreBookRequest`, `UpdateBookRequest`, `StoreCategoryRequest`, `UpdateCategoryRequest`
- `database/migrations` y `database/seeders`: esquema y datos de ejemplo
- `routes/web.php` y `routes/api.php`
