<?php
// Check for empty fields
if(empty($_POST['name'])      ||
   empty($_POST['email'])     ||
   empty($_POST['phone'])     ||
   empty($_POST['message'])   ||
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
   echo "No arguments Provided!";
   return false;
   }
   
$name = strip_tags(htmlspecialchars($_POST['name']));
$email_address = strip_tags(htmlspecialchars($_POST['email']));
$phone = strip_tags(htmlspecialchars($_POST['phone']));
$message = strip_tags(htmlspecialchars($_POST['message']));
   
// Create the email and send the message
$to = 'yourname@yourdomain.com'; // Add your email address in between the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
$email_subject = "Website Contact Form:  $name";
$email_body = "You have received a new message from your website contact form.\n\n"."Here are the details:\n\nName: $name\n\nEmail: $email_address\n\nPhone: $phone\n\nMessage:\n$message";
$headers = "From: noreply@yourdomain.com\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
$headers .= "Reply-To: $email_address";   
//mail($to,$email_subject,$email_body,$headers);


// ACA GRABA EL ARCHIVO.
$Fichero = "archivo.csv"; //nombre del fichero donde se guardan los informes.
$arreglo[0] = date("Y-m-d;H:i:s"); //fecha y hora (por lo general del servidor)\
$arreglo[1] = $_SERVER["REMOTE_ADDR"]; //guarda en la variable el ip
$arreglo[2] = $_SERVER["HTTP_X_FORWARDED_FOR"]; //En caso de usar proxy para esconderse aqui estaria el ip real
$arreglo[3] = $name;
$arreglo[4] = $email_address;
$arreglo[5] = $phone;
$arreglo[6] = $message;
$fp = fopen($Fichero, "a" );
fputcsv($fp, $arreglo, ';', '"');
fclose($fp);

//ACA MANDA EL CORREO.
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '/PHPMailer/Exception.php';
require '/PHPMailer/PHPMailer.php';
require '/PHPMailer/SMTP.php';
//Create a new PHPMailer instance
$mail = new PHPMailer;
$mail->IsHTML(true);
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// SMTP::DEBUG_OFF = off (for production use)
// SMTP::DEBUG_CLIENT = client messages
// SMTP::DEBUG_SERVER = client and server messages
$mail->SMTPDebug = SMTP::DEBUG_SERVER;
//Set the hostname of the mail server
$mail->Host = 'smtp.gmail.com';
// use
// $mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6
//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 587;
//Set the encryption mechanism to use - STARTTLS or SMTPS
$mail->SMTPSecure = 'tls';
$mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => false
                    )
                );
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = 'choikesoft@gmail.com';
//Password to use for SMTP authentication
$mail->Password = 'lacuchadelchoike'; 
//Set who the message is to be sent from
$mail->setFrom('choikesoft@gmail.com', 'YOUR NAME (OPTIONAL)');
//Set who the message is to be sent to
$mail->addAddress('choikesoft@gmail.com', 'choikesoft@gmail.com');
//Set the subject line
$mail->Subject = $arreglo[3]. 'envio un mensaje por el sitio.';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
//Replace the plain text body with one created manually
$mail->Body = 'Nombre: '.$arreglo[3].'<br> Correo: '.$arreglo[4].'<br> Telefono: '.$arreglo[5].'<br> Mensaje: '.$arreglo[6];
  //Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');
//send the message, check for errors
if (!$mail->send()) {
    echo 'Mailer Error: '. $mail->ErrorInfo;
} else {
    echo 'Message sent!';
    //Section 2: IMAP
    //Uncomment these to save your message in the 'Sent Mail' folder.
    #if (save_mail($mail)) {
    #    echo "Message saved!";
    #}
}
///// TERMINA DE MANDAR EL CORREO.

return true;         
?>