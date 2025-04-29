<?php

    class TaskController
    {
        private $pdo;

        public function __construct()
        {
            //Connecting to MariaDB and saving the connection inside the controller:
            $this->pdo = new PDO('mysql:host=localhost;dbname=demo;charset=utf8mb4', 
            'root', 
            'gavran77'); 

            //Configuring PDO to throw Exceptions when there are database errors:
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        }

        function getTasks()
        {
            try 
            {
                //Running an SQL query to select all rows from the Tasks table and storing them inside $tasks:
                $statement = $this->pdo->query('SELECT * FROM tasks');
                $tasks = $statement->fetchAll(PDO::FETCH_ASSOC);
                http_response_code(200);
                echo json_encode($tasks);
            }
            catch (Exception $e)
            {
                //If a runtime error occurs, give client an appropriate message:
                http_response_code(500);
                echo json_encode(['Error' => 'Server Error']);
            }
        }

        //getTaskById method recieves id parameter which represents id of task we want to retrieve 
        function getTaskById($id)
        {
            try
            {
                $statement = $this->pdo->prepare("SELECT * FROM tasks WHERE id = :id");
                $statement->execute(['id' => $id]);
                $task = $statement->fetch(PDO::FETCH_ASSOC);

                // If no task found with the given ID, return a 404 error:
                if ($task) 
                {
                    http_response_code(200);
                    echo json_encode($task);
                } 
                else 
                {
                    http_response_code(404);
                    echo json_encode(['Error' => 'Task not found']);
                }
            }
            catch (Exception $e) 
            {
                http_response_code(500);
                echo json_encode(['Error' => 'Server Error']);
            }
        }


        function createTask()
        {
            //Reading raw JSON data sent by client and decoding into PHP array:
            $input = json_decode(file_get_contents('php://input'), true);

            //If the title field doesn't exist, send a bad request error:
            if (!isset($input['title']))
            {
                http_response_code(400);
                echo json_encode(['Error' => 'Title is required']);
                return;
            }

            //Assigning to variables that are used to create new task:
            $title = htmlspecialchars($input['title']);
            $isDone = isset($input['isDone']) ? (int)$input['isDone'] : 0;

            try 
            {
                //Running an SQL query for insertion of a new task:
                $statement = $this->pdo->prepare("INSERT INTO tasks (title, isDone) VALUES (:title, :isDone)");
                $statement->execute(['title' => $title, 'isDone' => $isDone]);
                http_response_code(201);
                echo json_encode(['Success' => 'Task created']);
            } 
            catch (Exception $e) 
            {
                //If a runtime error occurs, give client an appropriate message:
                http_response_code(500);
                echo json_encode(['Error' => 'Server Error']);
            }
        }

        function updateTask($id)
        {
            //Reading raw JSON data sent by client and decoding into PHP array:
            $input = json_decode(file_get_contents('php://input'), true);

            //If the title field doesn't exist, send a bad request error:
            if (!$id || !isset($input['title']) || !isset($input['isDone']))
            {
                http_response_code(400);
                echo json_encode(['Error' => 'ID, title and isDone are required']);
                return;
            }

            //Assigning to variables needed to update existing task:
            $title = htmlspecialchars($input['title']);
            $isDone = (int)$input['isDone'];

            try 
            {
                //Running an SQL query for updating a row based on ID:
                $statement = $this->pdo->prepare("UPDATE tasks SET title = :title, isDone = :isDone WHERE id = :id");
                $statement->execute(['title' => $title, 'isDone' => $isDone, 'id' => $id]);

                if ($statement->rowCount() === 0)
                {
                    http_response_code(404);
                    echo json_encode(['Error' => 'Task not found']);
                }
                else
                {
                    http_response_code(200);
                    echo json_encode(['Success' => 'Task updated']);
                }
            } 
            catch (Exception $e) 
            {
                //If a runtime error occurs, give client an appropriate message:
                http_response_code(500);
                echo json_encode(['Error' => 'Server error']);
            }
        }

        //deleteTask method recieves id parameter which represents id of task we want to delete, so there won't be any assigning in the body:
        function deleteTask($id) 
        {
            //If the ID field doesn't exist, send a bad request error:
            if (!$id)
            {
                http_response_code(400);
                echo json_encode(['Error' => 'ID is required']);
                return;
            }

            try 
            {
                //Running an SQL query for deleting a row based on ID:
                $statement = $this->pdo->prepare("DELETE FROM tasks WHERE id = :id");
                $statement->execute(['id' => $id]);

                if ($statement->rowCount() === 0)
                {
                    http_response_code(404);
                    echo json_encode(['Error' => 'Task not found']);
                }
                else
                {
                    http_response_code(200);
                    echo json_encode(['Success' => 'Task deleted']);
                }
            } 
            catch (Exception $e) 
            {
                //If a runtime error occurs, give client an appropriate message:
                http_response_code(500);
                echo json_encode(['Error' => 'Server error']);
            }
        }
    }
?>