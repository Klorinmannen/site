<?php
namespace login;

$login = validate_id_input('login');

try {
    if ($login == 1)
        \login\action::login();
} catch (\Exceptionn $e) {
    echo json_encode($e->getMessage());
}

\login\view::output_default();
