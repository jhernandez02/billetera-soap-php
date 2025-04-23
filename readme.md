# Proyecto API SOAP - Billetera Virtual (Symfony)

Este proyecto es una API SOAP desarrollada en Symfony que simula una billetera virtual, permitiendo operaciones como registro, login, carga de saldo, compras y consulta de saldo.

## Requisitos

- PHP 8.1+
- Composer
- Symfony CLI (opcional pero recomendado)
- Servidor web (como Nginx o el integrado de Symfony)
- MySQL o MariaDB

## Instalación

```bash
git clone https://github.com/jhernandez02/billetera-soap-php.git
cd billetera-soap-php
composer install
```

## Configurar las variables de entorno

Configura especialmente la conexión a la base de datos y el proveedor de envio de correos:

```
APP_ENV=dev
APP_SECRET=
APP_HOST="http://localhost:8000"
APP_HOST_API_REST="http://localhost:3000"
DATABASE_URL=""
MAILER_DSN=""
```

## Ejecutar las migraciones y los seeders:

```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
```

## Levantar el servidor:

```
php -S 127.0.0.1:8000 -t public/
```

## Estructura del proyecto

```
├── Command/                        # Comandos de consola
│   └── TruncateDatabaseCommand.php     
├── Controller/                     # Controladores con lógica para cada endpoint
│   └── SOAPController.php
├── DataFixtures/                   # Contiene los seeders para las tablas cliente y billetera
│   └── AppFixtures.php         
├── Entity/                         # Entidad para la tabla billetera.
│   ├── Billetera.php                 
│   ├── Cliente.php
│   ├── Transaccion.php
├── Helper/                         # Funciones utilitarias (toArray, toSoapMap, etc.)
│   └── SessionHelper.php             
│   └── SoapHelper.php
│   └── TokenHelper.php
├── Repository/                     # Repositorios de Doctrine.
│   └── BilleteraRepository.php
│   └── ClienteRepository.php
│   └── TransaccionRepository.php
├── Service/                        # Servicios para conectarse a la base de datos, servicio de correo, etc.
│   └── BilleteraService.php
│   └── ClienteService.php
│   └── NotificacionService.php
├── Soap/                           # Contiene la lógica del servicio SOAP para conectarse con los servicios
│   └── BilleteraSoapHandler.php
│   └── ClienteSoapHandler.php
├── .env                              # Variables de entorno
├── composer.js                       # Configuración general de la app
└── readme.md                         # Documentación del proyecto
```

## WSDL

Los archivos WSDL se encuentra en:

```
Resource/wsdl/
```

**cliente.wsdl** define los siguientes métodos:

- registrarCliente
- loginCliente

**billetera.wsdl** define los siguientes métodos:

- cargarSaldo
- realizarCompra
- confirmarCompra
- consultarSaldo

Todos reciben un parámetro ItemArray y devuelven un objeto MapResponse.

## Usuarios disponibles

| Documento | Email           | Password | Celular   | Billetera_id | Saldo |
|-----------|-----------------|----------|-----------|--------------|-------|
| 11111111  | bart@mail.com   | 11111111 | 911111111 |      1       | 200   |
| 22222222  | homero@mail.com | 22222222 | 922222222 |      2       | 200   |
| 33333333  | lisa@mail.com   | 33333333 | 933333333 |      3       | 200   |

## Documentación de Endpoints

### 1. POST `/soap/cliente`

Registra un nuevo cliente 

#### Body (XML)
```xml
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:localhost">
   <soapenv:Header/>
   <soapenv:Body>
      <urn:registrarCliente>
         <data>
            <item>
                <key>documento</key>
                <value>44444444</value>
            </item>
            <item>
                <key>nombres</key>
                <value>Marge Simpson</value>
            </item>
            <item>
                <key>email</key>
                <value>marge@mail.com</value>
            </item>
           <item>
                <key>celular</key>
                <value>944444444</value>
            </item>
         </data>
      </urn:registrarCliente>
   </soapenv:Body>
</soapenv:Envelope>
```

### 2. POST `/soap/cliente`

Inicia sesión y devuelve los datos del cliente

#### Body (XML)
```xml
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:localhost">
   <soapenv:Header/>
   <soapenv:Body>
      <urn:loginCliente>
         <data>
           <item>
                <key>email</key>
                <value>homero@mail.com</value>
            </item>
            <item>
                <key>password</key>
                <value>22222222</value>
            </item>
         </data>
      </urn:loginCliente>
   </soapenv:Body>
</soapenv:Envelope>
```

### 3. POST `/soap/billetera`

Carga saldo en la billetera 

#### Body (XML)
```xml
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:localhost">
   <soapenv:Header/>
   <soapenv:Body>
      <urn:cargarSaldo>
         <data>
           <item>
                <key>documento</key>
                <value>44444444</value>
            </item>
           <item>
                <key>celular</key>
                <value>944444444</value>
            </item>
            <item>
                <key>valor</key>
                <value>100.0</value>
            </item>
         </data>
      </urn:cargarSaldo>
   </soapenv:Body>
</soapenv:Envelope>
```

### 4. POST `/soap/billetera`

Inicia una compra

#### Body (XML)
```xml
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:localhost">
   <soapenv:Header/>
   <soapenv:Body>
      <urn:realizarCompra>
         <data>
           <item>
                <key>billetera_id</key>
                <value>4</value>
            </item>
           <item>
                <key>monto</key>
                <value>20.0</value>
            </item>
         </data>
      </urn:realizarCompra>
   </soapenv:Body>
</soapenv:Envelope>
```

### 5. POST `/soap/billetera`

Confirma una compra con código 

#### Body (XML)
```xml
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:localhost">
   <soapenv:Header/>
   <soapenv:Body>
      <urn:confirmarCompra>
         <data>
           <item>
                <key>codigo_confirmacion</key>
                <value>196633</value>
            </item>
           <item>
                <key>sesion_id</key>
                <value>vv9f6laan1d7a40srtqqqve7u9</value>
            </item>
         </data>
      </urn:confirmarCompra>
   </soapenv:Body>
</soapenv:Envelope>
```

### 6. POST `/soap/billetera`

Consulta el saldo de la billetera

#### Body (XML)
```xml
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:localhost">
   <soapenv:Header/>
   <soapenv:Body>
      <urn:consultarSaldo>
         <data>
           <item>
                <key>documento</key>
                <value>44444444</value>
            </item>
           <item>
                <key>celular</key>
                <value>944444444</value>
            </item>
         </data>
      </urn:consultarSaldo>
   </soapenv:Body>
</soapenv:Envelope>
```