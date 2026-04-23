# Todo List Laravel

[![Laravel CI](https://github.com/pianic2/its-php-lavarel-todolist/actions/workflows/ci.yml/badge.svg)](https://github.com/pianic2/its-php-lavarel-todolist/actions/workflows/ci.yml)

Applicazione Todo List sviluppata in Laravel con UI Blade server-rendered, autenticazione Fortify, condivisione liste tra utenti e notifiche email per gli inviti.

Il progetto nasce come esercizio completo di sviluppo backend/frontend in PHP e oggi include CRUD, validazione, routing annidato, autenticazione, collaborazione multiutente, demo pubblica read-only e test automatici.

## Obiettivo del progetto

L'obiettivo è realizzare una piccola applicazione gestionale solida, leggibile e presentabile in un contesto professionale.

Il progetto dimostra:

- progettazione di un dominio semplice ma coerente;
- uso di Laravel per rotte, controller, model, request validation, notifications e migration;
- gestione di relazioni uno-a-molti e molti-a-molti;
- interfaccia Blade organizzata in layout, partial e pagine dedicate;
- attenzione a validazione, feedback utente, stati vuoti, sicurezza e rate limiting;
- copertura dei flussi principali tramite test automatici e CI.

## Funzionalità

### Liste

- creazione, modifica, visualizzazione ed eliminazione liste;
- ogni lista ha nome e descrizione;
- ogni lista contiene le proprie task;
- eliminando una lista vengono eliminate anche le task collegate;
- ogni lista appartiene a uno o più utenti tramite relazione many-to-many;
- una lista può essere condivisa con altri utenti tramite email.

### Task

- creazione, modifica, visualizzazione ed eliminazione task;
- ogni task appartiene sempre a una lista;
- non sono previste task orfane;
- ogni task contiene:
  - titolo;
  - descrizione opzionale;
  - stato di completamento;
  - riferimento alla lista;
- possibilità di segnare una task come completata o riaprirla tramite toggle.

### Autenticazione

- autenticazione gestita con Laravel Fortify;
- pagina login standalone;
- rate limiter `login` configurato per limitare i tentativi di accesso;
- accesso alle liste limitato agli utenti collegati alla lista.

### Condivisione e collaborazione

- un utente può condividere una lista con un altro utente esistente tramite email;
- la pagina della lista mostra i collaboratori correnti;
- è possibile revocare l'accesso a un collaboratore;
- quando una lista viene condivisa, l'utente invitato riceve una notifica email.

### Filtri

Le task di una lista possono essere filtrate per stato:

- `all`: tutte le task;
- `open`: task ancora aperte;
- `done`: task completate.

I filtri sono gestiti tramite query string e mantengono la paginazione coerente.

### Validazione

La validazione è gestita lato Laravel:

- il titolo della lista è obbligatorio;
- il titolo della task è obbligatorio;
- il titolo ha lunghezza massima di 255 caratteri;
- la descrizione è opzionale;
- lo stato `is_completed` viene trattato come booleano;
- l'email usata per condividere una lista deve appartenere a un utente esistente.

Per le task sono presenti request dedicate:

- `StoreTaskRequest`;
- `UpdateTaskRequest`.

### UI e UX

L'interfaccia usa Blade, `core.min.css` e override locali in `project.css`.

Sono presenti:

- layout base condiviso;
- sidebar di navigazione contestuale all'utente autenticato;
- vista login standalone;
- viste dedicate per liste e task;
- form riutilizzabili;
- filtri visivi;
- messaggi flash e messaggi di errore nel login;
- stato vuoto quando non ci sono task;
- sezione collaboratori nella pagina lista;
- separazione tra CSS di base e personalizzazioni di progetto.

## Stack tecnico

- PHP `^8.4`
- Laravel `^13.0`
- Laravel Fortify
- MySQL 8 tramite Docker
- phpMyAdmin
- Blade
- Vite
- PHPUnit
- Docker Compose
- GitHub Actions CI/CD

## Demo e credenziali

Live demo: https://laravel-todo-list-awlf.onrender.com

Il repository include una configurazione Render in `render.yaml` e un Dockerfile dedicato in `Dockerfile.render`.

La demo live esegue automaticamente:

- installazione dipendenze PHP in container;
- build degli asset Vite;
- migration Laravel;
- seed dei dati demo;
- blocco delle scritture pubbliche in modalità sola lettura;
- avvio dell'applicazione su porta cloud.

Credenziali demo locali:

- email: `demo@example.com`
- password: `password`

Nota: la demo pubblica è configurata in sola lettura tramite `DEMO_READ_ONLY=true`, così i visitatori possono navigare il progetto senza modificare o cancellare dati.

## CI/CD

Il repository include una pipeline GitHub Actions in `.github/workflows/ci.yml`.

La workflow viene eseguita su ogni `push` e `pull_request` verso `main` e verifica automaticamente:

- installazione dipendenze PHP con Composer;
- preparazione ambiente Laravel da `.env.example`;
- esecuzione test automatici con `php artisan test`;
- installazione dipendenze frontend;
- build asset Vite con `npm run build`.

Questa integrazione rende il progetto più adatto alla condivisione professionale, perché ogni modifica pubblicata passa da controlli automatici riproducibili.

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
    │   │   ├── Middleware/
    │   │   └── Requests/
    │   ├── Models/
    │   ├── Notifications/
    │   └── Providers/
    ├── database/
    │   ├── factories/
    │   ├── migrations/
    │   └── seeders/
    ├── resources/
    │   └── views/
    ├── routes/
    └── tests/
```

## Modello dati

### `User`

Tabella: `users`

Relazioni:

- un utente appartiene a molte liste tramite pivot `list_user`.

### `TaskList`

Tabella: `lists`

Campi principali:

- `id`
- `name`
- `description`
- `created_at`
- `updated_at`

Relazioni:

- una lista ha molte task;
- una lista appartiene a molti utenti.

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

Relazioni:

- una task appartiene a una lista.

Scope disponibili:

- `completed()`
- `pending()`

Metodo di dominio:

- `toggleCompleted()`

### Pivot `list_user`

La tabella pivot collega utenti e liste condivise.

Campi principali:

- `id`
- `user_id`
- `list_id`
- `role`
- `created_at`
- `updated_at`

## Rotte principali

### Autenticazione

```text
GET     /login
POST    /login
POST    /logout
GET     /register
POST    /register
```

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
POST    /lists/{list}/share
DELETE  /lists/{list}/share/{user}
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

Preparare le variabili locali Docker:

```bash
cp .env.docker.example .env.docker
```

Aggiornare `.env.docker` con valori locali personali. Il file `.env.docker` è ignorato da Git e non deve essere pubblicato.

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

## Setup applicazione

Preparare l'app Laravel:

```bash
cd todo-app
cp .env.example .env
php artisan key:generate
```

Eseguire migration e seed:

```bash
php artisan migrate
php artisan db:seed
```

Oppure da root via Docker:

```bash
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed --class=DatabaseSeeder --force
```

Il seeder crea o riutilizza l'utente demo e associa le liste seedate a quell'utente.

## Email e notifiche

Quando una lista viene condivisa, Laravel invia una notifica email all'utente invitato tramite `App\Notifications\ListShared`.

In ambiente locale, il mailer predefinito è `log`, quindi il contenuto delle email viene scritto in:

```text
todo-app/storage/logs/laravel.log
```

Per usare email reali, configura `MAIL_MAILER` e le variabili SMTP nel file `.env`.

## Sviluppo frontend

Dentro `todo-app` sono presenti gli script Vite:

```bash
npm install
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

La suite copre i flussi core dell'applicazione e può essere estesa per includere autenticazione, condivisione liste e notifiche.

## Scelte progettuali

### Task sempre dentro una lista

La task non viene trattata come entità indipendente globale, ma come elemento appartenente a una lista. Questa scelta semplifica il dominio e impedisce record senza contesto.

### Route annidate

Le task sono gestite tramite rotte del tipo:

```text
/lists/{list}/tasks/{task}
```

Questo rende esplicita la relazione tra risorsa padre e risorsa figlia.

### Liste condivise via pivot

La relazione tra utenti e liste usa una pivot dedicata. Questo permette ownership implicita, condivisione, estensioni future sui ruoli e controllo accessi più semplice.

### UI server-rendered

Il progetto usa Blade invece di una SPA JavaScript. La scelta è adatta a un gestionale semplice, riduce complessità frontend e valorizza le funzionalità native di Laravel.

### Demo pubblica read-only

La demo pubblica è bloccata in scrittura per evitare modifiche non autorizzate e mantenere i dati dimostrativi stabili.

## Evoluzioni possibili

Il progetto può essere esteso con:

- policy Laravel formali per ownership e ruoli;
- invio notifiche in coda con queue worker;
- password reset e verifica email completi;
- priorità task;
- scadenze;
- ricerca testuale;
- API JSON;
- soft delete;
- ruoli avanzati nella pivot utente-lista.

## Note per aziende e recruiter

Questo repository non vuole mostrare solo una Todo List, ma un processo di sviluppo completo e incrementale.

Sono stati curati:

- struttura del dominio;
- coerenza delle rotte;
- relazioni tra dati;
- validazione;
- UX server-rendered;
- autenticazione e protezione accessi;
- collaborazione tra utenti;
- notifiche applicative;
- test;
- documentazione;
- ambiente locale riproducibile.

Il progetto è utile per valutare competenze pratiche su Laravel, PHP moderno, database relazionali, Blade, Docker, testing applicativo e gestione di funzionalità reali come autenticazione e collaborazione multiutente.
