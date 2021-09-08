<?php
// Configure your Subject Prefix and Recipient here
$subjectPrefix = '[Contact via website]';
$emailTo = 'daoudireda100@gmail.com';
$errors = array(); // array to hold validation errors
$data = array(); // array to pass back data

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = stripslashes(trim($_POST['form-name']));
    $email = stripslashes(trim($_POST['form-email']));
    $subject = stripslashes(trim($_POST['form-subject']));
    $message = stripslashes(trim($_POST['form-message']));

    /*$secret = "6LcTJIIUAAAAAH4FQM1TG3dIn8Xxu0TN_CtAgPFu";*/
 /*   $response = stripslashes(trim($_POST['g-recaptcha-response']));*/
    $remoteip = $_SERVER['REMOTE_ADDR'];

   /* $api_url = "https://www.google.com/recaptcha/api/siteverify?secret="
        . $secret
        . "&response=" . $response
        . "&remoteip=" . $remoteip;
    $decode = json_decode(file_get_contents($api_url), true);*/

   /* if ($decode['success'] == true) {*/
        //If i'm not a robot
        /////////////////////
      

        if (empty($name)) {
            $errors['form-name'] = 'Le nom est obligatoire.';
        }

        if (empty($tel)) {
            $errors['form-tel'] = 'Le teléphone est obligatoire.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['form-email'] = 'L\'email est obligatoire.';
        }

        if (empty($subject)) {
            $errors['form-subject'] = 'Le sujet est obligatoire.';
        }

        if (empty($message)) {
            $errors['form-message'] = 'Le message est obligatoire.';
        }
        // if there are any errors in our errors array, return a success boolean or false
        if (!empty($errors)) {
            $data['form-success'] = false;
            $data['form-errors'] = $errors;
            foreach ($errors as $value) {
                echo $value . '<br />';
            }
        } else {
            $subject = "$subjectPrefix $subject";
            $body = '
                    <strong>Name: </strong>' . $name . '<br />
                    <strong>Email: </strong>' . $email . '<br />
                    <strong>Message: </strong>' . nl2br($message) . '<br />
                   
                ';
            $headers = "MIME-Version: 1.1" . PHP_EOL;
            $headers .= "Content-type: text/html; charset=utf-8" . PHP_EOL;
            $headers .= "Content-Transfer-Encoding: 8bit" . PHP_EOL;
            $headers .= "Date: " . date('r', $_SERVER['REQUEST_TIME']) . PHP_EOL;
            $headers .= "Message-ID: <" . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . '@' . $_SERVER['SERVER_NAME'] . '>' . PHP_EOL;
            $headers .= "From: " . "=?UTF-8?B?" . base64_encode($name) . "?=" . "<$email>" . PHP_EOL;
            $headers .= "Return-Path: $emailTo" . PHP_EOL;
            $headers .= "Reply-To: $email" . PHP_EOL;
            $headers .= "X-Mailer: PHP/" . phpversion() . PHP_EOL;
            $headers .= "X-Originating-IP: " . $_SERVER['SERVER_ADDR'] . PHP_EOL;

            if (mail($emailTo, "=?utf-8?B?" . base64_encode($subject) . "?=", $body, $headers)) {
                echo "Votre message a bien été envoyé";
            } else {
                echo "Une erreur s'est produite votre mail n'a pas été envoyé. veuillez réessayer utlérieurement";
            }
            //  $data['form-success'] = true;
            //$data['form-message'] = 'Votre message a bien été envoyyé!';
            //////////////////////////
            /**
             * } else {
             * echo "Nous avons détecté que vous êtes un robot :/"
             * }**/
        }
   /* } else {
        echo "Nous avons détecté que vous êtes un robot.";
    }*/
    //If was a robot
    // return all our data to an AJAX call
    //echo json_encode($errors);
}
?>