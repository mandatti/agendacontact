<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" type="image/x-icon" href="media/favicon.ico">
    </head>
    <body>
        <div id="header"> <?php include './header.php'; ?> </div>

        <!-- Modal - New Contact -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="form_new_contact" name="form_new_contact">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">New Contact</h4>
                        </div>
                        <div style="width: auto;" class="container">
                            <div style="width: auto;" class="col-lg-6">
                                <div style="margin-top: 15px;" class="form-group">
                                    <label>Name</label>
                                    <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                        <input type="text" class="form-control" name="username" id="username" placeholder="Username">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <label style="display: none;" class="error" id="label_email_used">Email ja usado</label>
                                    <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                                        <input type="text" class="form-control" name="email" id="email" placeholder="Email">
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></span>
                                        <input type="text" class="form-control" name="address" id="address" placeholder="Address">
                                    </div>
                                </div>
                                <div id="entry_phone1" class="form-group multiple-form-group clonedInput_phone">
                                    <label class="label_reference_phone" id="label_reference_phone_1" for="label_reference_phone_1">Phone 1</label>
                                    <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-earphone"></span></span>
                                        <input type="text" id="phone_1" name="phone[]" class="form-control reference_phone mask_phones" placeholder="Phone">
                                    </div>
                                </div>
                                <span class="input-group-btn dinamic-buttons">
                                    <button type="button" id="add_phone" class="btn btn-success btn-add dinamic-add-button">+</button>
                                    <button type="button" id="remove_phone" class="btn btn-danger btn-remove dinamic-remove-button">-</button>
                                </span><br />


                                <div class="modal-footer">
                                    <input type="submit" id="addContact" class="btn btn-primary"  value="Save">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div> <!-- End Modal - New Contact  -->

        <div style="margin-top: 50px;" class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-offset-3 col-sm-6">
                    <div class="panel panel-default">
                        <form name="form_content_contact" id="form_content_contact" method="GET">
                            <div class="panel-heading c-list">
                                <span class="title">Contacts</span>
                                <ul class="pull-right c-controls myresponsive_panel_heading">
                                    <li><a href="#" data-toggle="modal" data-target="#myModal" data-placement="top" title="Add Contact"><i class="glyphicon glyphicon-plus"></i></a></li>
                                    <li><a href="#" class="hide-search" data-command="toggle-search" data-toggle="tooltip" data-placement="top" title="Toggle Search"><i class="glyphicon glyphicon-search"></i></a></li>
                                </ul>
                            </div>
                            <?php
                            // Function to search / filter content
                            $search = (isset($_GET['contact_list_search'])) ? $_GET['contact_list_search'] : "";
                            if ($search) {
                                ?>
                                <div class="row" style="display: block;">
                                <?php } else { ?>
                                    <div class="row" style="display: none;">
                                    <?php } ?>
                                    <div class="col-xs-12">
                                        <div class="input-group c-search">
                                            <input type="search" class="form-control" id="contact_list_search" name="contact_list_search" value="<?php echo $search; ?>" placeholder="Search by name or email">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" id="clear_search_button"><span class="glyphicon glyphicon-remove text-muted"></span></button>
                                                <button class="btn btn-default" type="submit" id="search_button"><span class="glyphicon glyphicon-search text-muted"></span></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if (!isset($_GET[''])) {
                                    ?>
                                    <ul class="list-group" id="contact-list">
                                        <?php
                                        // Create connection
                                        $conn = new mysqli("localhost", "root", "", "agenda_contact");
                                        // Checking connection
                                        if ($conn->connect_error) {
                                            die("Connection failed: " . $conn->connect_error);
                                        }
                                        // Checks if the current page is informed in the URL, but assign as 1st page
                                        $pagina = (isset($_GET['page'])) ? $_GET['page'] : 1;
                                        // Selects all table items
                                        $sql = "SELECT * FROM contacts";
                                        $result = $conn->query($sql);
                                        // Account the total of items
                                        $total = $result->num_rows;
                                        // Sets the number of items per page, in this case, items 2
                                        $registros = 5;
                                        // Calculates the number of pages rounding the result up
                                        $numPaginas = ceil($total / $registros);
                                        // Variable to calculate the start of the display based on the current page
                                        $inicio = ($registros * $pagina) - $registros;
                                        // Select items per page
                                        $sql = "SELECT * FROM contacts " .
                                                (isset($_GET['contact_list_search']) ? "WHERE username LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%'" : '') .
                                                " ORDER BY username ASC LIMIT $inicio,$registros";
                                        $result = $conn->query($sql);

                                        if ($total > 0) {
                                            // Output data of each row
                                            while ($row = $result->fetch_assoc()) {

                                                $id = $row["id"];
                                                ?>
                                                <li class="list-group-item">
                                                    <div class=" col-sm-3">
                                                        <img style="float: left;" id="img_contact" src="media/avatar.png" alt="Scott Stevens" class="myresponsive_img img-circle" />
                                                    </div>
                                                    <div id="data_contact" class="col-sm-9 myresponsive_div">
                                                        <div class="btn-group myresponsive_actions">
                                                            <a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#">Action <span class="icon-cog icon-white"></span><span class="caret"></span></a>
                                                            <ul style="min-width: auto;" class="dropdown-menu">
                                                                <li><a class="action_edit" href="edit-contact.php?id=<?php echo base64_encode($row["id"]); ?>" id="edit_<?php echo $row["id"]; ?>"><span class="glyphicon glyphicon-pencil" style="margin-left: -10px; margin-right: 10px;"></span> Edit</a></li>
                                                                <li><a class="action_delete" href="#" id="delete_<?php echo $row["id"]; ?>"><span class="glyphicon glyphicon-trash" style="margin-left: -10px; margin-right: 10px;" ></span>Delete</a></li>
                                                            </ul>
                                                        </div><br />
                                                        <span class="name"><?php echo $row["username"]; ?></span><br />
                                                        <span class="glyphicon glyphicon-envelope text-muted c-info"></span>
                                                        <span class="text-muted"><?php echo $row["email"]; ?></span><br/>
                                                        <span class="glyphicon glyphicon-map-marker text-muted c-info"></span>
                                                        <span class="text-muted"><?php echo $row["address"]; ?></span><br/>
                                                        <span><a class="action_more" id="action-more_<?php echo $row["id"]; ?>" href="##">more...</a></span>
                                                        <div style="display: none;" id="div-phones_<?php echo $row["id"]; ?>">
                                                            <?php
                                                            $sql = "SELECT * FROM phones WHERE id_contacts = '$id'";
                                                            $result_phones = $conn->query($sql);

                                                            if ($result_phones->num_rows > 0) {
                                                                // Output data of each row
                                                                while ($row_phones = $result_phones->fetch_assoc()) {
                                                                    ?>
                                                                    <span class="glyphicon glyphicon-earphone text-muted c-info"></span>
                                                                    <span class="text-muted"><?php echo $row_phones["phone_number"]; ?></span><br />
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                            <span><a style="display: none;" class="action_less" id="action-less_<?php echo $row["id"]; ?>" href="##">...less</a></span>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </li>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <li class="list-group-item">
                                                <span class="name">No contacts</span>

                                            </li>
                                        <?php }
                                        ?>

                                        <div style='text-align: center; margin-top: 5px; margin-bottom: -15px;'>
                                            <?php
                                            // Displays pagination
                                            for ($i = 1; $i < $numPaginas + 1; $i++) {
                                                echo "<a style='color: #FFF; text-decoration: none' href='agenda.php?page=$i' >
                                        <button style='padding: 1px 6px;' type='button' class='btn btn-primary'>" . $i . "</button>
                                    </a>";
                                            }
                                            ?>
                                        </div>
                                    </ul>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="header"> <?php include './footer.php'; ?> </div>

    </body>
</html>