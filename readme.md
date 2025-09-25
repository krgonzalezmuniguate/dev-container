### 📄 Documentación del Entorno de Desarrollo Docker

Este repositorio contiene un entorno de desarrollo local configurado con **Docker Compose**, que utiliza un servidor **Apache** y un contenedor **PHP-FPM**. El objetivo es proporcionar un ambiente de trabajo consistente y fácil de replicar para cualquier proyecto web municipal.

-----

### 🚀 Inicio Rápido

Para iniciar el entorno de desarrollo, en la raíz del proyecto `/` y ejecutar el siguiente comando:

```bash
docker compose -f .docker/dev/docker-compose.yml up -d
#docker compose -f .docker/dev/docker-compose.yml up --build -d /*Opcional, referencia*/
```

* `up`: Inicia los servicios definidos en `docker-compose.yml`.
* `--build`: Reconstruye las imágenes de Docker. Sí se hicieron cambios en los archivos `Dockerfile`.
* `-d`: Inicia los contenedores en segundo plano (modo "detached").

Una vez que los contenedores estén corriendo, la aplicación estará disponible en la siguiente URL:

`http://161.1.0.3`

-----

### 🛠️ Configuración para Múltiples Instancias

Para una nueva instancia del entorno (por ejemplo, para otro proyecto o para un ambiente de pruebas), se deben de modificar los parámetros en el archivo `.env` y en los archivos de configuración de Apache y Xdebug.

#### Archivo a Modificar:

* **`.docker/dev/.env`**
* **`.configuration/httpd/httpd-fpm.conf`**
* **`.configuration/httpd/httpd.conf`**
* **`.configuration/php/xdebug.ini`**

Cada instancia debe tener valores únicos para evitar conflictos de nombres y redes. A continuación, se detallan los parámetros clave que deben ajustar:

| Parámetro                      | Descripción                                                                                                   | Ejemplo de Cambio                | archivo                   |
|:-------------------------------|:--------------------------------------------------------------------------------------------------------------| :------------------------------- |---------------------------|
| `HOSTNAME`                     | Nombre del host para identificar la instancia. Se usa para los nombres de los contenedores y la red.          | `gds2`, `project-beta`           | `.env`                    |
| `IP_ADDRESS_SEGMENT`           | Segmento de red IP para los contenedores. Revisar de que no entre en conflicto con otras redes.               | `161.1.1`                        | `.env`                    |
| `DOCKER_NETWORK`               | Nombre de la red de Docker. Se genera automáticamente a partir de `HOSTNAME`.                                 | `gds2-network`                   | `.env`                    |
| `DOCKER_CONTAINER_APACHE`      | Nombre del contenedor Apache. Se genera automáticamente a partir de `HOSTNAME`.                               | `gds2-apache`                    | `.env`                    |
| `DOCKER_CONTAINER_PHP`         | Nombre del contenedor PHP. Se genera automáticamente a partir de `HOSTNAME`.                                  | `gds2-phpfpm`                    | `.env`                    |
| `DOCKER_IP_APACHE`             | IP estática para el contenedor Apache. Debe ser única.                                                        | `${IP_ADDRESS_SEGMENT}.3`        | `.env`, `httpd.conf`      |
| `DOCKER_IP_PHP`                | IP estática para el contenedor PHP-FPM. Debe ser única.                                                       | `${IP_ADDRESS_SEGMENT}.4`        | `.env`, `httpd-fpm.conf`  |
| `xdebug.client_port`           | Puerto de Xdebug. Cambiar este puerto para evitar conflictos si hay múltiples instancias de Xdebug corriendo. | `${IP_ADDRESS_SEGMENT}.4`        | `xdebug.ini`              |

#### Ejemplo para una Segunda Instancia (`.env`):

```ini
#hostname
HOSTNAME=gds2
DOMAIN_NAME="${HOSTNAME}.muniguate.com"
IP_ADDRESS_SEGMENT=161.1.1

#APACHE
DOCKER_IMAGE_APACHE=gds-dev-apache24:1.0.0
DOCKERFILE_APACHE=Dockerfile-apache
DOCKER_CONTAINER_APACHE="${HOSTNAME}-apache"
DOCKER_IP_APACHE="${IP_ADDRESS_SEGMENT}.3"

## PHP-FPM
DOCKER_IMAGE_PHP_FPM=gds-dev-php83:1.0.0
DOCKERFILE_PHP_FPM=Dockerfile-php-fpm
DOCKER_CONTAINER_PHP="${HOSTNAME}-phpfpm"
DOCKER_IP_PHP="${IP_ADDRESS_SEGMENT}.4"

## NETWORK
DOCKER_NETWORK="gds-${HOSTNAME}"
DOCKER_NETWORK_GATEWAY="${IP_ADDRESS_SEGMENT}.1"
DOCKER_NETWORK_SUBNET="${IP_ADDRESS_SEGMENT}.0/24"
```

