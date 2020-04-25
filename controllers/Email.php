<?php

class Email
{

    public static function mailFromMe($to, $subject, $msg)
        {$message = "
                <html>
                    <head>
                        <title>$subject.</title>
                    </head>
                    <body>
                        <h3>$subject :</h3>
                        <p>$msg</p>
                    </body>
                </html>
                ";
        $headers = "From: christophe.sd@gmail.com \r\n";
        $headers .= "Reply-To: christophe.sd@gmail.com \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        mail($to, $subject, $message, $headers);
    }

}