<?php

class feedbackController extends BaseController{

    public function run() {
        parent::run();
        
        if (count($_POST)<1) return;
        
        $form = Form::create();
        $form->add(Primitive::string('name')->required())
            ->add(
                Primitive::string('email')
                    ->setImportFilter(
                            FilterChain::create()
                            ->add(Filter::trim())
                            ->add(Filter::lowerCase())
                    )
                    ->setAllowedPattern(PrimitiveString::MAIL_PATTERN)->required()
            )
            ->add(Primitive::string('phone'))
            ->add(Primitive::string('company'))
            ->add(Primitive::string('order'))
            ->add(Primitive::string('question'))
            ->add(Primitive::boolean('deadline'));
        ;
        $form->import($this->request->getPost());
        if ($form->getErrors()){
            $this->setFormErrors($form->getErrors());
            return;
        }
        
        
        $msg = new DeliveryMessage(
                'feedback', 
                array(
                    'name'=>$form->getValue('name'),
                    'email'=>$form->getValue('email'),
                    'phone'=>$form->getValue('phone'),
                    'company'=>$form->getValue('company'),
                    'order'=>$form->getValue('order'),
                    'question'=>$form->getValue('question'),
                    'deadline'=>$form->getValue('deadline'),
                ), 
                'GS-Print - новое обращение', 
                'i.wallride@gmail.com'
                );
        Delivery::push($msg);
        $this->resultData['feedbackSent']=true;
    }

}


?>