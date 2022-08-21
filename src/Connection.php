<?php

namespace TBCD\Doctrine\HFSQLDriver;

use Doctrine\DBAL\Driver\Connection as ConnectionInterface;
use Doctrine\DBAL\Driver\Result as ResultInterface;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\ParameterType;
use Symfony\Polyfill\Intl\Icu\Exception\MethodNotImplementedException;
use TBCD\Doctrine\HFSQLDriver\Exception\TransactionException;
use com;

class Connection implements ConnectionInterface
{

    /**
     * @var com
     */
    private com $connection;

    /**
     * @param string $dsn
     */
    public function __construct(string $dsn)
    {
        $this->connection = new com("ADODB.Connection");
        $this->connection->ConnectionString = $dsn;
    }


    /**
     * @inheritDoc
     */
    public function prepare(string $sql): Statement
    {
        throw new MethodNotImplementedException('prepare');
    }

    /**
     * @inheritDoc
     */
    public function query(string $sql): ResultInterface
    {
        $this->connection->open($this->connection);
        $recordSet = $this->connection->execute($sql);
        $data = RecordSetConverter::convert($recordSet);
        $recordSet->close();
        $this->connection->close();
        return new Result($data);
    }

    /**
     * @inheritDoc
     */
    public function quote($value, $type = ParameterType::STRING)
    {
        return "'" . str_replace("'", "\'", $value) . "'";
    }

    /**
     * @inheritDoc
     */
    public function exec(string $sql): int
    {
        $this->connection->open();
        $recordSet = $this->connection->execute($sql);
        $data = RecordSetConverter::convert($recordSet);
        $this->connection->close();
        return $data['count'] ?? 0; // TODO: Extract the exact number from the recordSet
    }

    /**
     * @inheritDoc
     */
    public function lastInsertId($name = null): bool|int|string
    {
        //TODO: Implements method
        throw new MethodNotImplementedException('lastInsertId');
    }

    /**
     * @inheritDoc
     */
    public function beginTransaction(): bool
    {
        throw new TransactionException(__METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function commit(): bool
    {
        throw new TransactionException(__METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function rollBack(): bool
    {
        throw new TransactionException(__METHOD__);
    }
}