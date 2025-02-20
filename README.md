<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Laravel TMDB Application</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
    }
    code {
      background-color: #f4f4f4;
      padding: 2px 4px;
      border-radius: 3px;
      font-family: Consolas, monospace;
    }
    pre {
      background-color: #f4f4f4;
      padding: 10px;
      border-radius: 3px;
      overflow-x: auto;
    }
    a {
      color: #0366d6;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
    h1, h2, h3 {
      border-bottom: 1px solid #eaecef;
      padding-bottom: 5px;
    }
  </style>
</head>
<body>
  <h1>Laravel TMDB Application</h1>
  <p>
    Aplikacja oparta na Laravelu pobiera dane o filmach, serialach i gatunkach z TMDB API, zapisuje je w bazie danych oraz udostępnia wielojęzyczne REST API.
    Projekt wykorzystuje pakiet <a href="https://github.com/spatie/laravel-translatable" target="_blank">spatie/laravel-translatable</a> do obsługi tłumaczeń, dzięki czemu pola takie jak <code>title</code> i <code>overview</code> są przechowywane jako JSON z tłumaczeniami dla różnych języków.
  </p>

  <h2>Wymagania</h2>
  <ul>
    <li>PHP 8.x</li>
    <li>Laravel 11 (najnowsza stabilna wersja)</li>
    <li>Composer</li>
    <li>Baza danych (SQLite, MySQL, itp.)</li>
    <li>Klucze TMDB:
      <ul>
        <li><strong>TMDB_API_KEY</strong></li>
        <li><strong>TMDB_READ_TOKEN</strong></li>
      </ul>
    </li>
  </ul>

  <h2>Instalacja</h2>
  <ol>
    <li>
      <strong>Klonowanie repozytorium:</strong>
      <pre><code>git clone https://github.com/twoj_uzytkownik/tmdb_app.git
cd tmdb_app</code></pre>
    </li>
    <li>
      <strong>Instalacja zależności:</strong>
      <pre><code>composer install</code></pre>
    </li>
    <li>
      <strong>Konfiguracja środowiska:</strong>
      <ul>
        <li>
          Skopiuj przykładowy plik konfiguracyjny:
          <pre><code>cp .env.example .env</code></pre>
        </li>
        <li>
          Zaktualizuj plik <code>.env</code> – ustaw dane bazy oraz klucze TMDB:
          <pre><code>TMDB_API_KEY=5464d202a5737639861f5cb12f0dde9b
TMDB_READ_TOKEN=TWÓJ_TMBD_READ_TOKEN</code></pre>
        </li>
      </ul>
    </li>
    <li>
      <strong>Generowanie klucza aplikacji:</strong>
      <pre><code>php artisan key:generate</code></pre>
    </li>
    <li>
      <strong>Migracje:</strong>
      <pre><code>php artisan migrate</code></pre>
    </li>
    <li>
      <strong>Pobieranie danych z TMDB:</strong>
      <pre><code>php artisan tmdb:fetch</code></pre>
    </li>
  </ol>

  <h2>API Endpoints</h2>
  <p>
    Aplikacja udostępnia następujące endpointy REST API. Aby uzyskać tłumaczenia, wystarczy dodać parametr <code>lang</code> do zapytania.
  </p>

  <h3>Filmy</h3>
  <ul>
    <li>
      <strong>GET /api/movies</strong><br>
      <em>Query Params:</em> <code>lang</code> (string, domyślnie: <code>en-US</code>) – określa wersję językową pól <code>title</code> i <code>overview</code>.<br>
      <strong>Przykładowa odpowiedź:</strong>
      <pre><code>{
  "page": 1,
  "results": [
    {
        "tmdb_id": 1241982,
        "title": "Moana 2",
        "overview": "After receiving an unexpected call from her wayfinding ancestors, Moana journeys alongside Maui and a new crew to the far seas of Oceania and into dangerous, long-lost waters for an adventure unlike anything she's ever faced.",
        "release_date": "2024-11-21",
        "poster_path": "/aLVkiINlIeCkcZIzb7XHzPYgO6L.jpg",
        "backdrop_path": "/zo8CIjJ2nfNOevqNajwMRO6Hwka.jpg",
        "vote_average": 7.213,
        "vote_count": 1584,
        "popularity": 2167.851,
        "adult": 0,
        "video": 0
    }
  ],
  "total_pages": 1,
  "total_results": 50
}</code></pre>
    </li>
    <li>
      <strong>GET /api/movies/{tmdbId}</strong><br>
      Pobiera szczegóły filmu o podanym <code>tmdbId</code>.
    </li>
  </ul>

  <h3>Seriale</h3>
  <ul>
    <li>
      <strong>GET /api/series</strong><br>
      <em>Query Params:</em> <code>lang</code> (string, domyślnie: <code>en-US</code>)<br>
      <strong>Przykładowa odpowiedź:</strong>
      <pre><code>{
  "page": 1,
  "results": [
    {
      "tmdb_id": 206559,
      "title": "Example Series",
      "overview": "Series overview...",
      "first_air_date": "2005-10-13",
      "poster_path": "/path/to/poster.jpg",
      "backdrop_path": "/path/to/backdrop.jpg",
      "vote_average": 5.6,
      "vote_count": 90,
      "popularity": 3087.796,
      "origin_country": ["ZA"]
    }
  ],
  "total_pages": 1,
  "total_results": 10
}</code></pre>
    </li>
    <li>
      <strong>GET /api/series/{tmdbId}</strong><br>
      Pobiera szczegóły serialu.
    </li>
  </ul>

  <h3>Gatunki</h3>
  <ul>
    <li>
      <strong>GET /api/genres</strong><br>
      <em>Query Params:</em> <code>lang</code> (string, domyślnie: <code>en</code>)<br>
      <strong>Przykładowa odpowiedź:</strong>
      <pre><code>{
  "genres": [
    {
      "tmdb_id": 28,
      "name": "Action"
    },
    {
      "tmdb_id": 12,
      "name": "Adventure"
    }
  ]
}</code></pre>
    </li>
    <li>
      <strong>GET /api/genres/{tmdbId}</strong><br>
      Pobiera szczegóły gatunku.
    </li>
  </ul>

  <h2>Multi-language</h2>
  <p>
    Projekt używa pakietu <a href="https://github.com/spatie/laravel-translatable" target="_blank">spatie/laravel-translatable</a>, który pozwala na przechowywanie tłumaczeń w jednym polu bazy (w formacie JSON).
  </p>
</code></pre>
  <p>Dzięki metodom <code>setTranslation()</code> oraz <code>getTranslation()</code> możesz ustawiać i pobierać tłumaczenia dla danego języka.</p>

  <h2>Problemy SSL i cURL</h2>
  <p>
    Jeśli podczas pobierania danych z TMDB pojawi się błąd cURL (np. <strong>cURL error 60</strong>), pobierz plik <code>cacert.pem</code> z <a href="https://curl.se/ca/cacert.pem" target="_blank">curl.se/ca/cacert.pem</a> i skonfiguruj go w pliku <code>php.ini</code>:
  </p>
  <pre><code>curl.cainfo="C:\xampp\php\extras\ssl\cacert.pem"
openssl.cafile="C:\xampp\php\extras\ssl\cacert.pem"</code></pre>
  <p>Następnie zrestartuj terminal, aby zmiany zaczęły obowiązywać.</p>

  <h2>Uruchamianie komendy</h2>
  <p>Aby pobrać dane z TMDB, uruchom komendę:</p>
  <pre><code>php artisan tmdb:fetch</code></pre>
  <p>
    Komenda pobiera 50 filmów, 10 seriali oraz wszystkie gatunki i zapisuje je w bazie danych, ustawiając tłumaczenia dla wybranych języków.
  </p>


  <h2>License</h2>
  <p>Ten projekt jest dostępny na licencji MIT.</p>
</body>
</html>
