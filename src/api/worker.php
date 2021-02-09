<?php
ob_clean();

try {

    // Route new request
    $request = new \api\request();
    $router = new \api\router($request);

    // Map request to a defined endpoint
    $router->map();

    $routed_resource_controller = $router->get_resource_api_controller();
    if (!class_exists($routed_resource_controller))
        throw new \Exception('Controller not found', 500);

    $routed_method = $router->get_method();    
    if (!method_exists($routed_resource_controller, $routed_method))
        throw new \Exception('Controller method not found', 500);
    
    $routed_resource_model = $router->get_resource_api_model();
    if (!class_exists($routed_resource_model))
        throw new \Exception('Resource model not found', 500);
    
    $controller = new $routed_resource_controller($routed_method, $router->get_params(), $request->get_data(), $routed_resource_model);
   
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
