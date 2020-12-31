<?php
ob_clean();

try {
    // Handle new request
    $request = new \api\request();

    // Instantiate the controller object
    $endpoint_controller = $request->get_endpoint_controller();    
    $controller = new $endpoint_controller($request);

    // Map request to a defined endpoint
    $controller->map_endpoint();
    
    // Make endpoint call
    $controller->call_endpoint();

    // Return response data and set response code
    echo $controller->get_json_encoded_response();

    http_response_code(200);        

} catch (\Exception $e) {

    switch ($e->getCode()) {
    case 500:
        http_response_code(500);        
        break;
    case 400:
        echo $e->getMessage();
        http_response_code(400);
        break;
    }
    exit;
}
