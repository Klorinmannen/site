<?php
ob_clean();

try {

    // Route new request
    $request = new \api\request();
    $router = new \api\router($request);

    // Map request to a defined endpoint
    $router->map();

    $routed_controller = $router->get_controller();
    if (!class_exists($routed_controller))
        throw new \Exception('Controller not found', 400);

    $routed_method = $router->get_method();    
    if (!method_exists($routed_controller, $routed_method))
        throw new \Exception('Controller method not found', 500);
    
    $controller = new $routed_controller($routed_method, $router->get_params(), $request->get_data());
   
    // Make endpoint call
    $response = $controller->call();

    // Return result/response
    header('Content-Type: application/json');
    echo $response;
    
    http_response_code(200);        

} catch (\Exception $e) {

    echo $e->getMessage();
    http_response_code($e->getCode());        
    exit;
}
