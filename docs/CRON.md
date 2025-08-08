# Cron & Queue (Shared Hosting)

- Scheduler elke minuut:
```
* * * * * php /pad/naar/artisan schedule:run >> /dev/null 2>&1
```
- Queue via database driver, run once elke minuut:
```
* * * * * php /pad/naar/artisan queue:work --once --queue=default >> /dev/null 2>&1
```
- Logs: gebruik host specifieke cron log of mail
- Zorg dat `QUEUE_CONNECTION=database` en `php artisan queue:table && php artisan migrate` is gedraaid