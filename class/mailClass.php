<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
include_once (Config::$site_path."plugins/phpmailer/class.phpmailer.php");
class MailClass{
    private $utils;
    public function __construct(){
        $this->utils=new Utils();
    }
    public function sendMailFunction($subject,$message){
        $mail = new PHPMailer();
        $mail->IsMail(); // IsMail
        $mail->FromName = 'The Top Remodeler Experts-Mailer'; // This is the from name in the email, you can put 
        $mail->AddReplyTo('mike@thetopremodelers.com', 'The Top Remodeler Experts-Contact');
        $mail->Subject  = $subject;
        $mail->AltBody  = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
        $mail->AddAddress('mike@thetopremodelers.com', 'Mike');
        $mail->AddBCC('pavanratnakar@gmail.com', 'Pavan Ratnakar');
        $mail->ConfirmReadingTo = 'pavanratnakar@gmail.com';
        $mail->MsgHTML($message);
        $status=$mail->Send();
        if (!$status) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
?>