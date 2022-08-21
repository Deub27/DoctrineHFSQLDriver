<?php

namespace TBCD\Doctrine\HFSQLDriver\Platform;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Exception;

class HFSQLPlatform extends AbstractPlatform
{

    /**
     * @inheritDoc
     */
    public function getBooleanTypeDeclarationSQL(array $column): string
    {
        return 'TINYINT(1)';
    }

    /**
     * @inheritDoc
     */
    public function getIntegerTypeDeclarationSQL(array $column): string
    {
        return 'INT';
    }

    /**
     * @inheritDoc
     */
    public function getBigIntTypeDeclarationSQL(array $column): string
    {
        return 'BIGINT';
    }

    /**
     * @inheritDoc
     */
    public function getSmallIntTypeDeclarationSQL(array $column): string
    {
        return 'SMALLINT';
    }

    /**
     * @inheritDoc
     */
    protected function _getCommonIntegerTypeDeclarationSQL(array $column): string
    {
        return empty($column['autoincrement']) ? '' : ' AUTO_INCREMENT';
    }

    /**
     * @inheritDoc
     */
    protected function initializeDoctrineTypeMappings()
    {
        $this->doctrineTypeMapping = [
            'bigint' => 'bigint',
            'binary' => 'binary',
            'blob' => 'blob',
            'char' => 'string',
            'date' => 'date',
            'datetime' => 'datetime',
            'decimal' => 'decimal',
            'double' => 'float',
            'float' => 'float',
            'int' => 'integer',
            'integer' => 'integer',
            'numeric' => 'decimal',
            'real' => 'float',
            'set' => 'simple_array',
            'smallint' => 'smallint',
            'string' => 'string',
            'text' => 'text',
            'time' => 'time',
            'tinyint' => 'boolean',
            'varbinary' => 'binary',
            'varchar' => 'string',
            'year' => 'date',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getClobTypeDeclarationSQL(array $column): string
    {
        return 'TEXT';
    }

    /**
     * @inheritDoc
     */
    public function getBlobTypeDeclarationSQL(array $column): string
    {
        return 'CLOB';
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'hfsql';
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function getCurrentDatabaseExpression(): string
    {
        throw new Exception('HFSQL doesnt have expression to get the current database');
    }
}