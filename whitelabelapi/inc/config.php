<?php
    session_start();
    //b50ad5b651f9f01bd8532581bab0e13a -- outback
    //3e5b3d33173e0b41502a67fef9030d58 -- oz snow
    //92500b01909454c2255b94305044bc51 -- aj hacket
    //c91fb8908bfd6807133067df218c192b -- st christopher
    define('J6_TOKEN', 'b50ad5b651f9f01bd8532581bab0e13a');
    define('J6_BASE_URL','http://beta.junction6travel.com/jsonservice/v1/');
    
     
    $action = isset($_REQUEST['action']) ? filter_var($_REQUEST['action'], FILTER_DEFAULT) : null;
    $redirectBackUrl = isset($_REQUEST['BackURL']) ? filter_var($_REQUEST['BackURL'], FILTER_DEFAULT) : null;
    
    include 'inc/session.php';