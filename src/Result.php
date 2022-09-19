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
use TypeError;

final class Result implements ResultInterface
{

    /**
     * @var resource
     */
    private mixed $result;

    /**
     * @var array|null
     */
    private ?array $fields = null;

    /**
     * @param resource $result
     *
     * @internal The result can be only instantiated by its driver connection or statement.
     */
    public function __construct(mixed $result)
    {
        if (!is_resource($result)) {
            throw new TypeError(sprintf('The result passed to %s must of type resource', self::class));
        }

        $this->result = $result;
    }


    /**
     * @inheritDoc
     */
    public function fetchNumeric(): array|false
    {
        if (null === $this->result) {
            return false;
        }

        if (!odbc_fetch_row($this->result)) {
            return false;
        }

        $row = [];
        foreach ($this->getFields() as $fieldId => $fieldData) {
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
        if (null === $this->result) {
            return false;
        }

        if (!odbc_fetch_row($this->result)) {
            return false;
        }

        $row = [];
        foreach ($this->getFields() as $fieldData) {
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
    public function fetchOne(): mixed
    {
        if (null === $this->result) {
            return false;
        }

        if (!odbc_fetch_row($this->result)) {
            return false;
        }

        $fields = $this->getFields();

        if (empty($fields)) {
            return false;
        }

        $fieldData = $fields[0];
        $fieldName = $fieldData['name'];
        $fieldType = $fieldData['type'];
        $fieldValue = odbc_result($this->result, $fieldName);
        return $this->convertType($fieldValue, $fieldType);
    }

    /**
     * @inheritDoc
     */
    public function fetchAllNumeric(): array
    {
        if (null === $this->result) {
            return [];
        }

        $data = [];
        while (odbc_fetch_row($this->result)) {
            $row = [];
            foreach ($this->getFields() as $fieldId => $fieldData) {
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
        if (null === $this->result) {
            return [];
        }

        $data = [];
        while (odbc_fetch_row($this->result)) {
            $row = [];
            foreach ($this->getFields() as $fieldData) {
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
        if (null === $this->result) {
            return [];
        }

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
        if (null === $this->result) {
            return 0;
        }

        return odbc_num_rows($this->result);
    }

    /**
     * @inheritDoc
     */
    public function columnCount(): int
    {
        if (null === $this->result) {
            return 0;
        }

        return odbc_num_fields($this->result);
    }

    /**
     * @inheritDoc
     */
    public function free(): void
    {
        odbc_free_result($this->result);
        $this->result = null;
    }

    /**
     * @param string $value
     * @param string $type
     * @return int|string
     */
    private function convertType(mixed $value, string $type): mixed
    {
        return match ($type) {
            'INTEGER', 'BIGINT', 'SMALLINT' => (int)$value,
            default => $value
        };
    }

    /**
     * @return array
     */
    private function getFields(): array
    {
        if (null === $this->fields) {
            $this->fields = [];
            for ($field = 1; $field <= odbc_num_fields($this->result); $field++) {
                $this->fields[] = ['name' => odbc_field_name($this->result, $field), 'type' => odbc_field_type($this->result, $field)];
            }
        }

        return $this->fields;
    }
}