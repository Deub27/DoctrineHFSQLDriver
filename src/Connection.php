<?php

namespace TBCD\Doctrine\HfsqlDriver;

use COM;
use Doctrine\DBAL\Driver\Connection as ConnectionInterface;
use Doctrine\DBAL\Driver\Result as ResultInterface;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\ParameterType;
use Symfony\Polyfill\Intl\Icu\Exception\MethodNotImplementedException;

class Connection implements ConnectionInterface
{

    /**
     * @var \COM
     */
    private COM $connection;

    /**
     * @param string $dsn
     */
    public function __construct(string $dsn)
    {
        $this->connection = new COM("ADODB.Connection");
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
        try {
            $results = [];
            $this->connection->open();
            $recordSet = new COM("ADODB.Recordset");
            $recordSet->open($sql, $this->connection);

            while (!$recordSet->EOF) {

                $row = [];

                for ($x = 0; $x < $recordSet->Fields->Count; $x++) {

                    $value = $recordSet->Fields[$x]->value;
                    $field = $recordSet->Fields[$x]->name;

                    if ($recordSet->Fields[$x]->type == 133) {
                        $date = (string)$recordSet->Fields[$x]->value;
                        switch ($date) {
                            case '':
                            case '30/11/1999':
                                $date = null;
                                break;
                            default:
                                $exploded = explode('/', $date);
                                $rev = array_reverse($exploded);
                                $date = implode('-', $rev);
                        }
                        $value = $date;
                    }

                    $row[$field] = $value;
                }
                $results[] = $row;
                $recordSet->MoveNext();
            }

            $recordSet->close();

            return new Result($results);
        } catch (\com_exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function quote($value, $type = ParameterType::STRING)
    {
        throw new MethodNotImplementedException('quote');
    }

    /**
     * @inheritDoc
     */
    public function exec(string $sql): int
    {
        throw new MethodNotImplementedException('exec');
    }

    /**
     * @inheritDoc
     */
    public function lastInsertId($name = null): bool|int|string
    {
        throw new MethodNotImplementedException('lastInsertId');
    }

    /**
     * @inheritDoc
     */
    public function beginTransaction(): bool
    {
        throw new MethodNotImplementedException('beginTransaction');
    }

    /**
     * @inheritDoc
     */
    public function commit(): bool
    {
        throw new MethodNotImplementedException('commit');
    }

    /**
     * @inheritDoc
     */
    public function rollBack(): bool
    {
        throw new MethodNotImplementedException('rollBack');
    }
}