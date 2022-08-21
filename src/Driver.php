<?php

namespace TBCD\Doctrine\HFSQLDriver;

use Doctrine\DBAL\Driver as DriverInterface;
use Doctrine\DBAL\Driver\API\ExceptionConverter;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\AbstractSchemaManager;

class Driver implements DriverInterface
{

    private ?HFSQLPlatform $platform = null;

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
    public function getDatabasePlatform(): AbstractPlatform
    {
        if (!$this->platform) {
            $this->platform = new HFSQLPlatform();
        }
        return $this->platform;
    }

    /**
     * @inheritDoc
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function getSchemaManager(\Doctrine\DBAL\Connection $conn, AbstractPlatform $platform): AbstractSchemaManager
    {
        return $platform->createSchemaManager($conn);
    }

    /**
     * @inheritDoc
     */
    public function getExceptionConverter(): ExceptionConverter
    {
        // TODO: Implement getExceptionConverter() method.
        throw new \Exception();
    }
}