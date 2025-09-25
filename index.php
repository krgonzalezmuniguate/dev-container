<?php

/*************** Oracle ***************/
require_once "connection/Oracle.php";

$oracle = new Oracle();

$ConnectionSuccess = $oracle->connect()->message;
$detailConnection = $oracle->information();
$detailConnectionHtml = '';

if ($detailConnection->status){
    foreach ($detailConnection->data as $key => $detail){
        $detailConnectionHtml .= <<<PART
            <div class="detail-item">
                <span class="detail-title">$key:</span>
                <span class="detail-value">$detail</span>
            </div>
        PART;
    }
}
if (!$detailConnection->status){
    $detailConnectionHtml = <<<PART
            <div class="detail-item">
                <span class="detail-title">Mensaje:</span>
                <span class="detail-value">$detailConnection->message</span>
            </div>
        PART;
}

/*************** SAP ***************/
require_once "connection/Sap.php";

$sap = new SAP();
$ConnectionSuccessSAP = $sap->connect()->title;
$detailConnectionSAPHtml = '';

if ($sap->connect()->status){
    $result = $sap->connect()->result;

    foreach ($result as $key => $detail){
        if (!is_iterable($detail)){
            $detailConnectionSAPHtml .= <<<PART
            <div class="detail-item">
                <span class="detail-title">$key:</span>
                <span class="detail-value">$detail</span>
            </div>
        PART;
        }
    }
    foreach ($result['RFCSI_EXPORT'] as $key2 => $value) {
        $detailConnectionSAPHtml .= <<<PART
                <div class="detail-item">
                    <span class="detail-title">$key2:</span>
                    <span class="detail-value">$value</span>
                </div>
                PART;
    }
}



/********
 * Vista
 *******/

echo <<<EOF
    <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Oracle And SAP Test Connection Status</title>
            <link rel="stylesheet" href="styles.css">
        </head>
        <body>
            <div class="container">
                <h1>$ConnectionSuccess</h1>
                <div class="connection-details">
                    $detailConnectionHtml
                </div>
            </div>
            
            <div class="container">
                <h1>$ConnectionSuccessSAP</h1>
                <div class="connection-details">
                    $detailConnectionSAPHtml
                </div>
            </div>
        </body>
</html>

EOF;
//phpinfo();