<?php

/**
 * Description of SimpleMail
 *
 * @author wallride
 */
class SimpleMail {
    //put your code here
    private $users = array();
    /**
     *
     * @var ModelAndView
     */
    private $mav = array();
    
    private $templateName;
    
    public function __construct($view) {
//        $this->mav->setView(SimplePhpView::create());
    }
    
    public function setData($name, $value){
//        $this->mav->getModel()->set($name, $var);
        return $this;
    }
    public function addUser(User $user){
        $this->users[$user->getEmail()] = $user;
        return $this;
    }
    
    public function send(){
        $headers = 'From: webmaster@example.com' . "\r\n" .
            'Reply-To: webmaster@example.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        mail('i.wallride@gmail.com', 'test', 'message', $headers);
    }
}

?>
