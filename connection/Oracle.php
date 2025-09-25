<?php

final class Oracle {
    private object $params;
    private $connection = null;

    public function __construct() {
        $this->params = require "./config/env.php";
    }

    public function connect(): object {
        if ($this->connection !== null) {
            return (object)['status' => true, 'message' => 'Conexión lista a Oracle.', 'db' => $this->connection];
        }


        if (!extension_loaded('oci8')) {
            return (object)['status' => false, 'message' => 'OCI8 No esta instalado.'];
        }

        try {
            $errorReporting = error_reporting();
            error_reporting($errorReporting & ~E_WARNING);

            $db = oci_connect(
                $this->params->db['user'],
                $this->params->db['pass'],
                $this->stringConnection(),
                'AL32UTF8'
            );


            error_reporting($errorReporting);

            if ($db === false) {
                $error = oci_error();
                return (object)[
                    'status' => false,
                    'message' => 'Conexión fallida: ' . ($error['message'] ?? 'Error desconocido'),
                    'code' => $error['code'] ?? null
                ];
            }

            $this->connection = $db;
            return (object)['status' => true, 'message' => 'Conexión exitosa a oracle.', 'db' => $db];

        } catch(Exception $e) {
            return (object)['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function information(): object {
        $connectionResult = $this->connect();

        if (!$connectionResult->status) {
            return $connectionResult; // Retorna el error de conexión
        }

        try {
            $query = "SELECT * FROM product_component_version";
            $result = oci_parse($this->connection, $query);

            if ($result === false) {
                $error = oci_error($this->connection);
                return (object)[
                    'status' => false,
                    'message' => 'Parse error: ' . ($error['message'] ?? 'Error en la consulta')
                ];
            }

            if (!oci_execute($result)) {
                $error = oci_error($result);
                return (object)[
                    'status' => false,
                    'message' => 'Execute error: ' . ($error['message'] ?? 'Error en la consulta')
                ];
            }

            $data = oci_fetch_object($result);
            oci_free_statement($result);

            return (object)['status' => true, 'message' => 'Consulta ejecutada correctamente', 'data' => $data];

        } catch(Exception $e) {
            return (object)['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function close(): void {
        if ($this->connection !== null) {
            oci_close($this->connection);
            $this->connection = null;
        }
    }

    public function __destruct() {
        $this->close();
    }

    private function stringConnection(): string {
        return "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = {$this->params->db['host']})(PORT = {$this->params->db['port']})))(CONNECT_DATA=(SERVICE_NAME={$this->params->db['service_name']})))";
    }
}