<?php
$conn = mysqli_connect('localhost', 'root', '', 'user');
if ($conn) {
    echo "--- utang ---\n";
    $res = mysqli_query($conn, "DESCRIBE utang");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            echo "{$row['Field']} - {$row['Type']}\n";
        }
    } else {
        echo "Table 'utang' does not exist.\n";
    }
}
?>
