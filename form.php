<?php

    if (!empty($_POST)) {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = filter_var($value, FILTER_SANITIZE_STRING);
        }
    }

    if (!empty($_POST['contact_email_confirm'])) {
        exit('Email Sent');
    }

    if ($_POST['action'] == 'callback') {
        if (!empty($_POST['callback_name']) && !empty($_POST['callback_number'])) {
            $status = 200;

            mail("wade.danny@gmail.com", "Cobalt Electrics - Request a Callback", "Hello,
The callback form on http://www.cobaltelectrics.co.uk/ has been submitted:
    
Date/Time: " . date("jS F Y H:i") ."
Name: " . $_POST['callback_name'] . "
Telephone No: " . $_POST['callback_number'], "From:noreply@cobaltelectrics.co.uk");
        } else {
            $status = 403;
        }

        exit(json_encode([
            'status' => $status
        ]));
    }

    if ($_POST['action'] == 'contact') {
        // check the fields
        $errors = [];

        if (empty($_POST['contact_name'])) {
            $errors['contact_name'] = true;
        }

        if (empty($_POST['contact_email']) && empty($_POST['contact_number'])) {
            $errors['contact_info'] = true;
        }

        // they have tried an email, verify it
        if (!empty($_POST['contact_email'])) {
            if (!filter_var($_POST['contact_email'], FILTER_VALIDATE_EMAIL)) {
                $errors['contact_email'] = true;
            }
        }

        if (empty($_POST['contact_message'])) {
            $errors['contact_message'] = true;
        }

        $status = (empty($errors)) ? 200 : 403;

        if ($status === 200) {
            mail("wade.danny@gmail.com", "Cobalt Electrics - Contact Form", "Hello,
The contact form on http://www.cobaltelectrics.co.uk/ has been submitted:
    
Date/Time: " . date("jS F Y H:i") ."
Name: " . $_POST['contact_name'] . "
Email Address: " . $_POST['contact_email'] . "
Telephone No: " . $_POST['contact_number'] . "
Message: " . $_POST['contact_message'], "From:noreply@cobaltelectrics.co.uk");
        }

        exit(json_encode([
            'status' => $status,
            'errors' => $errors
        ]));
    }
