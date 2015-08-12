<?php
//Getting handle passed by the GET method
$id_edit = base64_decode($_GET['id']);
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" type="image/x-icon" href="media/favicon.ico">

        <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#id_edit').val('<?php echo $id_edit; ?>');
            });

        </script>
    </head>
    <body>

        <div id="header"> <?php include './header.php'; ?> </div>

        <form id="form_edit_contact" name="form_edit_contact">
            <div style="margin-top: 50px; margin-bottom: 43px;" class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-offset-3 col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading c-list">
                                <span class="title">Edit Contact</span>
                            </div>

                            <input type="hidden" id="id_edit" name="id_edit">

                            <?php
                            // Create connection
                            $conn = new mysqli("localhost", "root", "", "agenda_contact");
                            // Checking connection
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            // Select
                            $sql = "SELECT * FROM contacts WHERE id = '$id_edit'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // Output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    ?>

                                    <div style="width: auto; float: none;" class="col-lg-6">
                                        <div style="margin-top: 15px;" class="form-group">
                                            <label>Name</label>
                                            <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                                <input type="text" class="form-control" name="edit_username" id="edit_username" value="<?php echo $row["username"]; ?>" placeholder="Username">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <label style="display: none;" class="error" id="label_edit_email_used">Email ja usado</label>
                                            <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                                                <input type="text" class="form-control" name="edit_email" id="edit_email" value="<?php echo $row["email"]; ?>" placeholder="Email">
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <label>Address</label>
                                            <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></span>
                                                <input type="text" class="form-control" name="edit_address" id="edit_address" value="<?php echo $row["address"]; ?>" placeholder="Address">
                                            </div>
                                        </div>
                                        <?php
                                        $sql = "SELECT * FROM phones WHERE id_contacts = '$id_edit'";
                                        $result_phones = $conn->query($sql);

                                        if ($result_phones->num_rows > 0) {
                                            // Output data of each row
                                            $count = 0;
                                            while ($row_phones = $result_phones->fetch_assoc()) {
                                                $count++;
                                                ?>
                                                <div id="edit_entry_phone<?php echo $count; ?>" class="form-group multiple-form-group edit_clonedInput_phone">
                                                    <label class="edit_label_reference_phone" id="edit_label_reference_phone_<?php echo $count; ?>" for="edit_label_reference_phone_<?php echo $count; ?>">Phone <?php echo $count; ?></label>
                                                    <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-earphone"></span></span>
                                                        <input type="text" id="edit_phone_<?php echo $count; ?>" name="edit_phone[]" value="<?php echo $row_phones["phone_number"]; ?>"class="form-control edit_reference_phone mask_phones" placeholder="Phone">
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <div id="entry_phone1" class="form-group multiple-form-group clonedInput_phone">
                                                <label class="label_reference_phone" id="label_reference_phone_1" for="label_reference_phone_1">Phone 1</label>
                                                <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-earphone"></span></span>
                                                    <input type="text" id="phone_1" name="phone[]" class="form-control reference_phone" placeholder="Phone">
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <span class="input-group-btn dinamic-buttons">
                                            <button type="button" id="edit_add_phone" class="btn btn-success btn-add dinamic-add-button">+</button>
                                            <button type="button" id="edit_remove_phone" class="btn btn-danger btn-remove dinamic-remove-button">-</button>
                                        </span><br />

                                        <div class="modal-footer">
                                            <input type="submit" id="editContact" class="btn btn-primary"  value="Save">
                                            <a style="color: #FFF; text-decoration: none;" href="index.php"><button type="button" class="btn btn-primary">Back</button></a>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <div class="modal-footer">
                                        <a style="color: #FFF; text-decoration: none;" href="index.php"><button type="button" class="btn btn-primary">Back</button></a>
                                    </div>
                                <?php } ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div id="header"> <?php include './footer.php'; ?> </div>

    </body>
</html>