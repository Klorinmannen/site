<?php
ob_clean();
$request = \api\request::parse();
$controller = array_shift($request);

try {

    // Map request to an specified endpoint if there is one
    $endpoint = \api\router::map($_SERVER['REQUEST_METHOD'], $controller, \api\request::parse_uri());

    // Define the endpoint call
    $api_endpoint = sprintf('%s\api\controller::%s', $controller, $endpoint);

    // Make the actual endpoint call
    $response_json_data = $api_endpoint( ...$request);

    // Return response data and set response code
    echo $response_json_data;   
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
    

