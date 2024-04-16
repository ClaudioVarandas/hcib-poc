# HCIB - code challenge (04/2024)

## Author

Cláudio Varandas

Software Engineer

## Project

[![HCIB-POC](https://github.com/ClaudioVarandas/hcib-poc/actions/workflows/hcib-poc-workflow.yml/badge.svg)](https://github.com/ClaudioVarandas/hcib-poc/actions/workflows/hcib-poc-workflow.yml)

This project was created to submit the code challenge for HCIB.

The goal of this project is to provide a rest api application that the user can use to track the value
of stocks in the stock market.

Tech stack:

- Symfony 6.4
- PHPUnit 10.5.11
- Postgresql 16.2
- RabbitMQ 3.13

Repository:

The souce code repository is available here :
`https://github.com/ClaudioVarandas/hcib-poc`

CI Workflow : https://github.com/ClaudioVarandas/hcib-poc/actions/workflows/hcib-poc-workflow.yml

## How to setup

#### Requirements

- Docker desktop (WSL/MacOs)
- Docker and Docker composer Linux
- Git

#### Instructions

- clone the repository 
- create `.env.local` with the secrets
- `docker compose up -d --build`
- `./php.sh` to enter the app container
  - inside the container
    - `php bin/console doctrine:migrations:migrate`
    - `php bin/console lexik:jwt:generate-keypair`

  
#### Urls 

RabbitMQ management :
`http://localhost:15679/#/queues`

API Base URI : `http://localhost:8181`

Postman collection : `doc/HCIB-POC.postman_collection.json`

#### Useful commands

Consuming Messages (Running the Worker):

`php bin/console messenger:consume async -vv`

Debug messenger:

`php bin/console debug messenger`

Enter the php container 

`./php.sh` , and run any command you like.


### Services matrix

Service            |            | Container Name | Ports (ext:int)
-------------------|------------|----------------|-----------------
 api               | php, app   | hcib-db        | 
 nginx             | Nginx      | hcib-nginx     | 8181:8181
 db                | Postgresql | hcib-db        | 5439:5432           
 rmq               | RabbitMQ   | hcib-rmq       | 15679:15672 

## Features

### Mandatory

- :white_check_mark: The application must use an SQL database to store users and record logs of past
requests.
- :white_check_mark: The application must be able to authenticate registered users.
- :white_check_mark: The application must have these three endpoints:
- :white_check_mark: An endpoint to create a new User, storing the email and information to log in later.
- :white_check_mark: An endpoint to request a stock quote, like this:

`GET /stock?q=IBM`

```json
{
"symbol": "IBM",
"open": 123.66,
"high": 123.66,
"low": 122.49,
"close": 123
}
```

- :white_check_mark: The same endpoint must additionally send an email with the same information to the user who
requested the quote.
- :white_check_mark: An endpoint to retrieve the history of queries made to the API service by that user. The endpoint
should return the list of entries saved in the database, showing the latest entries first:
`GET /history`

```json
[
  {
    "date": "2021-04-01T19:20:30Z",
    "symbol": "IBM",
    "open": "123.66",
    "high": 123.66,
    "low": 122.49,
    "close": "123"
  },
  {
    "date": "2021-03-25T11:10:55Z",
    "symbol": "IBM",
    "open": "121.10",
    "high": 123.66,
    "low": 122,
    "close": "122"
  }
]
```

### Bonus

The following features are optional to implement, but if you do, you'll be ranked higher in our
evaluation process.

- :white_check_mark: Add unit tests, and integration tests for the endpoints.
- :white_check_mark: Use RabbitMQ to send the email asynchronously.
- :white_check_mark: Use JWT instead of basic authentication for endpoints.
- :white_check_mark: Containerize the app.
- :white_check_mark: Postman Collection.
- :eight_pointed_black_star: Creation of a simple frontend using Vue to interact with the API.


## Postman
There is also available , in the doc folder , a postman collection to be imported on postman:

- `<project_root>/doc/api/LWS.postman_collection.json`


## Tests

### Setup 

```sh
php bin/console --env=test doctrine:database:create
php bin/console --env=test doctrine:schema:create
php bin/console --env=test doctrine:fixtures:load
```

