This application is a simple To-Do List REST API, designed to manage tasks through HTTP requests. 

To-Do list consists of tasks, which are defined by following attributes: 
    ID - a unique integer number 
    title - a short description of a task 
    isDone - an indicator showing whether the task is finished.

These correspond to the columns in the Tasks table in the database. SQL query used to create Tasks table:
    
    CREATE TABLE Tasks (
        ID INT PRIMARY KEY AUTO_INCREMENT,
        title VARCHAR(100) NOT NULL,
        isDone TINYINT(1) DEFAULT 0
        ); 



The API allows performing standard CRUD operations via HTTP methods:
    POST - creates a new task by specifying a title value (ID is auto-generated and isDone has default value of 0)
    GET - retrieves information about all tasks in the database
    GET/{id} - retrieves information of a single task by specifying its ID
    PUT/{id} - updates all information of a single task by specifying its ID
    DELETE/{id} - deletes a task from the database by specifying its ID.

HTTP response codes you can get after executing HTTP methods:
    200 OK
    201 Created
    400 Bad Request
    404 Not Found
    400 Internal Server Error.


Coding environment:
    Visual Studio Code with extensions (Code Runner, PHP Debug, PHP Intelephense, PHP Server, REST Client, Postman).

Technologies used to build application: 
    PHP programming language 
    MariaDB (MySQL) database. 

Some other tools include: 
    PDO (PHP Database Objects) for secure database interaction 
    JSON for request/response formatting and 
    Postman for API testing.



Project structure:
    README.md - provides information about the app, including purpose, technologies, project architecture and user manual
    index.php - main application file that handles routing of requests
    task-controller.php - provides connection with the database and contains definitions of CRUD methods    
    test.rest - provides example API requests for testing through REST Client or Postman.

User Manual:
    Start the server - right-click index.php and select PHP Server: Serve project, after which new browser window will open with the app's URL 
    Test API - in test.rest file, click Send request above the desired HTTP method to execute it; ensure that localhost port matches the port shown in the newly opened browser window; by index.php in browser's URL with tasks, you can view all tasks in JSON format; to view a specific task, use tasks/{id}, where {id} is the task's ID 
    Stop the server - after testing, right-click index.php and select PHP Server: Stop server.