Después de actualizar el archivo `.env`, los archivos de configuración de `httpd.conf`, `httpd-fpm.conf` y `xdebug.ini`, se puede iniciar la nueva instancia con el mismo comando de inicio: `docker compose -f .docker/dev/docker-compose.yml up -d
`

-----

### ⚙️ Detalles Técnicos

#### Estructura de la Red

Los contenedores **Apache** y **PHP** se comunican entre sí a través de una red de Docker privada con **IPs estáticas**. Apache se encuentra en `161.1.0.3` y PHP-FPM en `161.1.0.4`.

#### Volúmenes

Los volúmenes definidos en `docker-compose.yml` (`./../../:/...`) montan los archivos del proyecto local dentro de los contenedores. Esto permite que los cambios en el código se reflejen al instante, sin necesidad de reconstruir las imágenes.

#### Conexiones de Apache

La configuración de Apache (`httpd.conf` y `httpd-fpm.conf`) está optimizada para el desarrollo. Escucha en el puerto `80` y actúa como **proxy inverso** para las solicitudes de PHP.

* En `httpd-fpm.conf`, la directiva `ProxyPassMatch` debe apuntar a la IP del contenedor de PHP-FPM: `ProxyPassMatch ^/(.*\.php(/.*)?)$ fcgi://<DOCKER_CONTAINER_PHP>:9000/var/www/html/$1`.
* En `httpd.conf`, la directiva `ServerName` debe coincidir con la IP del contenedor de Apache: `ServerName <DOCKER_IP_APACHE>`.

#### Configuración de PHP

* **Xdebug**: Está habilitado y configurado para depuración remota. Se conecta a la máquina local (`host.docker.internal`). Para evitar conflictos al correr múltiples instancias, cambiar el puerto en el archivo `xdebug.ini`: `xdebug.client_port = 41711`.
* **Extensiones**: El `Dockerfile-php-fpm` incluye las extensiones como **OCI8** para conexiones a bases de datos Oracle y **SAP NW RFC** para la comunicación con sistemas SAP.

#### Registro de Errores

Para depuración y monitoreo, los logs de Apache y PHP se montan en el directorio `logs` de la raíz del proyecto.

* `logs/httpd/`: Para los logs de acceso y errores de Apache.
* `logs/php/`: Para los logs de PHP.

-----

### 📄 Documentación de Prueba de Conexión

Se detalla los pasos para verificar la conexión a las bases de datos de **Oracle** y **SAP** utilizando el entorno Docker.

-----

### 🛠️ Configuración Inicial

Para que las pruebas de conexión funcionen, es requerido configurar los parámetros de acceso a las bases de datos.

1.  **Renombrar el archivo**: En la carpeta `config`, renombrar el archivo `.env.example.php` a **`env.php`**.

2.  **Configurar credenciales**: en el archivo `env.php` actualizar los siguientes valores con las credenciales proporcionadas:

    * **Para Oracle (`db`)**:

        * `host`: La dirección IP del servidor de la base de datos.
        * `port`: El puerto de conexión.
        * `user`: El usuario de la base de datos.
        * `pass`: La contraseña del usuario.
        * `service_name`: El nombre del servicio de la base de datos.

    * **Para SAP (`sap`)**:

        * `ashost`: El host del sistema SAP.
        * `sysnr`: El número de sistema.
        * `client`: El cliente SAP.
        * `user`: El usuario de SAP.
        * `passwd`: La contraseña del usuario.

-----
### 🚀 Ejecución de la Prueba

Una vez que el entorno Docker esté en funcionamiento, las pruebas se ejecutan automáticamente al acceder a la página principal.

1.  **Iniciar los contenedores**: Si no están activos, ejecutar el comando en la raíz del proyecto `./`:

    ```bash
    docker compose -f .docker/dev/docker-compose.yml up -d
    ```

2.  **Acceder a la URL**: en un navegador pegar la dirección IP del contenedor Apache.

    ```
    http://161.1.0.3
    ```

La página mostrará el estado de la conexión tanto para Oracle como para SAP. Si la conexión es exitosa, verás los detalles de la información de cada sistema, como la versión de Oracle o los recursos de SAP. En caso de falla, se mostrará un mensaje de error.
