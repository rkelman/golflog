<?php
require_once '../include/log.php';

$logs = getLogs();

echo "<table>\n";
echo "<th><td>log ID</td><td>Date Time</td><td>App</td><td>Message</td></th>\n";
for($i=0;$i<count($logs);$i++) {
    echo('<tr>');
    echo('<td>' . $logs[$i]['logID'] . '</td>');
    echo('<td>' . $logs[$i]['logDateTime'] . '</td>');
    echo('<td>' . $logs[$i]['app'] . '</td>');
    echo('<td>' . $logs[$i]['message'] . '</td>');
    echo('</tr>\n');   
}
echo "</table>\n";
?>