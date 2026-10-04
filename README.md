# Space Station 14 Paper Editor

A Laravel page for writing Space Station 14 paper text in a visual editor and copying the markup it produces.

Author: [Danila Nazarenko](https://forged.by)

## About the Project

The home page is a paper editor. It provides:

-   A visual editor (Editor.js) with bold, italic, text color, and headings
-   Three preview styles: Paper, Office Paper, and Napkin
-   A Code tab that shows the markup generated from the visual editor
-   Paste of existing markup into the editor, which is rendered in the preview

Formatting is converted in the browser by `public/js/editor.js`. The page loads Bootstrap, Editor.js, and the text-color plugin from CDNs.

## Requirements

-   PHP 8.1 or higher
-   Composer
-   PHP's built-in server, or another web server pointed at `public/`

Node.js is not required. The editor does not use the Vite build.

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/lermal/papers_ss14.git
cd papers_ss14
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Configure the environment

This repository does not include `.env.example`. Create a `.env` file in the project root with at least:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
```

`php artisan key:generate` only writes the key when an `APP_KEY=` line is already present.

### 4. Generate the application key

```bash
php artisan key:generate
```

A database is not required. The editor does not read or write one. Do not run migrations unless you are continuing the unused `documents` table work described below.

## Running the Application

```bash
php artisan serve
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000).

## Usage

1. Type in the **Editor** tab, or paste paper markup into it.
2. Select text and use the inline toolbar: bold, italic, color, and heading (`H1`–`H5`).
3. Switch the preview with **Paper**, **Office Paper**, or **Napkin**. These change appearance only. They are not written into the markup.
4. Open the **Code** tab and copy the generated markup.

The Code tab is filled from the visual editor when the text changes and when you switch to that tab. Text you type only in the Code tab is not written back into the visual editor.

Markup handled by the browser parser:

| Markup | Result |
| --- | --- |
| `[bold]...[/bold]` | Bold |
| `[italic]...[/italic]` | Italic |
| `[bolditalic]...[/bolditalic]` | Bold and italic |
| `[color=#RRGGBB]...[/color]` | Color. A CSS color name from the map in `public/js/editor.js` is also accepted and shown in that color |
| `[head=1]...[/head]` through `[head=5]...[/head]` | Heading. `[header=N]` is accepted when parsing |
| `[bullet]` or `[bullet/]` | Bullet character |

`[color]` is exported as a hex value, headings as `[head=N]`, and bullets as `[bullet/]`. `\[` and `\]` are treated as escaped brackets.

## Project Structure

```
papers_ss14/
├── app/Http/Controllers/EditorController.php   # Home page, unused preview action
├── public/js/editor.js                         # Editor and markup conversion
├── resources/views/editor/index.blade.php      # Editor page
├── routes/web.php                              # / and POST /preview
└── database/migrations/2024_03_21_create_documents_table.php
```

`POST /preview` returns JSON `{ "html": "..." }` from a smaller PHP converter in `EditorController`. The editor page does not call it. That converter only handles `[bold]`, `[color=#RRGGBB]`, `[head=1]`–`[head=5]`, and `[bullet]`.

`EditorController::store()` validates `content` and an optional `title`, then returns `{ "status": "success" }`. It is not registered as a route, there is no `Document` model, and the commented save is not active. The `documents` migration (`title`, `content`, timestamps) is unused by the running editor.

`resources/views/welcome.blade.php` is the default Laravel welcome view. `routes/web.php` registers `/` twice; the editor route replaces it, so the welcome view is not served.

## License

`composer.json` declares the [MIT license](https://opensource.org/licenses/MIT).
