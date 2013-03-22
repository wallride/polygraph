<?php

class feedbackController extends BaseController{

    public function run() {
        parent::run();
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
                $form->getList(), 
                'GS-Print - новое обращение '.( date('Y-m-d') ), 
                'i.wallride@gmail.com'
                );
        Delivery::push($msg);
    }

}


?>