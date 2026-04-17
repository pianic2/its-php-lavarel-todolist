# its-php-lavarel-todolist

Todo List in Laravel con UI basata su `core.min.css`.

## Stato progetto

- Fase 1: progetto Laravel, database e CSS core impostati.
- Fase 2: modelli `Task` e `TaskList` con migrazioni.
- Fase 3: CRUD base, rotte e toggle completamento.
- Fase 4: UI Blade completata con layout, sidebar, liste e task annidate.

## Fase 4 in breve

- La home `/` mostra tutte le liste.
- Anche `/lists` mostra tutte le liste.
- Le task sono servite sotto `/lists/{list}/tasks`.
- Una task deve appartenere sempre a una lista.
- Eliminando una lista vengono eliminate anche le task associate.
- La sidebar include i pulsanti `Tutte le liste` e `Nuova lista`.
- Il form task non permette piu' la selezione di task orfane.
- Le view usano componenti e utility di `core.min.css` con override in `project.css`.

## Rotte principali

- `GET /`
- `GET /lists`
- `GET /lists/{list}`
- `GET /lists/{list}/tasks`
- `GET /lists/{list}/tasks/create`
- `POST /lists/{list}/tasks`
- `GET /lists/{list}/tasks/{task}`
- `PATCH /lists/{list}/tasks/{task}`
- `DELETE /lists/{list}/tasks/{task}`
- `PATCH /lists/{list}/tasks/{task}/toggle`

## Verifica

La suite Laravel passa con:

```bash
docker compose exec app php artisan test
```
