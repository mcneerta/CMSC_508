<?php

require_once('Connection.php');

$stmt = $conn->prepare("SELECT employee_id, concat(first_name, ', ', last_name) as "Full Name" from employees);
$stmt->execute();

echo "<table style= 'border: solid 1px black;'> ";
echo "<thead><tr><th>ID</th><th>Full Name</th></tr></thead>";
echo "<tbody>";

while ($row = $stmt->fetch()){
       echo "<tr><td>$row[employee_id]</td><td>$row[Full Name]</td><tr>";
}

echo "</tbody>";
echo "</table>";

?>
