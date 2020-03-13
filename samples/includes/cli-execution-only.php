<?php

if ( php_sapi_name() !== "cli" ) {
    echo "This app only runs from the console :(" . PHP_EOL;
    exit();
}
