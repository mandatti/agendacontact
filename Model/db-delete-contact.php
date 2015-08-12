<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_delete = $_POST["id_delete"];

    $db_hostname = "localhost";
    $db_username = "root";
    $db_password = "";
    $db_name = "agenda_contact";

    // Create connection
    $conn = new mysqli($db_hostname, $db_username, $db_password, $db_name);
    // Checking connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Delete
    $sql = "DELETE FROM contacts WHERE id IN ('$id_delete')";

    if ($conn->query($sql) === TRUE) {
        $result_text = "1"; // Contact successfully deleted

        $sql = "DELETE FROM phones WHERE id_contacts IN ('$id_delete')";

        if (!($conn->query($sql) === TRUE)) {
            $result_text = "0"; // Could not delete the contact
        }
    } else {
        $result_text = "0"; // Could not delete the contact
    }

    // Closing connection
    $conn->close();

    echo json_encode(array(
        'result_operation' => $result_text
    ));
} else {
    header("Location: http://localhost/agendacontact/index.php");
}
?>