<?php

namespace TBCD\Doctrine\HFSQLDriver;

use Doctrine\DBAL\Driver\Result as ResultInterface;

class Result implements ResultInterface
{

    /**
     * @var mixed
     */
    private mixed $result;

    /**
     * @param mixed $result
     */
    public function __construct(mixed $result)
    {
        $this->result = $result;
    }


    /**
     * @inheritDoc
     */
    public function fetchNumeric(): array
    {
        odbc_fetch_row($this->result);
        return odbc_fetch_array($this->result);
    }

    /**
     * @inheritDoc
     */
    public function fetchAssociative(): array|false
    {
        return odbc_fetch_array($this->result);
    }

    /**
     * @inheritDoc
     */
    public function fetchOne(): array
    {
        odbc_fetch_row($this->result, 0);
        return odbc_fetch_array($this->result);
    }

    /**
     * @inheritDoc
     */
    public function fetchAllNumeric(): array
    {
        $data = [];
        while (odbc_fetch_row($this->result)) {
            $data[] = array_values(odbc_fetch_array($this->result));
        }
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function fetchAllAssociative(): array
    {
        $data = [];
        while ($row = odbc_fetch_array($this->result)) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function fetchFirstColumn(): array
    {
        $data = [];
        while (odbc_fetch_row($this->result)) {
            $data[] = odbc_fetch_array($this->result);
        }
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function rowCount(): int
    {
        return odbc_num_rows($this->result);
    }

    /**
     * @inheritDoc
     */
    public function columnCount(): int
    {
        return odbc_num_fields($this->result);
    }

    /**
     * @inheritDoc
     */
    public function free(): void
    {
        odbc_free_result($this->result);
    }
}