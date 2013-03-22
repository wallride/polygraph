<?php


class Message{
    public
            $template,
            $email,
            $name,
            $subject,
            $data = array();
    /**
     *
     * @param type $template
     * @param type $data
     * @param type $subject
     * @param type $email
     * @param type $name 
     */
    public function __construct($template, $data, $subject, $email, $name=''){
        $this->template = $template;
        $this->email = $email;
        $this->name = $name;
        $this->subject = $subject;
        $this->data = array(
            'email'=>$this->email,
            'name'=>$this->name,
            'subject'=>$this->subject,
            'data'=>$data,
        );
    }
    
    
}


class Delivery {
    
    static private $logPath = '/tmp/mail';

    static 
        $messages = array(),
        $mailHeaders = 'From: info@handsandheads.ru\nContent-type:text/html; charset=\'utf-8\'';

    static function push(Message $message){
        self::$messages[] = $message;
    }
    static function deliverMail ($twig, $saveToLog=false){
        $headers  = "Content-type: text/html; charset=utf-8 \r\n";
        $headers .= "From: HANDS&HEADS robot <info@handsandheads.ru>\r\n";
//        $headers .= "Bcc: birthday-archive@example.com\r\n"; 
        ini_set('sendmail_path',"/usr/sbin/sendmail -t -i");
        ini_set('SMTP',"localhost");
        ini_set('smtp_port',"25");
        ini_set('mail.add_x_header',"0");
        foreach (self::$messages as $msg){
            $text = $twig->render(
                'mail/'.$msg->template.'.twig.html', 
                $msg->data
            );
            if ($saveToLog || __LOCAL_DEBUG__){
                file_put_contents(self::$logPath.'/'.date('Y-m-d H:i:s').'_for_'.$msg->email, $text, FILE_APPEND);
            }
            else{
//$sendmail = "/usr/sbin/sendmail -t -f info@handsandheads.com -C /etc/sendmail.orig.cf";
//$fd = popen($sendmail, "w");
//fputs($fd, "To: i.wallride@gmail.com\r\n");
//fputs($fd, "From: \"Sender Name\" <info@handsandheads.com>\r\n");
//fputs($fd, "Subject: Finally\r\n");
//fputs($fd, "X-Mailer: Mailer Name\r\n\r\n");
//fputs($fd, $text);
//pclose($fd);                 
                
                mail($msg->email, $msg->subject, $text, $headers);
            }
        }
        return;
        
        
    }

}

?>
