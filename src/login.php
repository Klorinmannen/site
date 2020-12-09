<?php

//$login = validate_id_input('login');
if ($login === 1)
    \login\action::login();

\login\view::output_default();

