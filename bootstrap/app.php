<?php

/**
 * 
 */
require __DIR__. '/../env.php';

require __DIR__. '/../routes/web.php';


$router = router($routes);
$router->run();
