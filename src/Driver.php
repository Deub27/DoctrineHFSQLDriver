<?php

namespace TBCD\Doctrine\HfsqlDriver;

use Doctrine\DBAL\Driver as DriverInterface;
use Doctrine\DBAL\Driver\API\ExceptionConverter;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class Driver implements DriverInterface
{

    /**
     * @inheritDoc
     */
    public function connect(array $params): Connection
    {
        $dsn = sprintf('Provider=PCSOFT.HFSQL;Data Source=%s:%s;User ID=%s;Password=%s;Initial Catalog=%s', $params['host'], $params['port'], $params['user'], $params['password'], $params['dbname']);
        return new Connection($dsn);
    }

    /**
     * @inheritDoc
     */
    public function getDatabasePlatform()
    {
        // TODO: Implement getDatabasePlatform() method.
    }

    /**
     * @inheritDoc
     */
    public function getSchemaManager(\Doctrine\DBAL\Connection $conn, AbstractPlatform $platform)
    {
        // TODO: Implement getSchemaManager() method.
    }

    /**
     * @inheritDoc
     */
    public function getExceptionConverter(): ExceptionConverter
    {
        throw new \Exception();
    }
}