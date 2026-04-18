# Todo List Laravel - Versione 1

[![Laravel CI](https://github.com/pianic2/its-php-lavarel-todolist/actions/workflows/ci.yml/badge.svg)](https://github.com/pianic2/its-php-lavarel-todolist/actions/workflows/ci.yml)

Applicazione Todo List sviluppata in Laravel con interfaccia Blade e stile basato su `core.min.css`.

Il progetto nasce come esercizio completo di sviluppo backend/frontend in PHP: modellazione dati, CRUD, validazione, routing annidato, UI server-rendered, filtri, stati utente e test automatici.

## Obiettivo del progetto

L'obiettivo e' realizzare una piccola applicazione gestionale solida, leggibile e valutabile da un punto di vista professionale.

La versione 1 dimostra:

- capacita' di progettare un dominio semplice ma coerente;
- uso di Laravel per rotte, controller, model, request validation e migration;
- gestione corretta delle relazioni tra entita';
- UI Blade organizzata in layout e partial riutilizzabili;
- attenzione a validazione, stati vuoti, feedback utente e filtri;
- copertura dei flussi principali tramite test Feature e Unit.

## Funzionalita'

### Liste

- Creazione, modifica, visualizzazione ed eliminazione liste.
- Ogni lista ha nome e descrizione.
- La home mostra l'elenco delle liste.
- Ogni lista mostra le proprie task associate.
- Eliminando una lista vengono eliminate anche le task collegate.

### Task

- Creazione, modifica, visualizzazione ed eliminazione task.
- Ogni task appartiene sempre a una lista.
- Non sono previste task orfane.
- Ogni task contiene:
  - titolo;
  - descrizione opzionale;
  - stato di completamento;
  - riferimento alla lista.
- Possibilita' di segnare una task come completata o riaprirla tramite toggle.

### Filtri

Le task di una lista possono essere filtrate per stato:

- `all`: tutte le task;
- `open`: task ancora aperte;
- `done`: task completate.

I filtri sono gestiti tramite query string e mantengono la paginazione coerente.

### Validazione

La validazione e' gestita lato Laravel:

- il titolo della lista e' obbligatorio;
- il titolo della task e' obbligatorio;
- il titolo ha lunghezza massima di 255 caratteri;
- la descrizione e' opzionale;
- lo stato `is_completed` viene trattato come booleano.

Per le task sono presenti request dedicate:

- `StoreTaskRequest`;
- `UpdateTaskRequest`.

### UI e UX

L'interfaccia usa Blade, `core.min.css` e override locali in `project.css`.

Sono presenti:

- layout base condiviso;
- sidebar di navigazione;
- viste dedicate per liste e task;
- form riutilizzabili;
- filtri visivi;
- messaggi flash;
- stato vuoto quando non ci sono task;
- loading state sui bottoni;
- separazione tra CSS di base e personalizzazioni di progetto.

## Stack tecnico

- PHP `^8.3`
- Laravel `^13.0`
- MySQL 8 tramite Docker
- phpMyAdmin
- Blade
- Vite
- PHPUnit
- Docker Compose
- GitHub Actions CI/CD

## CI/CD

Il repository include una pipeline GitHub Actions in `.github/workflows/ci.yml`.

La workflow viene eseguita su ogni `push` e `pull_request` verso `main` e verifica automaticamente:

- installazione dipendenze PHP con Composer;
- preparazione ambiente Laravel da `.env.example`;
- esecuzione test automatici con `php artisan test`;
- installazione dipendenze frontend;
- build asset Vite con `npm run build`.

Questa integrazione rende il progetto piu' adatto alla condivisione professionale, perche' ogni modifica pubblicata passa da controlli automatici riproducibili.

## Struttura del repository

```text
.
├── README.md
├── docker-compose.yml
├── php/
│   └── Dockerfile
├── codex/
│   └── TODO.json
└── todo-app/
    ├── app/
    │   ├── Http/
    │   │   ├── Controllers/
    │   │   └── Requests/
    │   └── Models/
    ├── database/
    │   ├── factories/
    │   ├── migrations/
    │   └── seeders/
    ├── public/
    │   └── css/
    ├── resources/
    │   └── views/
    ├── routes/
    └── tests/
```

## Modello dati

### `TaskList`

Tabella: `lists`

Campi principali:

- `id`
- `name`
- `description`
- `created_at`
- `updated_at`

Relazione:

- una lista ha molte task.

### `Task`

Tabella: `tasks`

Campi principali:

- `id`
- `title`
- `description`
- `is_completed`
- `list_id`
- `created_at`
- `updated_at`

Relazione:

- una task appartiene a una lista.

Scope disponibili:

- `completed()`
- `pending()`

Metodo di dominio:

- `toggleCompleted()`

## Rotte principali

### Home e liste

```text
GET     /
GET     /lists
POST    /lists
GET     /lists/create
GET     /lists/{list}
GET     /lists/{list}/edit
PUT     /lists/{list}
PATCH   /lists/{list}
DELETE  /lists/{list}
```

### Task annidate nella lista

```text
GET     /lists/{list}/tasks
POST    /lists/{list}/tasks
GET     /lists/{list}/tasks/create
GET     /lists/{list}/tasks/{task}
GET     /lists/{list}/tasks/{task}/edit
PUT     /lists/{list}/tasks/{task}
PATCH   /lists/{list}/tasks/{task}
DELETE  /lists/{list}/tasks/{task}
PATCH   /lists/{list}/tasks/{task}/toggle
```

Le rotte task sono definite con `scopeBindings()`, quindi Laravel impedisce di accedere a una task da una lista diversa da quella a cui appartiene.

## Installazione con Docker

Prerequisiti:

- Docker
- Docker Compose

Avvio dei container:

```bash
docker compose up -d --build
```

L'applicazione viene esposta su:

```text
http://127.0.0.1:8000
```

phpMyAdmin viene esposto su:

```text
http://127.0.0.1:8080
```

Credenziali database configurate in `docker-compose.yml`:

```text
Database: laravel
User: laravel
Password: laravel
Root password: root
Host interno Docker: db
```

## Setup applicazione

Entrare nel container:

```bash
docker compose exec app sh
```

Installare dipendenze PHP, se necessario:

```bash
composer install
```

Preparare `.env`, se non presente:

```bash
cp .env.example .env
php artisan key:generate
```

Eseguire le migration:

```bash
php artisan migrate
```

Popolare dati demo:

```bash
php artisan db:seed
```

## Sviluppo frontend

Dentro `todo-app` sono presenti gli script Vite:

```bash
npm run dev
npm run build
```

Il progetto usa anche file CSS statici in:

```text
todo-app/public/css/core.min.css
todo-app/public/css/project.css
```

`core.min.css` rappresenta il layer di stile base, mentre `project.css` contiene gli override specifici dell'applicazione.

## Test

Esecuzione test:

```bash
docker compose exec app php artisan test
```

Oppure, dentro il container:

```bash
php artisan test
```

La suite copre in particolare:

- pagina creazione task sotto una lista;
- creazione task associata alla lista corretta;
- validazione titolo obbligatorio in creazione;
- validazione titolo obbligatorio in modifica;
- protezione da accesso a task tramite lista sbagliata;
- eliminazione task quando viene eliminata la lista;
- filtro task aperte;
- filtro task completate;
- fallback a `all` per filtri non validi.

## Stato del progetto

Versione corrente: `1.0.0`

Fasi completate:

- Project Foundation Setup
- Domain Model
- Core CRUD Logic
- UI Implementation
- Filtering & UX
- Validation Layer
- UX Polish & States
- Core Validation & QA

La versione 1 e' considerata completa: non sono previsti bugfix bloccanti prima della chiusura.

## Criteri di valutazione tecnica

Questo progetto puo' essere valutato positivamente per:

- uso ordinato del pattern MVC di Laravel;
- separazione tra logica controller, model, request validation e view;
- rotte RESTful;
- relazione uno-a-molti tra liste e task;
- route model binding con vincolo di appartenenza;
- query scope per stati delle task;
- paginazione;
- validazione lato server;
- test automatici sui flussi principali;
- pipeline CI/CD con GitHub Actions;
- UI coerente e non dipendente da framework JavaScript;
- ambiente Docker riproducibile.

## Scelte progettuali

### Task sempre dentro una lista

La task non viene trattata come entita' indipendente globale, ma come elemento appartenente a una lista. Questa scelta semplifica il dominio e impedisce record senza contesto.

### Route annidate

Le task sono gestite tramite rotte del tipo:

```text
/lists/{list}/tasks/{task}
```

Questo rende esplicita la relazione tra risorsa padre e risorsa figlia.

### Validazione dedicata per le task

Le request `StoreTaskRequest` e `UpdateTaskRequest` separano la validazione dal controller, mantenendo il controller piu' leggibile.

### UI server-rendered

Il progetto usa Blade invece di una SPA JavaScript. La scelta e' adatta a un gestionale semplice, riduce complessita' frontend e valorizza le funzionalita' native di Laravel.

## Possibili evoluzioni future

Questa versione e' chiusa, ma il progetto puo' essere esteso con:

- autenticazione utenti;
- liste private per utente;
- priorita' task;
- scadenze;
- ricerca testuale;
- ordinamento drag and drop;
- API JSON;
- policy/autorizzazioni;
- soft delete;
- deploy in ambiente cloud;
- pipeline CI.

## Note per aziende e recruiter

Questo repository non vuole mostrare solo una Todo List, ma un processo di sviluppo completo e incrementale.

Sono stati curati:

- struttura del dominio;
- coerenza delle rotte;
- relazione tra dati;
- validazione;
- UI;
- test;
- documentazione;
- ambiente locale riproducibile.

Il progetto e' quindi utile per valutare competenze pratiche su Laravel, PHP moderno, database relazionali, Blade, Docker e testing applicativo.
