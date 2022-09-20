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

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use PHPUnit\Framework\TestCase;
use TBCD\Doctrine\HFSQLDriver\Driver;

class DoctrineIntegrationTest extends TestCase
{

    use TestHelperTrait;

    /**
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::clearFiles();
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testDriverManager(): void
    {
        $connection = DriverManager::getConnection([
            'host' => '127.0.0.1',
            'user' => 'foo',
            'password' => 'bar',
            'port' => 4900,
            'dbName' => 'DBHF_CF',
            'driverClass' => Driver::class
        ]);

        $this->assertInstanceOf(Connection::class, $connection);
        $this->assertInstanceOf(Driver::class, $connection->getDriver());
    }

    /**
     * @return void
     */
    public function testBundle(): void
    {
        $kernel = new Kernel([
            'dbal' => [
                'host' => '127.0.0.1',
                'user' => 'foo',
                'password' => 'bar',
                'port' => 4900,
                'dbname' => 'DBHF_CF',
                'driver_class' => Driver::class
            ]
        ]);
        $kernel->boot();
        $connection = $kernel->getContainer()->get('doctrine.dbal.default_connection');

        $this->assertInstanceOf(Connection::class, $connection);
        $this->assertInstanceOf(Driver::class, $connection->getDriver());
    }
}