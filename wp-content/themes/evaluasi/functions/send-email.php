<?php
/*
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/Exception.php';
require_once 'PHPMailer/SMTP.php';


function email($request) {
    $parameters = $request->get_json_params();
    
    $email = sanitize_text_field($parameters['email']);
    
    $response = send_email($email, 'subject', 'body');

    return $response;
}

function send_email($email, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host       = gethostbyname('smtp.gmail.com');
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sikad.knpk.xyz@gmail.com';
        $mail->Password   = 'spcwwqxdzxgbwxzq';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465; 
        $mail->SMTPOptions = array('ssl' => array('verify_peer_name' => false));                                   //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        $mail->setFrom('belajartpa.com@gmail.com', 'Belajartpa.com', FALSE);
        $mail->addAddress($email);       
        
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        
        $mail->send();

        return 'success';
    } catch (Exception $e) {
        return $mail->ErrorInfo;
    }
}
*/

?>