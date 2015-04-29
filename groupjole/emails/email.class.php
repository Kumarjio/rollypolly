<?php
class Email
{
    public function send_email($toEmail, $subject, $template, $params=array())
    {
        include 'Mail.php';
        include 'Mail/mime.php' ;
        log_log('Email Function Started', 'START:');
        
        ob_start();
        include EMAILTEMPLATEDIR.'/'.$template;
        $msg = ob_get_clean();
        $text = strip_tags($msg);
        $html = $msg;
        $crlf = "\n";
        $hdrs = array(
                      'From'    => FROM_EMAIL,
                      'Subject' => $subject
                      );
        
        $mime = new Mail_mime($crlf);
        
        $mime->setTXTBody($text);
        $mime->setHTMLBody($html);
        
        $body = $mime->get();
        $hdrs = $mime->headers($hdrs);
        log_log($toEmail, 'toEmail:');
        log_log(var_export($hdrs, 1), 'hdrs:');
        log_log($body, 'body:');
        
        $mail =& Mail::factory('mail');
        $mail->send($toEmail, $hdrs, $body); 
        log_log('Email Function Ended', 'END:');
    }
}

$emailClass = new Email();

function send_email($toEmail, $subject, $template, $params=array())
{
    global $emailClass;
    $emailClass->send_email($toEmail, $subject, $template, $params);
    return true;
}