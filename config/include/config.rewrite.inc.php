<?php

/*
 * Все rewrite правила тут
 */

return array(
'home' =>
    RouterStaticRule::create('/')
    ->setDefaults(array(
            'area' => 'indexController',
            '_template'=>'index',
            '_redirect'=>'bags'
    )),

'ABOUT' =>
    RouterStaticRule::create('about')
    ->setDefaults(array(
            'area' => 'pageController',
            '_template'=>'about',
            '_page'=>'about',
    )),
'Contact' =>
    RouterStaticRule::create('contact')
    ->setDefaults(array(
            'area' => 'feedbackController',
            '_template'=>'contact',
            '_page'=>'contact',
    )),


/**
 * BAGS
 */
    
'BAGS index' =>
    RouterStaticRule::create('bags')
    ->setDefaults(array(
            'area' => 'pageController',
            '_template'=>'bags/index',
            '_section'=>'bags',
            '_page'=>'index',
    )),

'BAGS portfolio' =>
    RouterStaticRule::create('bags/samples')
    ->setDefaults(array(
            'area' => 'pageController',
            '_template'=>'bags/samples',
            '_section'=>'bags',
            '_page'=>'samples',
    )),
'BAGS materials' =>
    RouterStaticRule::create('bags/materials')
    ->setDefaults(array(
            'area' => 'pageController',
            '_template'=>'bags/materials',
            '_section'=>'bags',
            '_page'=>'materials',
    )),
'BAGS price calculator' =>
    RouterStaticRule::create('bags/price')
    ->setDefaults(array(
            'area' => 'pageController',
            '_template'=>'bags/price',
            '_section'=>'bags',
            '_page'=>'price',
    )),
'BAGS submit order' =>
    RouterStaticRule::create('bags/order')
    ->setDefaults(array(
            'area' => 'feedbackController',
            '_template'=>'bags/order',
            '_section'=>'bags',
            '_page'=>'order',
    )),

    
    
    

);