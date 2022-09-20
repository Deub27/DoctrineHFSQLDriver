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

use Doctrine\DBAL\Driver\Result as ResultInterface;
use Doctrine\DBAL\Driver\Statement as StatementInterface;
use Doctrine\DBAL\ParameterType;
use TBCD\Doctrine\HFSQLDriver\Exception\Exception;
use TypeError;

final class Statement implements StatementInterface
{

    /**
     * @var mixed
     */
    private mixed $connection;

    /**
     * @var string
     */
    private string $sql;

    /**
     * @var array
     */
    private array $bindValues = [];

    /**
     * @param mixed $connection
     * @param string $sql
     *
     * @internal The statement can be only instantiated by its driver connection.
     */
    public function __construct(mixed $connection, string $sql)
    {
        if (!is_resource($connection)) {
            throw new TypeError(sprintf('The connection passed to %s must of type resource', self::class));
        }

        $this->connection = $connection;
        $this->sql = $sql;
    }


    /**
     * @inheritDoc
     */
    public function bindValue($param, $value, $type = ParameterType::STRING)
    {
        if (is_string($param) && !str_contains($this->sql, ":$param")) {
            throw new Exception("The named param $param doesn't exists in the prepared statement " . $this->sql);
        }

        $this->bindValues[$param] = $type === ParameterType::STRING ? "'$value'" : $value;
    }

    /**
     * @inheritDoc
     */
    public function bindParam($param, &$variable, $type = ParameterType::STRING, $length = null): ?bool
    {
        return $this->bindValue($param, $variable, $type);
    }

    /**
     * @inheritDoc
     */
    public function execute($params = null): ResultInterface
    {
        $finalParams = $this->bindValues;
        $sql = $this->sql;

        foreach (($params ?? []) as $paramId => $paramValue) {
            $finalParams[$paramId] = "'$paramValue'";
        }

        foreach ($finalParams as $key => $value) {
            if (!str_contains($sql, '?')) {
                throw new Exception("Too much parameters given for the prepared statement " . $this->sql);
            }
            if (is_string($key)) {
                $sql = preg_replace('/' . preg_quote(":$key", '/') . '/', $value, $sql, 1);
            } else {
                $sql = preg_replace('/' . preg_quote('?', '/') . '/', $value, $sql, 1);
            }
        }

        $result = odbc_exec($this->connection, $sql);
        if (!$result) {
            throw new Exception(odbc_errormsg($this->connection), odbc_error($this->connection));
        }

        return new Result($result);
    }
}