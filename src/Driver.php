<?php

namespace TBCD\Doctrine\HFSQLDriver;

use Doctrine\DBAL\Driver as DriverInterface;
use Doctrine\DBAL\Driver\API\ExceptionConverter as ExceptionConverterInterface;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use TBCD\Doctrine\HFSQLDriver\Platform\HFSQLPlatform;

class Driver implements DriverInterface
{

    private ?HFSQLPlatform $platform = null;
    private ?ExceptionConverterInterface $exceptionConverter = null;

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
     * @throws Exception
     */
    public function getSchemaManager(\Doctrine\DBAL\Connection $conn, AbstractPlatform $platform): AbstractSchemaManager
    {
        return $platform->createSchemaManager($conn);
    }

    /**
     * @inheritDoc
     */
    public function getExceptionConverter(): ExceptionConverterInterface
    {
        if (!$this->exceptionConverter) {
            $this->exceptionConverter = new DriverInterface\API\SQLSrv\ExceptionConverter();
        }
        return $this->exceptionConverter;
    }
}