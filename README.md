# installtion

cd into project
copy .env.example to .env
§

browser needs experimental feature to be anbled when server is on docker edge://flags/#unsafely-treat-insecure-origin-as-secure

main html is in resources/views/welcome.blade.php

main controller is app/Http/Controllers/AudioController.php


```shell
#init docker containern
vednor/bin/sail up -d

vednor/bin/sail php artisan key:generate
vednor/bin/sail php artisan migrate
vednor/bin/sail php artisan storage:link

vednor/bin/sail npm i
vednor/bin/sail npm dev
```
