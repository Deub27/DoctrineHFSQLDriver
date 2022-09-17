<?php

/*
 * This file is part of the tbcd/doctrine-hfsql-driver package.
 *
 * (c) Thomas Beauchataud <thomas.beauchataud@yahoo.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TBCD\Doctrine\HFSQLDriver\Exception;

use Doctrine\DBAL\Driver\API\ExceptionConverter as ExceptionConverterInterface;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\DBAL\Exception\SyntaxErrorException;
use Doctrine\DBAL\Query;

class ExceptionConverter implements ExceptionConverterInterface
{

    /**
     * @inheritDoc
     */
    public function convert(Exception $exception, ?Query $query): DriverException
    {
        return match ($exception->getSQLState()) {
            'HY09' => new SyntaxErrorException($exception, $query),
            'IM002' => new ConnectionException($exception, $query),
            default => new DriverException($exception, $query)
        };
    }
}