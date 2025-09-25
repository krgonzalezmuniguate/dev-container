<?php

use SAPNWRFC\Connection as SapConnection;
use SAPNWRFC\Exception as SapException;

final class SAP{
    public function connect():object
    {
        $params = require "./config/env.php";

        try{

            $c = new SapConnection($params->sap);

            $f = $c->getFunction('RFC_SYSTEM_INFO');

            $result = $f->invoke();

            return (object)['status'=>true, 'title'=>'Successfully connected to SAP', 'result'=> $result];
        }catch(SapException $ex){
            return (object)['status'=>false, 'title'=>'Error connecting to SAP', 'result'=> $ex->getMessage()];
        }
    }
}
