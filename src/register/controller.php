<?php

$signup = validate_id_input('signup');

try {
    if ($signup == 1)
        \register\action::signup();
} catch (\Exception $e) {
    echo json_encode($e->getMessage());
}

\register\view::output_default();


