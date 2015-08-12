<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_edit = $_POST["id_edit"];
    $edit_username = $_POST["edit_username"];
    $edit_email = $_POST["edit_email"];
    $edit_address = $_POST["edit_address"];
    $edit_phones = $_POST["edit_phone"];

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

    // Select
    $sql = "SELECT * FROM contacts WHERE email = '$edit_email'";
    $result = $conn->query($sql);

    // Noting that e-mail is already registered and is the contact itself
    $boolean = true;
    if ($result->num_rows > 0) {
        while ($rows_contacts = $result->fetch_assoc()) {
            if (!($rows_contacts["id"] === $id_edit)) {
                $boolean = false;
            }
        }
    }

    if ($boolean) {
        // Updating contact
        $sql = "UPDATE contacts SET username = '$edit_username', email = '$edit_email', address = '$edit_address' WHERE id='$id_edit'";

        if ($conn->query($sql) === TRUE) {
            $result_text = "1"; // Successfully updated contact???
            // Deleting phones

            $sql = "DELETE FROM phones WHERE id_contacts IN ('$id_edit')";

            if ($conn->query($sql) === TRUE) {
                // Entering new phones
                $qt = count($edit_phones);
                for ($i = 0; $i < $qt; $i++) {
                    $sql = "INSERT INTO phones (id_contacts, phone_number)
                VALUES ('$id_edit', '$edit_phones[$i]');";

                    if (!($conn->query($sql) === TRUE)) {
                        $result_text = "0"; // ERROR
                    }
                }
            } else {
                $result_text = "0"; // Could not delete the contact
            }
        } else {
            $result_text = "0"; // ERROR
        }
    } else {
        $result_text = "2"; // Email already registered
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