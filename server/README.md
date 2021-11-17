# Scootin Aboot task server part implementation

### Implementation notes:

* Instead of working just with scooter, I decided to make DB structure and endpoint a bit more universal.
With current setup it will be pretty easy to add another vehicle type like bike, for example.

* Folder's structure:
  * **Application** - stands for service application layer, contains controllers, requests/responses definition, security related functionality
  * **Domain** - stands for service business layer, contains services and entities
  * **Infrastructure** - stands for service infrastructure layer, contains repositories, responsible for fetching data from DB

* To simplify development flow no logging functionality was provided, but for observability purposes in real production ready 
services it is very important to have an informative logs.

* Auth part based on Symfony **AbstractAuthenticator** class. **ApiKeyAuthenticator** class fetch API-KEY from request header
and tries to find user with such api key in DB.

* In some classes you can find additional comments about possible improvements.

* For working with spatial data I use spatial functionality provided by MySQL.

### How to run:

* Normally the first step for any php app is installing external dependencies, but to simplify and speed up running process
I decided to also add `vendor` folder to the git-repository. If you have any troubles with it, you can try to remove `vendor`
folder and run ```composer install --ignore-platform-reqs```  (you should have composer installed on your machine)
* ```docker-compose up -d``` - to run service (it can take some time during first launch)
* ```docker exec -it app bin/console  doctrine:migration:migrate --no-interaction``` - to apply DB migrations
* ```docker exec -it app php -d memory_limit=2048M bin/console doctrine:fixtures:load --no-interaction``` - to load the
  fixtures (every execution of this command removes all records from DB and creates new)

### How to stop:

* execute ```docker-compose down``` to stop the app

### Running tests

I created just one unit test (VehicleLocationServiceTest) for demonstration purposes. In perfect world all necessary
functionality should be covered by unit and ideally functional tests.

To run the tests you can use ```docker exec -it app ./bin/phpunit``` command

### Request's examples:

* Update vehicle location
```
curl -X PUT 'http://127.0.0.1:5555/api/vehicle/{vehicle_identifier}/location' -H 'API-KEY: toobanitoocs' \
--data-raw '{
    "latitude": 52.458316,
    "longitude": 13.528655,
    "received_at": "2021-11-14 10:00:00"
}'
```

* Update vehicle status
```
curl -X PUT 'http://127.0.0.1:5555/api/vehicle/{vehicle_identifier}/status/{status}' -H 'API-KEY: toobanitoocs' \
--data-raw '{
    "updated_at": "2021-11-14 10:00:00"
}'
```

* Get vehicles list
```
curl -X GET 'http://127.0.0.1:5555/api/vehicles/{type}/status/{status}' -H 'API-KEY: toobanitoocs' \
--data-raw '{
    "start_latitude": 52.444906,
    "start_longitude": 13.278702,
    "end_latitude": 52.557687,
    "end_longitude": 13.529307
}'
```

* Values available for {type}: scooter
* Values available for {status}: available, occupied
* {vehicle_identifier} can be taken from[GET] vehicles request.

### Check DB data:

If you need to select some data from DB (for example to fetch any vehicle identifier), you can use following command:
* ```docker exec -i app-mysql mysql -uroot -proot <<< "use app_db; select * from vehicle;"```

Available tables:
* api_user
* vehicle
* vehicle_location