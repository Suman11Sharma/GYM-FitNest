<?php
$conn = new mysqli("localhost", "username", "password", "database");
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM contact_us WHERE id=$id");
}
