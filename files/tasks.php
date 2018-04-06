<?php

require('database.php');


function error_handler($message)
{
  $log = (new DateTime())->format('Y-m-d H:i:s') . '  ' . $message . PHP_EOL;
  file_put_contents('errorlog', $log, FILE_APPEND | LOCK_EX);

  http_response_code(500);
  header('Content-type: application/json');
  print('{ "code" : 500, "message" : "' . $message . '" }');
  die();
}


function database_connection()
{
    try
    {
        $database = null;
        $database = new PDO('mysql:host=localhost; dbname=' . Database::NAME . '; charset=utf8', Database::USER, Database::PASSWORD);
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $database->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        return $database;
    }
    catch(Exception $e)
    {
        error_handler($e->getMessage());
    }
}


function get_data()
{
    $database = database_connection();

    $statment = $database->prepare('select task_id, name, description, start, finish, status, active as state from tasks', array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $statment->execute();

    $result = $statment->fetchAll(PDO::FETCH_ASSOC);
    $result = json_encode($result);

    header('Content-type: application/json');
    print_r($result);
}


get_data();

?>