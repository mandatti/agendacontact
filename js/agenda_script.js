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

    // Function to not move the page by clicking on tags
    $("a[href^=##]").click(function () {
        return false;
    });

    // Function to validate form and insert new contacts
    $("#form_new_contact").validate({
        rules: {
            username: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            address: {
                required: true
            },
            'phone[]': {
                required: true,
                minlength: 14
            },
        },
        messages: {
            username: {
                required: "Enter a name"
            },
            email: {
                required: "Enter an email address",
                email: "Please enter a valid email address"
            },
            address: {
                required: "Enter an address"
            },
            'phone[]': {
                required: "Enter a phone number",
                minlength: "Please enter the 8-digit phone number minimum"
            },
        },
        submitHandler: function () {
            // Check email and contact inserts if not in use
            $form = $('form#form_new_contact');
            $.ajax({
                type: "POST",
                url: "./Model/db-new-contact.php",
                dataType: 'json',
                data: $form.serialize(),
                success: function (results) {
                    switch (results.result_operation) {
                        case ("0"):
                            alert("Error, try again!");
                            window.location.href = "index.php";
                            break;
                        case ("1"):
                            alert("New contact registered successfully!");
                            window.location.href = "index.php";
                            break;
                        case ("2"):
                            $('#label_email_used').css('display', 'block').text("Email already registered, please enter another");
                            break;
                    }
                },
                error: function (results) {
                    alert('Falha de Conexão JSON');
                }
            });
        }
    });

    // Function that cleans content search / filter
    $('#clear_search_button').click(function () {
        $('#contact_list_search').val('');
    });

    // Function that displays phones
    $('.action_more').click(function () {
        ident = $(this).attr('id');
        res = ident.split("_");
        $('#div-phones_' + res[1]).css('display', 'block');
        $('#action-more_' + res[1]).css('display', 'none');
        $('#action-less_' + res[1]).css('display', 'block');
    });

    // Function that hides phones
    $('.action_less').click(function () {
        ident = $(this).attr('id');
        res = ident.split("_");
        $('#div-phones_' + res[1]).css('display', 'none');
        $('#action-more_' + res[1]).css('display', 'block');
        $('#action-less_' + res[1]).css('display', 'none');
    });

    // Function to delete a contact
    $('.action_delete').click(function () {
        ident = $(this).attr('id');
        res = ident.split("_");

        if (confirm('Are you sure you want to delete this contact?')) {
            $dataString = 'id_delete=' + res[1];
            $.ajax({
                type: "POST",
                url: "./Model/db-delete-contact.php",
                dataType: 'json',
                data: $dataString,
                success: function (result) {
                    switch (result.result_operation) {
                        case ("0"):
                            alert("Could not delete the contact, try again!");
                            document.location.reload();
                            break;
                        case ("1"):
                            alert("Contact successfully deleted");
                            document.location.reload();
                            break;
                    }
                },
                error: function (result) {
                    alert('failed');
                }
            });
        }
    });

    // Dynamic fields new contact - phones
    $(function () {
        $('#add_phone').click(function () {
            var num = $('.clonedInput_phone').length,
                    newNum = new Number(num + 1),
                    newElem = $('#entry_phone' + num).clone().attr('id', 'entry_phone' + newNum).fadeIn('slow');
            // Label - Phone
            newElem.find('.label_reference_phone').attr('id', 'label_reference_phone_' + newNum).attr('for', 'label_reference_phone_' + newNum).html('Phone ' + newNum);

            // Input - Phone
            newElem.find('.reference_phone').attr('id', 'phone_' + newNum).attr('name', 'phone[]').val('');

            $('#entry_phone' + num).after(newElem);

            $('#remove_phone').attr('disabled', false);
            if (newNum == 100)
                $('#add_phone').attr('disabled', true);
        });

        $('#remove_phone').click(function () {
            var num = $('.clonedInput_phone').length;
            $('#entry_phone' + num).slideUp('slow', function () {
                $(this).remove();
                if (num - 1 === 1)
                    $('#remove_phone').attr('disabled', true);
                $('#add_phone').attr('disabled', false);
            });
            return false;
            $('#add_phone').attr('disabled', false);
        });
        $('#remove_phone').attr('disabled', true);
    }); // End dynamic fields new contact - phones

    // Function that displays search field / filter
    $(function () {
        $('[data-command="toggle-search"]').on('click', function (event) {
            event.preventDefault();
            $(this).toggleClass('hide-search');
            if ($(this).hasClass('hide-search')) {
                $('.c-search').closest('.row').slideUp(100);
            } else {
                $('.c-search').closest('.row').slideDown(100);
            }
        });
    });
});