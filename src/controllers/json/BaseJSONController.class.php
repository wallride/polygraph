<?php


class BaseJSONController extends BaseController {

    protected $jsonStatus = 200;
    /**
        * @var HttpRequest
        */
    protected $request;
    
    protected $action;


    public function handleRequest(HttpRequest $request){
        $this->request = $request;
        if ($this->request->hasAttachedVar('loggedUser')){
            $this->loggedUser = $this->request->getAttachedVar('loggedUser');
        }
        if ($this->request->hasPostVar('_action')){
            $this->action = $this->request->getPostVar('_action');
        }
        if ($this->request->hasAttachedVar('authRequired') && !$this->loggedUser){
            $this->jsonStatus = 403;
            return;
        }
        return $this->run();
    }




    public function getResultHTML($twig){
        header('Content-type: application/json; charset=utf-8');
        header('Cache-Control: no-cache');
        echo json_encode(
                array(
                    'status'=>$this->jsonStatus,
                    'data'=>$this->toArray($this->resultJSON),
                ), 
                JSON_HEX_APOS|JSON_HEX_QUOT
            );
        exit();            
    }

    
    private function toArray( $data, $depth=7){
        if ($depth<=0) return $list;
        $res = array();
        if (is_array($data)){
            foreach ($data as $k=>$v){
                if ($v instanceof IArrayable)
                    $res[$k] = $v->toArray();
                elseif (is_array($v))
                    $res[$k] = $this->toArray($v ,$depth--);
                else
                    $res[$k] = $v;
            }
        }
        elseif($data instanceof IArrayable)
            return $data->toArray();
        else
            return $data;
        
        return $res;
    }

}



?>