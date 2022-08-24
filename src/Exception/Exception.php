<?php

namespace TBCD\Doctrine\HFSQLDriver\Exception;

use Doctrine\DBAL\Driver\Exception as DriverExceptionInterface;

class Exception extends \Exception implements DriverExceptionInterface
{

    /**
     * @var string|null
     */
    private ?string $sqlState;

    /**
     * @param string $message
     * @param string|null $sqlState
     */
    public function __construct(string $message, ?string $sqlState = null)
    {
        parent::__construct($message);
        $this->sqlState = $sqlState;
    }


    /**
     * @inheritDoc
     */
    public function getSQLState(): string|null
    {
        return $this->sqlState;
    }
}