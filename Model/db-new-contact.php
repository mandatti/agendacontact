<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST["username"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $phones = $_POST["phone"];

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
    $sql = "SELECT * FROM contacts WHERE email = '$email'";
    $result = $conn->query($sql);

    // Checking that email already registered
    if ($result->num_rows > 0) {
        $result_text = "2"; // Email already registered
    } else {
        // Entering a new contact
        $sql = "INSERT INTO contacts (username, email, address)
                VALUES ('$username', '$email', '$address');";

        if ($conn->query($sql) === TRUE) {
            $result_text = "1"; // New contact registered successfully??
            $last_id = $conn->insert_id;

            // Entering phone numbers
            $qt = count($phones);
            for ($i = 0; $i < $qt; $i++) {
                $sql = "INSERT INTO phones (id_contacts, phone_number)
                VALUES ('$last_id', '$phones[$i]');";

                if (!($conn->query($sql) === TRUE)) {
                    $result_text = "0"; // ERROR
                }
            }
        } else {
            $result_text = "0"; // ERROR
        }
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