$(document).ready(function () {
    // Regex for mascara phone number
    var SPMaskBehavior = function (val) {
        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    },
            spOptions = {
                onKeyPress: function (val, e, field, options) {
                    field.mask(SPMaskBehavior.apply({}, arguments), options);
                }
            };
    $('.mask_phones').mask(SPMaskBehavior, spOptions);

    // Function to validate and update the contact form
    $("#form_edit_contact").validate({
        rules: {
            edit_username: {
                required: true
            },
            edit_email: {
                required: true,
                email: true
            },
            edit_address: {
                required: true
            },
            'edit_phone[]': {
                required: true,
                minlength: 14
            },
        },
        messages: {
            edit_username: {
                required: "Enter a name"
            },
            edit_email: {
                required: "Enter an email address",
                email: "Please enter a valid email address"
            },
            edit_address: {
                required: "Enter an address"
            },
            'edit_phone[]': {
                required: "Enter a phone number",
                minlength: "Please enter the 8-digit phone number minimum"
            },
        },
        submitHandler: function () {
            // Check email and contact inserts if not in use
            $form = $('form#form_edit_contact');
            $.ajax({
                type: "POST",
                url: "./Model/db-edit-contact.php",
                dataType: 'json',
                data: $form.serialize(),
                success: function (results) {
                    switch (results.result_operation) {
                        case ("0"):
                            alert("Error, try again!");
                            window.location.href = "index.php";
                            break;
                        case ("1"):
                            alert("Successfully modified contact!");
                            window.location.href = "index.php";
                            break;
                        case ("2"):
                            $('#label_edit_email_used').css('display', 'block').text("Email already registered, please enter another");
                            break;
                    }
                },
                error: function (results) {
                    alert('Falha de Conexão JSON');
                }
            });
        }
    });

    // Dynamic fields update contact - phones
    $(function () {
        // Enabling / Disabling add button
        if ($('.edit_clonedInput_phone').length >= 100) {
            $('#edit_remove_phone').attr('disabled', false);
            $('#edit_add_phone').attr('disabled', true);
        }
        // Enabling / Disabling remove button
        if ($('.edit_clonedInput_phone').length < 2) {
            $('#edit_remove_phone').attr('disabled', true);
        }

        $('#edit_add_phone').click(function () {
            var num = $('.edit_clonedInput_phone').length,
                    newNum = new Number(num + 1),
                    newElem = $('#edit_entry_phone' + num).clone().attr('id', 'edit_entry_phone' + newNum).fadeIn('slow');
            // Label - Phone
            newElem.find('.edit_label_reference_phone').attr('id', 'edit_label_reference_phone_' + newNum).attr('for', 'edit_label_reference_phone_' + newNum).html('Phone ' + newNum);

            // Input - Phone
            newElem.find('.edit_reference_phone').attr('id', 'edit_phone_' + newNum).attr('name', 'edit_phone[]').val('');

            $('#edit_entry_phone' + num).after(newElem);

            $('#edit_remove_phone').attr('disabled', false);
            if (newNum == 100)
                $('#edit_add_phone').attr('disabled', true);
        });

        $('#edit_remove_phone').click(function () {
            var num = $('.edit_clonedInput_phone').length;
            $('#edit_entry_phone' + num).slideUp('slow', function () {
                $(this).remove();
                if (num - 1 === 1)
                    $('#edit_remove_phone').attr('disabled', true);
                $('#edit_add_phone').attr('disabled', false);
            });
            return false;
            $('#edit_add_phone').attr('disabled', false);
        });
    }); // End dynamic fields update contact - phones
});