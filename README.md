# Legal One BE Coding Challenge

## Problem Description

There is an aggregated log file that contains log lines from multiple services. This file is potentially very large (think hundreds of millions of lines). A very small example of this file can be found in the provided `logs.txt`.

We would now like to analyze this file, e.g. count log lines for a specific service.

## Tasks

1. Build a console command that parses the file and inserts the data to a database (without using a parsing library). Pick any DB that you are familiar with and decide on a schema. The import should be triggered manually, and it should be able to start where it left off when interrupted.

2. Build a RESTful service that implements the provided `api.yaml` OpenAPI specs.

The service should query the database and provide a single endpoint `/count` which returns a count of rows that match the filter criteria.

a) This endpoint accepts a list of filters via GET request and allows zero or more filter parameters. The filters are:

- serviceNames
- statusCode
- startDate
- endDate

b) Endpoint result:

```
{
    "counter": 1
}
```

## Solution Description

## Task 1:

- Console command can import data from `logs.txt` file to `database`. Data will be inserted in the form of `batches` in database as logs file can contains millions of data. Batch size is an optional parameter as it can be provided by the user alongwith the command.

- For resume the import after interruption, `ImportJob entity` is created in which record of each file is maintained alongwith row number till where it get inserted successfully by using the status (pending/completed). So, it's easy to start importing where it gets stopped.

- `ServiceImportJob` has been created which is generic and independent. It can easily be used by simply calling its `executeImport` method anywhere if we want to import some other modules in future.

Import logs.txt command to database:

```
    php bin/console app:import-logs <logFile-path> <batchSize>
```

or

```
    make import-logs <logFile-path> <batchSize>
```

## Task 2:

- Get request endpoint `/count` which returns a count of rows that match the filter criteria.

- Based on the filter criteria, it will check on runtime and add the conditions in the query after executing, it will return the count filtered records.

## Tests

- Both `Unit` and `Integration` test cases have been added under test/Unit and tests/Integration respectively.

- Performed unit testing by `Mocking` the objects and functions of other classes.

- Performed `Functional testing` on **Repository/Database** and `Integration testing` on **ServiceImportJob** and **/Count endpoint** by setting up a testing database and write some `fixtures` for it.

- For testing database, simply add the `DATABASE_URL` in .env.test.

## Solution Directory Path

- Code directory path `/devbox`
