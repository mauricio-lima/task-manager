<?php
header("Content-type: application/json");
print( 
'[{
    "task_id"     : 1,
    "name"        : "Name 1",
    "description" : "Description 1",
    "start"       : "01/02/2018",
    "finish"      : "01/03/2018",
    "status"      : "Pendente",
    "state"       : "ativo"
},
{
    "task_id"     : 2,
    "name"        : "Name 2",
    "description" : "Description 2",
    "start"       : "01/03/2018",
    "finish"      : "01/04/2018",
    "status"      : "Pendente",
    "state"       : "ativo"
}]');

?>