<?php

require('database.php');


class RequestException extends Exception
{
    public function __construct($code = 0, $message, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}


function database_connection()
{
    $database = null;
    $database = new PDO('mysql:host=localhost; dbname=' . Database::NAME . '; charset=utf8', Database::USER, Database::PASSWORD);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $database->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    return $database;
}


function get_tasks_data()
{
    $database = database_connection();

    $statment = $database->prepare('call tasks_list', array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $statment->execute();

    $result = $statment->fetchAll(PDO::FETCH_ASSOC);
    $result = json_encode($result);

    header('Content-type: application/json');
    print_r($result);
}


function insert_task_data($data)
{
    $database = database_connection();

    $statment = $database->prepare('call task_insert(:name, :description, :start, :finish, :status, :active, @json)', array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $statment->bindValue(':name',        $data['name'],        PDO::PARAM_STR);
    $statment->bindValue(':description', $data['description'], PDO::PARAM_STR);
    $statment->bindValue(':start',       $data['start'],       PDO::PARAM_STR);
    $statment->bindValue(':finish',      $data['finish'],      PDO::PARAM_STR);
    $statment->bindValue(':status',       1,           PDO::PARAM_INT);
    $statment->bindValue(':active',       1,           PDO::PARAM_INT);
    $statment->execute();

    $json = $database->query('select @json')->fetchAll(PDO::FETCH_ASSOC)[0]['@json'];

    header('Content-type: application/json');
    print($json);
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
      switch ($_SERVER['REQUEST_METHOD'])
      {
          case 'POST' :
              $method = 'post';
              if ( isset($_SERVER['CONTENT_TYPE']) )
              {
                if ( strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false )
                {
                    $parameters = json_decode(file_get_contents('php://input'), true);
                    if (json_last_error() != JSON_ERROR_NONE)
                    {
                        http_response_code(402);
                        throw new RequestException(406, 'Invalid JSON');                            
                    }
                }
              }
              break;

          case 'GET'  :
              $method = 'get';
              $parameters = normalize_parameters($_GET);
              break;

          default :
              throw new Exception(405, 'Method \'' . $_SERVER['REQUEST_METHOD'] . '\' not supported');
              return;        
      }

      if ( ($method == 'post') && !isset($parameters['action']) )
      {
          throw new RequestException(406, 'Missing \'action\' in post \n' . $_SERVER['CONTENT_TYPE'] . print_r($parameters, true));
          return;
      }

      if ( ($method == 'get')  )
      {
        get_tasks_data();
        return;
      }

      if ( ($method == 'post')  )
      {
        if ( $parameters['action'] == 'insert')
        {
            insert_task_data($parameters['data']);
            return;
        }

        throw new RequestException(406, 'action ' . $parameters['action'] . ' not allowed.');
        return;
      }
}


function error_handler($message, $line, $code)
{
    $log = (new DateTime())->format('Y-m-d H:i:s') . '  ' . $message . '[' . $line .']' . PHP_EOL;
    file_put_contents('errorlog', $log, FILE_APPEND | LOCK_EX);

    header('Content-type: application/json');
    print('{ "code" : ' . $code . ', "message" : "' . $message . '" }');
    die();
}


try
{
    request_select();
}
catch (RequestException $e)
{
    http_response_code($e->getCode());  
    error_handler($e->getMessage(), $e->getLine(), $e->getCode());
}
catch (Exception $e)
{
    http_response_code(500);
    error_handler($e->getMessage(), $e->getLine(), $e->getCode());
}

?>