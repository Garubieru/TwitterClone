<?php

namespace backend;

class Connection
{
  public static function connectDb()
  {
    try {
      $conn = new \PDO(
        "mysql:host=localhost;dbname=twitter_db;charset=utf8",
        "root",
        ""
      );

      return $conn;
    } catch (\PDOException $e) {
      echo `<p> Error: {$e->getCode()}, Message: {$e->getMessage()} </p>`;
    }
  }
}
