<?php

namespace TBCD\Doctrine\HFSQLDriver;

use Doctrine\DBAL\Platforms\AbstractPlatform;

class HFSQLPlatform extends AbstractPlatform
{

    public function getBooleanTypeDeclarationSQL(array $column)
    {
        // TODO: Implement getBooleanTypeDeclarationSQL() method.
    }

    public function getIntegerTypeDeclarationSQL(array $column): string
    {
        return 'INT';
    }

    public function getBigIntTypeDeclarationSQL(array $column)
    {
        // TODO: Implement getBigIntTypeDeclarationSQL() method.
    }

    public function getSmallIntTypeDeclarationSQL(array $column)
    {
        // TODO: Implement getSmallIntTypeDeclarationSQL() method.
    }

    protected function _getCommonIntegerTypeDeclarationSQL(array $column)
    {
        // TODO: Implement _getCommonIntegerTypeDeclarationSQL() method.
    }

    protected function initializeDoctrineTypeMappings()
    {
        // TODO: Implement initializeDoctrineTypeMappings() method.
    }

    public function getClobTypeDeclarationSQL(array $column)
    {
        // TODO: Implement getClobTypeDeclarationSQL() method.
    }

    public function getBlobTypeDeclarationSQL(array $column)
    {
        // TODO: Implement getBlobTypeDeclarationSQL() method.
    }

    public function getName()
    {
        // TODO: Implement getName() method.
    }

    public function getCurrentDatabaseExpression(): string
    {
        // TODO: Implement getCurrentDatabaseExpression() method.
        return '';
    }
}