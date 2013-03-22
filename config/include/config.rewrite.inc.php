<?php

/*
 * Все rewrite правила тут
 */

return array(
'home' =>
    RouterStaticRule::create('/')
    ->setDefaults(array(
            'area' => 'indexController',
            'template'=>'index',
            'redirect'=>'bags'
    )),


/**
 * BAGS
 */
    
'BAGS index' =>
    RouterStaticRule::create('bags')
    ->setDefaults(array(
            'area' => 'pageController',
            'template'=>'bags/index',
            'section'=>'bags',
            'page'=>'index',
    )),

'BAGS materials' =>
    RouterStaticRule::create('bags/materials')
    ->setDefaults(array(
            'area' => 'pageController',
            'template'=>'bags/materials',
            'section'=>'bags',
            'page'=>'materials',
    )),

    
    
    

);