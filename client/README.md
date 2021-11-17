## Scootin fake clients app 

This service is responsible for emulating client's activity.

### Implementation notes

Nothing very special here, just simple API client for communication with server part. It also contains set of structures for
requests and responses and main flow, which runs several goroutines to emulate client's activity.

For communicating with server part service tries to get the base url from `BASE_URL` env variable. For docker container
this value is set as ```http://host.docker.internal:5555``` in Dockerfile directly. It was tested on macOS, that with
given value it is possible to access localhost from docker container. For linux it is, most probably, should be something different.
If you prefer to run service locally and did not set up `BASE_URL` env variable, the default value, which is
```http://localhost:5555``` will be used.

After getting list of available vehicles in every goroutine one of those vehicles is selected randomly to emulate further ride.
There is small probability that in two different goroutines will be selected vehicle with same identifier, which in real life
is impossible. This means that the same vehicle will be used by two different clients. To save some time, I did not
implement any protection against this scenario.


### How to run
To see the proper output you should first run server part.

First option is to run app using docker:
- docker should be installed on your machine
- to build an image execute ```docker build . -t go-scootin-client```
- to run an image execute ```docker run go-scootin-client```

Second option is to run app locally:
- *Golang* should be installed on you machine
- from the application root folder execute ```go run cmd/main.go```

After running the app you should be able to see some output information

### Running tests

I have created just one unit test (api_client_test.go) for demonstration purposes. In perfect world all necessary
functionality should be covered by unit and ideally functional tests.

To run the tests you can use following command:

- from project root ```go test -v ./app/infrastructure/```
