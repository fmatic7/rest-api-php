<?php

    //Loading TaskController class from another file:
    require 'task-controller.php';

    //Creating an instance of TaskController for the future operations:
    $controller = new TaskController();

    //Detecting which HTTP method (GET, POST, PUT, DELETE) will be used based on superglobal $_SERVER array:
    $method = $_SERVER['REQUEST_METHOD'];

    //Getting and processing the URL path by splitting it wherever is /
    $request = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    
    //Check if the first part of URL is tasks and rejecting if it isn't, sending an error message:
    if ($request[0] !== 'tasks')
    {
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        exit();
    }

    //Calling an HTTP method based on the recieved value:
    switch ($method)
    {
        case 'GET':
            //GET method can return all tasks or a single task by specifying id, depending on the URL we provide
            if (isset($request[1])) 
            {
                $controller->getTaskById((int)$request[1]);
            } 
            else 
            {
                // If no ID is provided, return all tasks
                $controller->getTasks();
            }
            break;
        case 'POST':
            $controller->createTask();
            break;
        case 'PUT':
            $id = isset($request[1]) ? (int)$request[1] : null;
            $controller->updateTask($id);
            break;
        case 'PATCH':
            if (isset($request[1]) && isset($request[2]))
            {
                if ($request[2] === 'title') 
                {
                    $controller->updateTitle((int)$request[1]);
                } 
                elseif ($request[2] === 'status') 
                {
                    $controller->updateStatus((int)$request[1]);
                } else 
                {
                    http_response_code(400);
                    echo json_encode(['error' => 'Invalid PATCH operation']);
                }
            }
            else
            {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid PATCH request']);
            }
            break;
        case 'DELETE':
            $id = isset($request[1]) ? (int)$request[1] : null;
            $controller->deleteTask($id);
            break;
        default:  
            http_response_code(405);
            echo json_encode(['error' => 'Invalid Method']);
            break;
    }

?>