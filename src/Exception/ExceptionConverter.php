<?php

namespace TBCD\Doctrine\HFSQLDriver\Exception;

use Doctrine\DBAL\Driver\API\ExceptionConverter as ExceptionConverterInterface;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\DBAL\Query;

class ExceptionConverter implements ExceptionConverterInterface
{

    /**
     * @inheritDoc
     */
    public function convert(Exception $exception, ?Query $query): DriverException
    {
        return match ($exception->getSQLState()) {
            'HY09' => new BadRequestException($exception, $query),
            default => null
        };
    }
}