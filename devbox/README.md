# L1 Challenge Solution Devbox

## Description

This application can perform the following functionalities:

1. Import multiple service logs data from text file to database by simple running the console command alongwith the file path.

   **Console Command**

   ```
     make import-logs logfile=<file-path> batchSize=<size> [batchSize default 15]
   ```

   **Or**

   Go to docker devbox-service container and execute the following command:

   ```
    php bin/console app:import-logs <file-path> <batch-size>
   ```

   - batchSize is optional its default value is 15.

2. Filter can be applied on the imported logs data by using
   /count GET endpoint.

   a) This endpoint accepts a list of filters via GET request and allows zero or more filter parameters. The filters are:

   - serviceNames
   - statusCode
   - startDate
   - endDate

Example 1:

```
  /count?serviceNames[]=USER-SERVICE&serviceNames[]=INVOICE-SERVICE&startDate=2021-08-18%2010:26:53& endDate=2021-08-25%2010:32:56&statusCode=201

```

Example 2:

```
  /count?serviceNames[]=USER-SERVICE&statusCode=201

```

b) Endpoint result:

```
{
  "counter": 1
}
```

## Tech Intro

- Dockerfile & Docker-compose setup with PHP8.1, MySQL and Symfony 5.4.
- After the image is started the app will run on port 9002 on localhost. You can try the using the following
  endpoints:
  - http://localhost:9002/healthz
  - http://localhost:9002/count
- The default database is called `database` and the username and password are `root` and `root` respectively but for testing, `database_test` database will be used.
- Makefile with some basic commands

## pre-requisite

- create `.env` and `.env.local` file if not already exists and paste .env.example content in both files.

## Installation

```
  make run && make install && make migration && make migrate
```

## Run commands inside the container

```
  make enter
```

## Run commands inside the mysql container

```
  make enter-mysql
```

## Run tests

```
  make test
```
