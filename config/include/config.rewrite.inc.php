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
            '_redirect'=>'bags/'
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

/**
 * CORPORATE
 */
    
'CORPORATE index' =>
    RouterStaticRule::create('corporate')
    ->setDefaults(array(
            'area' => 'pageController',
            '_template'=>'corporate/index',
            '_section'=>'corporate',
            '_page'=>'index',
    )),
'CORPORATE price calculator' =>
    RouterStaticRule::create('corporate/price')
    ->setDefaults(array(
            'area' => 'pageController',
            '_template'=>'corporate/price',
            '_section'=>'corporate',
            '_page'=>'price',
    )),
'CORPORATE submit order' =>
    RouterStaticRule::create('corporate/order')
    ->setDefaults(array(
            'area' => 'feedbackController',
            '_template'=>'corporate/order',
            '_section'=>'corporate',
            '_page'=>'order',
    )),

    
    
/**
 * EVENTS
 */
    
'EVENTS index' =>
    RouterStaticRule::create('events')
    ->setDefaults(array(
            'area' => 'pageController',
            '_template'=>'events/index',
            '_section'=>'events',
            '_page'=>'index',
    )),

    
/**
 * PACK
 */
    
'PACK index' =>
    RouterStaticRule::create('pack')
    ->setDefaults(array(
            'area' => 'pageController',
            '_template'=>'pack/index',
            '_section'=>'pack',
            '_page'=>'index',
    )),

/**
 * POS
 */
    
'POS index' =>
    RouterStaticRule::create('POS')
    ->setDefaults(array(
            'area' => 'pageController',
            '_template'=>'POS/index',
            '_section'=>'POS',
            '_page'=>'index',
    )),

/**
 * SOUVENIR
 */
    
'SOUVENIR index' =>
    RouterStaticRule::create('souvenir')
    ->setDefaults(array(
            'area' => 'pageController',
            '_template'=>'souvenir/index',
            '_section'=>'souvenir',
            '_page'=>'index',
    )),

    
/**
 * OUTDOOR
 */
    
'OUTDOOR index' =>
    RouterStaticRule::create('outdoor')
    ->setDefaults(array(
            'area' => 'pageController',
            '_template'=>'outdoor/index',
            '_section'=>'outdoor',
            '_page'=>'index',
    )),

    
    

    
    
    
/**
 * STATIC PAGES
 */    
    
'Landing page 1' =>
    RouterStaticRule::create('hello')
    ->setDefaults(array(
            'area' => 'pageController',
            '_template'=>'landing/lp1',
//            '_page'=>'about',
    )),
    
    

);