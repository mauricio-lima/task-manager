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


function get_tasks_data()
{
    $database = database_connection();

    $statment = $database->prepare('select task_id, name, description, start, finish, status, active as state from tasks', array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $statment->execute();

    $result = $statment->fetchAll(PDO::FETCH_ASSOC);
    $result = json_encode($result);

    header('Content-type: application/json');
    print_r($result);
}


function insert_task_data()
{
    $database = database_connection();

    $statment = $database->prepare('call task_insert(:name, :description, :start, :finish, :status, :active)', array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $statment->execute([':name' => 'aaaa', ':description' => 'bbbb', ':start' => '2018-01-01', ':finish' => '2018-01-01', ':status' => 1, ':active' => 1]);

    header('Content-type: application/json');
    print_r('{ "success" : true }');
}


function normalize_parameters($source)
{
  $parameters = array();
  foreach ($source as $key => $value) 
  { 
    $parameters[strtolower($key)] = $value;
  }
  return $parameters;
}


function request_select()
{
  try
  {
      switch ($_SERVER['REQUEST_METHOD'])
      {
          case 'POST' :
              $method = 'post';
              if ( isset($_SERVER['CONTENT_TYPE']) )
              {
                switch ($_SERVER['CONTENT_TYPE'])
                {
                    case 'application/x-www-form-urlencoded':
                        $parameters = normalize_parameters($_POST);
                        break;

                    case 'application/json':
                        $a = file_get_contents('php://input');
                        $parameters = json_decode(file_get_contents('php://input'), true);
                        break;

                    default:
                        break;
                }
              }
              break;

          case 'GET'  :
              $method = 'get';
              $parameters = normalize_parameters($_GET);
              break;

          default :
              http_response_code(405);
              throw new Exception('Method \'' . $_SERVER['REQUEST_METHOD'] . '\' not supported');
              return;        
      }

      if ( ($method == 'post') && !isset($parameters['action']) )
      {
          throw new Exception('Missing \'action\' in post \n' . $_SERVER['CONTENT_TYPE'] . '\n' . $a . '\n' . print_r($parameters, true));
          return;
      }

      if ( ($method == 'get')  )
      {
        get_tasks_data();
        return;
      }

      if ( ($method == 'post')  )
      {
        if ( $parameters['action'] != 'insert')
        {
            throw new Exception('Only action allowed is \'insert\'');
            return;
        }

        insert_task_data($parameters['data']);
        return;
      }
  }
  catch (Exception $e)
  {
      error_handler($e->getMessage() . '[' . $e->getLine() .']');
  }

}


request_select();

?>