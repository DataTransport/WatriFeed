<?php


namespace App\helpers;


//use App\Ticket;
//use Formats;
//use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SendMail
{


    /**
     * MAIL constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param string $receiver_email
     * @param string $comer_name
     * @param string $comer_mail
     * @return bool
     */
    final public static function register_mail(string $receiver_email, string $comer_name, string $comer_mail): bool
    {


        ob_start();

        $to = sprintf('%s', $receiver_email);
        $to2 = sprintf('%s', $comer_mail);

        $boundary = md5(uniqid(microtime(), TRUE));

        $email_body = '--' . $boundary . "\r\n";
        $email_body .= "Content-type: text/html; charset=utf-8\r\n\r\n";

//        $email_subject = sprintf('E-ticket BILLET EXPRESS %s', $ticket->compagnie);

        $email_subject = sprintf('WatriFeed Register Notification');
        $subject = '=?UTF-8?B?' . base64_encode($email_subject) . '?=';


        $boundary = md5(uniqid(microtime(), TRUE));

        $email_body = '--' . $boundary . "\r\n";
        $email_body .= "Content-type: text/html; charset=utf-8\r\n\r\n";
        $email_body .= sprintf('<div>
               Newcomer
               <hr>
               Nom : %s
               <br>
               Email : %s
            </div>',$comer_name, $comer_mail);




        $headers = 'From: WatriFeed Register Notification <labs@data-transport.org>' . "\r\n";
        $headers .= 'Reply-To: labs@data-transport.org' . "\r\n";
//        $headers .='X-Mailer: PHP/' . PHP_VERSION;
        $headers .= 'Mime-Version: 1.0' . "\r\n";
        $headers .= 'Content-Type: multipart/mixed;boundary=' . $boundary . "\r\n";
        $headers .= "\r\n";
        mail($to, $subject, $email_body, $headers);

        $email_body2 = '--' . $boundary . "\r\n";
        $email_body2 .= "Content-type: text/html; charset=utf-8\r\n\r\n";
        $email_body2 .= sprintf('<div>
                Your account is pending approval <br>

            </div>');




        $headers2 = 'From: WatriFeed Register Notification <labs@data-transport.org>' . "\r\n";
        $headers2 .= 'Reply-To: labs@data-transport.org' . "\r\n";
//        $headers .='X-Mailer: PHP/' . PHP_VERSION;
        $headers2 .= 'Mime-Version: 1.0' . "\r\n";
        $headers2 .= 'Content-Type: multipart/mixed;boundary=' . $boundary . "\r\n";
        $headers2 .= "\r\n";
        mail($to2, $subject, $email_body2, $headers2);
//        dd('mail send');
        return true;

    }

    /**
     * @param string $receiver_email
     * @param string $comer_name
     * @param string $comer_mail
     * @return bool
     */
    final public static function active_mail(string $receiver_email, string $comer_name): bool
    {


        ob_start();

        $to = sprintf('%s', $receiver_email);

        $boundary = md5(uniqid(microtime(), TRUE));

        $email_body = '--' . $boundary . "\r\n";
        $email_body .= "Content-type: text/html; charset=utf-8\r\n\r\n";

//        $email_subject = sprintf('E-ticket BILLET EXPRESS %s', $ticket->compagnie);

        $email_subject = sprintf('WatriFeed Notification');
        $subject = '=?UTF-8?B?' . base64_encode($email_subject) . '?=';


        $boundary = md5(uniqid(microtime(), TRUE));

        $email_body = '--' . $boundary . "\r\n";
        $email_body .= "Content-type: text/html; charset=utf-8\r\n\r\n";
        $email_body .= sprintf('<div>
               Hello %s your WatriFeed account is activated
               <hr>
               Login : %s

               <br><br>

                https://watrifeed.ml/login

                <br><br>

                Best regards,
            </div>',$comer_name, $receiver_email);




        $headers = 'From: WatriFeed Register Notification <labs@data-transport.org>' . "\r\n";
        $headers .= 'Reply-To: labs@data-transport.org' . "\r\n";
//        $headers .='X-Mailer: PHP/' . PHP_VERSION;
        $headers .= 'Mime-Version: 1.0' . "\r\n";
        $headers .= 'Content-Type: multipart/mixed;boundary=' . $boundary . "\r\n";
        $headers .= "\r\n";
        mail($to, $subject, $email_body, $headers);

//        dd('mail send');
        return true;

    }

}
