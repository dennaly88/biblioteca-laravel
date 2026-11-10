<div align="center">
  
<h1 align="center"> DANNY JOSÉ JIMÉNEZ GUTIÉRREZ  </h1>
🖥️_ INGENIERO EN INFORMÁTICA _💻

[![📞 0424-281-44-55](https://img.shields.io/badge/Phone-0424--281--44--55-0078D4?style=for-the-badge&logo=phone&logoColor=white)](tel:04242814455)
[![✉️ dennaly88@gmail.com](https://img.shields.io/badge/Email-dennály88@gmail.com-EA4335?style=for-the-badge&logo=gmail&logoColor=white)](mailto:dennaly88@gmail.com)

</div>



<div align="center">
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/html5/html5-original.svg" height="60" alt="html5 logo"  />
  <img width="12" />
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/css3/css3-original.svg" height="60" alt="css logo"  />
  <img width="12" />
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-original.svg" height="60" alt="javascript logo"  />
  <img width="12" />
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg" height="60" alt="php logo"  />
  <img width="12" />
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/python/python-original.svg" height="60" alt="python logo"  />
  <img width="12" />
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg" height="60" alt="mysql logo"  />
  <img width="12" />
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/postgresql/postgresql-original.svg" height="60" alt="postgresql logo"  />
  <img width="12" />
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/microsoftsqlserver/microsoftsqlserver-plain.svg" height="60"     alt="sqlserver logo"  />  <img width="12" />
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/linux/linux-original.svg" height="60" alt="linux logo"  />
  <img width="12" />
  <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/trello/trello-plain.svg" height="60" alt="trello logo"  />
</div>

###

<div align="center">
  <img src="https://img.shields.io/static/v1?message=LinkedIn&logo=linkedin&label=&color=0077B5&logoColor=white&labelColor=&style=for-the-badge" height="25" alt="linkedin logo"  />
  <img src="https://img.shields.io/static/v1?message=Twitter&logo=twitter&label=&color=1DA1F2&logoColor=white&labelColor=&style=for-the-badge" height="25" alt="twitter logo"  />
  <img src="https://img.shields.io/static/v1?message=Discord&logo=discord&label=&color=7289DA&logoColor=white&labelColor=&style=for-the-badge" height="25" alt="discord logo"  />
  <img src="https://img.shields.io/static/v1?message=Twitch&logo=twitch&label=&color=9146FF&logoColor=white&labelColor=&style=for-the-badge" height="25" alt="twitch logo"  />
  <img src="https://img.shields.io/static/v1?message=dev.to&logo=dev.to&label=&color=0A0A0A&logoColor=white&labelColor=&style=for-the-badge" height="25" alt="devto logo"  />
  <img src="https://img.shields.io/static/v1?message=Youtube&logo=youtube&label=&color=FF0000&logoColor=white&labelColor=&style=for-the-badge" height="25" alt="youtube logo"  />
</div>


_____________________________________________________________________________________________________________________________________________________________
📚 Biblioteca – CRUD de libros con Laravel                                                                                                  
📌 Aplicación para gestionar una biblioteca de libros y categorías (CRUD + filtro por categoría).                                                                                                          🗓️ Arquitectura MVC, migraciones, seeders, Form Requests y Eloquent. Base de datos: PostgreSQL.

______________________________________________________________________________________________________________________________________________________________







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
