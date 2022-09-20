<?php

/*
 * This file is part of the tbcd/doctrine-hfsql-driver package.
 *
 * (c) Thomas Beauchataud <thomas.beauchataud@yahoo.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TBCD\Doctrine\HFSQLDriver;

use Doctrine\DBAL\Driver\Connection as ConnectionInterface;
use Doctrine\DBAL\Driver\Result as ResultInterface;
use Doctrine\DBAL\Driver\Statement as StatementInterface;
use Doctrine\DBAL\ParameterType;
use TBCD\Doctrine\HFSQLDriver\Exception\Exception;

final class Connection implements ConnectionInterface
{

    /**
     * @var mixed
     */
    private mixed $connection;

    /**
     * @param string $dsn
     * @param string $user
     * @param string $password
     * @throws Exception
     */
    public function __construct(string $dsn, string $user, string $password)
    {
        $connection = odbc_connect($dsn, $user, $password);
        if (!$connection) {
            throw new Exception(odbc_errormsg(), odbc_error());
        }

        $this->connection = $connection;
    }


    /**
     * @inheritDoc
     */
    public function prepare(string $sql): StatementInterface
    {
        return new Statement($this->connection, $sql);
    }

    /**
     * @inheritDoc
     */
    public function query(string $sql): ResultInterface
    {
        $result = odbc_exec($this->connection, $sql);
        if (!$result) {
            throw new Exception(odbc_errormsg($this->connection), odbc_error($this->connection));
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
            throw new Exception(odbc_errormsg($this->connection), odbc_error($this->connection));
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
            throw new Exception(odbc_errormsg($this->connection), odbc_error($this->connection));
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