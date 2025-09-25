<?php
use SAPNWRFC\Connection as SapConnection;
use SAPNWRFC\Exception as SapException;

return (object)[
    'db' => [
        'host' => 'xxx.xx.xx.xxx',
        'port' => 1521,
        'user' => 'user',
        'pass' => 'passw',
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