<?php

namespace TBCD\Doctrine\HFSQLDriver;

use Doctrine\DBAL\Driver as DriverInterface;
use Doctrine\DBAL\Driver\API\ExceptionConverter as ExceptionConverterInterface;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use TBCD\Doctrine\HFSQLDriver\Exception\ExceptionConverter;
use TBCD\Doctrine\HFSQLDriver\Platform\HFSQLPlatform;

class Driver implements DriverInterface
{

    /**
     * @var HFSQLPlatform|null
     */
    private ?HFSQLPlatform $platform = null;

    /**
     * @var ExceptionConverter|null
     */
    private ?ExceptionConverterInterface $exceptionConverter = null;

    /**
     * @inheritDoc
     */
    public function connect(array $params): Connection
    {
        $dsn = sprintf('DRIVER={HFSQL};Server Name=%s;Server Port=%s;Database=%s;UID=%s;PWD=%s', $params['host'], $params['port'], $params['dbname'], $params['user'], $params['password']);
        return new Connection($dsn, $params['user'], $params['password']);
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
            $this->exceptionConverter = new ExceptionConverter();
        }
        return $this->exceptionConverter;
    }
}