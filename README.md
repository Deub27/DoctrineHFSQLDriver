# Doctrine HFSQL Driver

## Installation

### 1.  Install the doctrine driver

```
composer require tbcd/doctrine-hfsql-driver
```

### 2. Download and install ODBC HFSQL driver

You can find the driver on PCSOFT platform. Here is the link : https://pcsoft.fr/st/telec/modules-communs-27/wx27_103n.htm

### 3. Create your connection

With the DriverManager 
```
$connection = DriverManager::getConnection([
    'host' => '127.0.0.1',
    'user' => 'foo',
    'password' => 'bar',
    'port' => 4900,
    'dbName' => 'DBHF_CF',
    'driverClass' => \TBCD\Doctrine\HFSQLDriver\Driver::class
]);
```
Or with doctrine bundle
```
doctrine:
    dbal:
        default_connection: my_connection
        connections:
            my_connection:
                host: '127.0.0.1'
                user: 'foo'
                password: 'bar'
                dbname: 'DBHF_CF'
                port: 4900
                driver_class: TBCD\Doctrine\HFSQLDriver\Driver
```