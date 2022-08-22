<?php

namespace TBCD\Doctrine\HFSQLDriver;

use Doctrine\DBAL\Driver\Result as ResultInterface;
use Doctrine\DBAL\Driver\Statement as StatementInterface;
use Doctrine\DBAL\ParameterType;
use TBCD\Doctrine\HFSQLDriver\Exception\DriverException;

class Statement implements StatementInterface
{

    /**
     * @var mixed
     */
    private mixed $statement;

    /**
     * @var array
     */
    private array $bindValues = [];

    /**
     * @param mixed $statement
     */
    public function __construct(mixed $statement)
    {
        $this->statement = $statement;
    }


    /**
     * @inheritDoc
     */
    public function bindValue($param, $value, $type = ParameterType::STRING)
    {
        assert(is_int($param));
        $this->bindValues[$param] = $value;
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
        foreach (($params ?? []) as $paramId => $paramValue) {
            $this->bindValues[$paramId] = $paramValue;
        }

        $result = odbc_execute($this->statement, $this->bindValues);
        if (!$result) {
            throw new DriverException(odbc_errormsg($this->statement), odbc_error($this->statement));
        }
        return new Result($result);
    }
}