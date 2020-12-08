<?php

namespace MF\Model;

use backend\Connection;

abstract class Container
{
  public static function getModel($model)
  {
    $class = "backend\\Models\\" . ucfirst($model);
    $conn = Connection::connectDb();

    return new $class($conn);
  }
}
