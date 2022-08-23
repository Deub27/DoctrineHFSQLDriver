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
        $fields = [];
        for ($field = 1; $field <= odbc_num_fields($this->result); $field++) {
            $fields[] = ['name' => odbc_field_name($this->result, $field), 'type' => odbc_field_type($this->result, $field)];
        }

        $row = [];
        foreach ($fields as $fieldId => $fieldData) {
            $fieldName = $fieldData['name'];
            $fieldType = $fieldData['type'];
            $fieldValue = odbc_result($this->result, $fieldName);
            $row[$fieldId] = $this->convertType($fieldValue, $fieldType);
        }

        return $row;

    }

    /**
     * @inheritDoc
     */
    public function fetchAssociative(): array|false
    {
        $fields = [];
        for ($field = 1; $field <= odbc_num_fields($this->result); $field++) {
            $fields[] = ['name' => odbc_field_name($this->result, $field), 'type' => odbc_field_type($this->result, $field)];
        }

        $row = [];
        foreach ($fields as $fieldData) {
            $fieldName = $fieldData['name'];
            $fieldType = $fieldData['type'];
            $fieldValue = odbc_result($this->result, $fieldName);
            $row[$fieldName] = $this->convertType($fieldValue, $fieldType);
        }

        return $row;
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
        $fields = [];
        for ($field = 1; $field <= odbc_num_fields($this->result); $field++) {
            $fields[] = ['name' => odbc_field_name($this->result, $field), 'type' => odbc_field_type($this->result, $field)];
        }

        $data = [];
        while (odbc_fetch_row($this->result)) {
            $row = [];
            foreach ($fields as $fieldId => $fieldData) {
                $fieldName = $fieldData['name'];
                $fieldType = $fieldData['type'];
                $fieldValue = odbc_result($this->result, $fieldName);
                $row[$fieldId] = $this->convertType($fieldValue, $fieldType);
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
        $fields = [];
        for ($field = 1; $field <= odbc_num_fields($this->result); $field++) {
            $fields[] = ['name' => odbc_field_name($this->result, $field), 'type' => odbc_field_type($this->result, $field)];
        }

        $data = [];
        while (odbc_fetch_row($this->result)) {
            $row = [];
            foreach ($fields as $fieldData) {
                $fieldName = $fieldData['name'];
                $fieldType = $fieldData['type'];
                $fieldValue = odbc_result($this->result, $fieldName);
                $row[$fieldName] = $this->convertType($fieldValue, $fieldType);
            }
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
        $fieldType = odbc_field_type($this->result, 1);

        $data = [];
        while (odbc_fetch_row($this->result)) {
            $data[] = $this->convertType(odbc_result($this->result, $fieldName), $fieldType);
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

    /**
     * @param string $value
     * @param string $type
     * @return int|string
     */
    private function convertType(string $value, string $type): int|string
    {
        return match ($type) {
            'INTEGER', 'BIGINT', 'SMALLINT' => (int)$value,
            default => $value
        };
    }
}