# Lite SQL Insert

[![Build Status](https://travis-ci.org/mikeshiyan/lite-sql-insert.svg?branch=master)](https://travis-ci.org/mikeshiyan/lite-sql-insert)

Very lightweight PHP service class for SQL INSERT queries abstraction.

Best suited for use as a [Composer](https://getcomposer.org) library.

## Requirements

* PHP >= 7.1
* PDO extension

## Installation

To add this library to your Composer project:
```
composer require shiyan/lite-sql-insert
```

## Usage

```
$connection = new \Shiyan\LiteSqlInsert\Connection($pdo);
$insert = $connection->insert('my_table', ['name', 'value']);

foreach ($my_data as $name => $value) {
  $insert->values(['name' => $name, 'value' => $value]);
}

$insert->commit();
```
