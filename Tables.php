<?php

require_once('Connection.php');

try {

$sql = "CREATE TABLE employees(
employee_id INT(6) PRIMARY KEY
)";

$entry = "INSERT INTO employees VALUES(10)";

$conn->exec($sql);
echo "Table created successfully";

$conn->exec($entry);
echo "New record created successfully";
}

catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

?>