<?php

require_once('Connection.php');

$stmt = $conn->prepare("SELECT employee_id from employees);
$stmt->execute();

echo "<table style= 'border: solid 1px black;'> ";
echo "<thead><tr><th>ID</th></tr></thead>";
echo "<tbody>";

while ($row = $stmt->fetch()){
       echo "<tr><td>$row[employee_id]</td></tr>";
}

echo "</tbody>";
echo "</table>";

?>
