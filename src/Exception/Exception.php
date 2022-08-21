<?php

namespace TBCD\Doctrine\HFSQLDriver\Exception;

use Doctrine\DBAL\Driver\Exception as ExceptionInterface;

class Exception extends \Exception implements ExceptionInterface
{

    /**
     * @inheritDoc
     */
    public function getSQLState(): string|null
    {
        return null;
    }
}