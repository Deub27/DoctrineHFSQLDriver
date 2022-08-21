<?php

namespace TBCD\Doctrine\HFSQLDriver\Exception;

use Exception;
use Doctrine\DBAL\Driver\Exception as ExceptionInterface;
use TBCD\Doctrine\HFSQLDriver\Driver;

class TransactionException extends Exception implements ExceptionInterface
{

    /**
     * @param string $calledMethod
     */
    public function __construct(string $calledMethod)
    {
        $message = sprintf('The %s doesnt support transactions. The method %s cannot be called', Driver::class, $calledMethod);
        parent::__construct($message);
    }


    /**
     * @inheritDoc
     */
    public function getSQLState(): string|null
    {
        return null;
    }
}