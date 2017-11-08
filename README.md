# Lite SQL Insert

Very lightweight PHP service class for SQL INSERT queries abstraction.

Best suited for use as a [Composer](https://getcomposer.org) library.

## Requirements

* PHP >= 7.1
* PDO extension

## Installation

To add this library to your Composer project:
```
composer require mikeshiyan/lite-sql-insert
```

## Usage

```
$connection = new \LiteSqlInsert\Connection($pdo);
$insert = $connection->insert('my_table', ['name', 'value']);

foreach ($my_data as $name => $value) {
  $insert->values(['name' => $name, 'value' => $value]);
}

$insert->commit();
```
