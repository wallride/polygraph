<?php


class DeliveryMessage{
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



?>
