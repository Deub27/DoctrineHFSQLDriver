<?php

namespace TBCD\Doctrine\HFSQLDriver;

use Doctrine\DBAL\Driver\Connection as ConnectionInterface;
use Doctrine\DBAL\Driver\Result as ResultInterface;
use Doctrine\DBAL\Driver\Statement as StatementInterface;
use Doctrine\DBAL\ParameterType;
use TBCD\Doctrine\HFSQLDriver\Exception\DriverException;

class Connection implements ConnectionInterface
{

    /**
     * @var mixed
     */
    private mixed $connection;

    /**
     * @param string $dsn
     * @param string $user
     * @param string $password
     */
    public function __construct(string $dsn, string $user, string $password)
    {
        $this->connection = odbc_connect($dsn, $user, $password);
    }


    /**
     * @inheritDoc
     */
    public function prepare(string $sql): StatementInterface
    {
        return new Statement(odbc_prepare($this->connection, $sql));
    }

    /**
     * @inheritDoc
     */
    public function query(string $sql): ResultInterface
    {
        $result = odbc_exec($this->connection, $sql);
        if (!$result) {
            throw new DriverException(odbc_errormsg($this->connection), odbc_error($this->connection));
        }
        return new Result($result);
    }

    /**
     * @inheritDoc
     */
    public function quote($value, $type = ParameterType::STRING): string
    {
        return "'" . str_replace("'", "\'", $value) . "'";
    }

    /**
     * @inheritDoc
     */
    public function exec(string $sql): int
    {
        $result = odbc_exec($this->connection, $sql);
        if (!$result) {
            throw new DriverException(odbc_errormsg($this->connection), odbc_error($this->connection));
        }
        return odbc_num_rows($result);
    }

    /**
     * @inheritDoc
     */
    public function lastInsertId($name = null): bool|int|string
    {
        $sql = "SELECT LAST_INSERT_ID() as last_insert_id FROM $name LIMIT 1";
        $result = odbc_exec($this->connection, $sql);
        if (!$result) {
            throw new DriverException(odbc_errormsg($this->connection), odbc_error($this->connection));
        }
        return odbc_fetch_array($result, 0)['last_insert_id'];
    }

    /**
     * @inheritDoc
     */
    public function beginTransaction(): bool
    {
        return odbc_autocommit($this->connection);
    }

    /**
     * @inheritDoc
     */
    public function commit(): bool
    {
        return odbc_commit($this->connection);
    }

    /**
     * @inheritDoc
     */
    public function rollBack(): bool
    {
        return odbc_rollback($this->connection);
    }
}