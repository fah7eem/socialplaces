<?php
namespace System;
use PHPMailer\PHPMailer\PHPMailer;
class Email extends PHPMailer
{
    function __construct(){
        parent::__construct(true);
        $this->isSMTP();
        $this->Host       = MAIL_SMTP;
        $this->SMTPAuth   = true;
        $this->Username   = MAIL_FROM;
        $this->Password   = MAIL_PASS;
        $this->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->Port       = MAIL_PORT;
        $this->isHTML(true);
        $this->setFrom(MAIL_FROM, MAIL_NAME);
    }

    public function setBodyContents($contents, $alternative){
        $this->Body    = '
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <style>
        .center {
          margin: auto;
          width: 60%;
          border: 3px solid red;
          padding: 10px;
        }
        .linkb{
            text-decoration:none;
            color: white;
            font-size:20px;
            background-color:red;
            padding:5px;
        }
        </style>
        <body>            
        <div class="center">
            '.$contents.'
        </div>
        </body>
        </html>';
        $this->AltBody = $alternative;
    }

}