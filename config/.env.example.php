<?php
use SAPNWRFC\Connection as SapConnection;
use SAPNWRFC\Exception as SapException;

return (object)[
    'db' => [
        'host' => '172.23.50.95',
        'port' => 1521,
        'user' => 'rrhh',
        'pass' => 'rrhhadmin',
        'charset' => 'UTF8',
        'service_name' => 'service_name'
    ],
    'sap'=>[
        'ashost' => 'ahost',
        'sysnr'  => '00',
        'client' => 'client',
        'user'   => 'user',
        'passwd' => 'passw',
        'trace'  => SapConnection::TRACE_LEVEL_FULL,
    ]
];