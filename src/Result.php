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
    public function fetchNumeric(): array|false
    {
        $fieldNames = [];
        for ($field = 1; $field <= odbc_num_fields($this->result); $field++) {
            $fieldNames[] = odbc_field_name($this->result, $field);
        }

        $row = [];
        foreach ($fieldNames as $field => $fieldName) {
            $row[$field] = odbc_result($this->result, $fieldName);
        }

        return $row;

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
    public function fetchOne(): array|false
    {
        $data = $this->fetchNumeric();
        return $data ? $this->fetchNumeric()[0] : $data;
    }

    /**
     * @inheritDoc
     */
    public function fetchAllNumeric(): array
    {
        $fieldNames = [];
        for ($field = 1; $field <= odbc_num_fields($this->result); $field++) {
            $fieldNames[] = odbc_field_name($this->result, $field);
        }

        $data = [];
        while (odbc_fetch_row($this->result)) {
            $row = [];
            foreach ($fieldNames as $field => $fieldName) {
                $row[$field] = odbc_result($this->result, $fieldName);
            }
            $data[] = $row;
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
        $fieldName = odbc_field_name($this->result, 1);

        $data = [];
        while (odbc_fetch_row($this->result)) {
            $data[] = odbc_result($this->result, $fieldName);
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