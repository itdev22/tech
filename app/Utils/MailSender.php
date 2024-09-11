<?php

namespace App\Utils;

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailSender
{
    public static function sendMail()
    {
        // require 'vendor/autoload.php';
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;  //Enable verbose debug output
            $mail->isSMTP();   //Send using SMTP
            $mail->Host       = 'iix40.idcloudhost.com'; //hostname/domain yang dipergunakan untuk setting smtp
            $mail->SMTPAuth   = true;  //Enable SMTP authentication
            $mail->Username   = 'developer@rtrsite.com'; //SMTP username
            $mail->Password   = 'XUu5xwsbjy6a';   //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;   //Enable implicit TLS encryption
            $mail->Port       = 465;   //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            // $mail->setFrom('developer@rtrsite.com', 'Mailer');
            $mail->addAddress('ramamai27@gmail.com', 'Rama');     //email tujuan
            // $mail->addReplyTo('ramaitdev@gmail.com', 'Information'); //email tujuan add reply (bila tidak dibutuhkan bisa diberi pagar)
            // $mail->addCC('ramaitdev@gmail.com'); // email cc (bila tidak dibutuhkan bisa diberi pagar)
            // $mail->addBCC('ramaitdev@gmail.com'); // email bcc (bila tidak dibutuhkan bisa diberi pagar)

            //Attachments
            #$mail->addAttachment('/var/tmp/file.tar.gz');   //Add attachments
            #$mail->addAttachment('/tmp/image.jpg', 'new.jpg');  //Optional name

            //Content
            $mail->isHTML(true);   //Set email format to HTML
            $mail->Subject = 'Here is the subject';
            $mail->Body    = 'This is the HTML message body <b>in bold! thus</b>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public static function SendEmailText($email, $subject, $text)
    {

        $curl = curl_init();

        $postField = [
            'from' => 'test.id <no-reply@test.id>',
            'to' => $email,
            'subject' => $subject,
            'text' => $text
        ];
        $MAILGUN_URL=env('MAILGUN_URL');
        $MAILGUN_KEY=array(
            env('MAILGUN_KEY')
        );
        curl_setopt_array($curl, array(
            CURLOPT_URL => $MAILGUN_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postField,
            CURLOPT_HTTPHEADER => $MAILGUN_KEY,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
    }
}
