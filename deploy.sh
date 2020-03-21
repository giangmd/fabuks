docker exec fabuks_app php artisan migrate
docker exec fabuks_app php artisan migrate:refresh
docker exec fabuks_app php artisan super:update "admin@gmail.com" "12345678" "12345678" --create
docker exec fabuks_app php artisan rates:generates 1.25
docker exec fabuks_app php artisan passport:client --password