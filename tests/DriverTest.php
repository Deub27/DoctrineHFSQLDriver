<?php

/*
 * This file is part of the tbcd/doctrine-hfsql-driver package.
 *
 * (c) Thomas Beauchataud <thomas.beauchataud@yahoo.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TBCD\Doctrine\HFSQLDriver\Tests;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use PHPUnit\Framework\TestCase;
use TBCD\Doctrine\HFSQLDriver\Driver;

class DriverTest extends TestCase
{

    /**
     * @return void
     * @throws Exception
     */
    public function testDriverManager(): void
    {
        DriverManager::getConnection([
            'host' => '127.0.0.1',
            'user' => 'foo',
            'password' => 'bar',
            'port' => 4900,
            'dbName' => 'DBHF_CF',
            'driverClass' => Driver::class
        ]);

        $this->addToAssertionCount(1);
    }
}