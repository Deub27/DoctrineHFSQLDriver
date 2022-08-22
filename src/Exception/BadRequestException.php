<?php

namespace TBCD\Doctrine\HFSQLDriver\Exception;

use Doctrine\DBAL\Driver\Exception as DriverExceptionInterface;
use Doctrine\DBAL\Exception\DriverException;

class BadRequestException extends DriverException implements DriverExceptionInterface
{

}