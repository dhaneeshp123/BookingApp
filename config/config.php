<?php

if(empty(getenv('APP_ENV'))){
    putenv('APP_ENV=development');
}
$env = getenv('APP_ENV');
switch ($env) {

    case 'development':
        $config = [
            'database_server' => '127.0.0.1',
            'database_port_number' => 3036,
            'database_name' => 'booking_app',
            'database_username' => 'root',
            'database_password' => '',
        ];
        break;
    case 'testing':
        $config = [
            'database_server' => '127.0.0.1',
            'database_port_number' => 3036,
            'database_name' => 'booking_app_test',
            'database_username' => 'root',
            'database_password' => '',
        ];
        break;
}
