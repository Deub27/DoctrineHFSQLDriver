<?php

namespace TBCD\Doctrine\HfsqlDriver;

use Doctrine\DBAL\Driver\Result as ResultInterface;

class Result implements ResultInterface
{

    /**
     * @var array
     */
    private array $data;

    /**
     * @var int|null
     */
    private ?int $currentRow = null;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }


    /**
     * @inheritDoc
     */
    public function fetchNumeric(): array
    {
        $result = array_values($this->data[$this->currentRow]);
        $this->currentRow++;
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function fetchAssociative(): array
    {
        $result = $this->data[$this->currentRow];
        $this->currentRow++;
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function fetchOne(): array
    {
        return $this->data[0];
    }

    /**
     * @inheritDoc
     */
    public function fetchAllNumeric(): array
    {
        return array_values($this->data);
    }

    /**
     * @inheritDoc
     */
    public function fetchAllAssociative(): array
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function fetchFirstColumn(): array
    {
        return array_map(function (array $row) {
            return array_values($row)[0];
        }, $this->data);
    }

    /**
     * @inheritDoc
     */
    public function rowCount(): int
    {
        return count($this->data);
    }

    /**
     * @inheritDoc
     */
    public function columnCount(): int
    {
        return count(array_keys($this->data[0]));
    }

    /**
     * @inheritDoc
     */
    public function free(): void
    {
        $this->currentRow = 0;
    }
}