<?php


abstract class BaseController {

	protected $controllerName ;
	protected $templateFile;
	protected $resultData = array();
	protected $resultHTML;
	protected $resultJSON;
        
        protected $redirectTo = null;
        /**
         * @var User
         */
        protected $loggedUser;
        /**
         * @var HttpRequest
         */
        protected $request;
        
        
        protected $formErrors;



        public function __construct() {
            $this->controllerName = str_replace('Controller', '', get_called_class());
            $this->resultData['request']= array_merge($_GET, $_POST);
            return $this;
        }
        public function run(){
            return $this;
        }
        
        public function handleRequest(HttpRequest $request){
            $this->request = $request;
//            if ($this->request->hasAttachedVar('loggedUser')){
//                $this->loggedUser = $this->request->getAttachedVar('loggedUser');
//                $this->resultData['loggedUser'] = $this->loggedUser;
//            }
            if ($request->hasGetVar('template')){
                $this->setTemplateName($request->getGetVar('template'));
            }
            if ($request->hasGetVar('redirect')){
                $this->redirectNow($request->getGetVar('redirect'));
            }
//            if ($this->request->hasAttachedVar('authRequired') && !$this->loggedUser){
//                $this->setTemplateName('auth/signin');
//                return;
//            }
            return $this->run();
        }
        
        
	
	public function setTemplateName($name=null){
		$n = (!$name) ? $this->controllerName : $name;
		$this->templateFile = $n .'.twig.html';
	}

        protected function setFormErrors($arr){
            $this->formErrors = $arr;
        }
        
	public function getResultHTML($twig){
            if (!is_null($this->redirectTo)){
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: ".PATH_WEB.$this->redirectTo);
                exit();            
            }
            if(!$this->templateFile) $this->setTemplateName();
            try{
                $html = $twig->render(
                    $this->templateFile, 
                    array(
                        'data'=>$this->resultData,
                        'baseUrl'=>PATH_WEB,
                        'formErrors' => (count($this->formErrors)>0) ? $this->formErrors: null,
                        'formErrorsJSON' => (count($this->formErrors)>0) ? json_encode($this->formErrors, JSON_HEX_APOS|JSON_HEX_QUOT): null,
                        'navigation'=>array(
                            'section'=>$this->request->hasGetVar('section') ? $this->request->getGetVar('section') : null,
                            'page'=>$this->request->hasGetVar('page') ? $this->request->getGetVar('page') : null,
                            'structure'=> require '../config/include/config.navigation.inc.php',
                        ),
                    )
                );
            }
            catch(Exception $e){
                LoggerUtils::log(__FILE__.'@'.__LINE__.': '.$e->getMessage(), true);
                $this->setTemplateName('404');
                $html = $this->getResultHTML($twig);
            }
            return $html;
	}

        
        
        protected function redirectNow($path){
            $this->redirectTo = $path;
        }
}



?>