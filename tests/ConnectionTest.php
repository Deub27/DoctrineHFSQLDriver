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

use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Driver\Statement;
use PHPUnit\Framework\TestCase;
use TBCD\Doctrine\HFSQLDriver\Driver;

class ConnectionTest extends TestCase
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
     */
    public function testCreation(): void
    {

    }

    /**
     * @throws Exception
     */
    public function testExec(): void
    {
        $driver = new Driver();
        $connection = $driver->connect(['host' => '127.0.0.1', 'user' => 'foo', 'password' => 'bar', 'port' => 4900, 'dbname' => 'DBHF_CF']);
        $tableName = $this->generateTableName();
        $resultCreate = $connection->exec("CREATE TABLE $tableName (id INTEGER, code VARCHAR(255), name VARCHAR(255))");
        $this->assertEquals(0, $resultCreate);
        $resultInsert = $connection->exec("INSERT INTO $tableName (id, code, name) VALUES (1, 'code', 'name')");
        $this->assertEquals(1, $resultInsert);
        $resultInsert2 = $connection->exec("INSERT INTO $tableName (id, code, name) VALUES (2, 'code', 'name'), (3, 'code', 'name'), (4, 'code', 'name')");
        $this->assertEquals(3, $resultInsert2);
        $resultUpdate = $connection->exec("UPDATE $tableName SET name = 'new' WHERE name = 'name'");
        $this->assertEquals(4, $resultUpdate);
        $resultUpdate = $connection->exec("DELETE FROM $tableName WHERE id = 1 OR id = 2");
        $this->assertEquals(2, $resultUpdate);
        $resultDrop = $connection->exec("DROP TABLE $tableName");
        $this->assertEquals(2, $resultDrop);
    }

    /**
     * @throws Exception
     */
    public function testQuery(): void
    {
        $driver = new Driver();
        $connection = $driver->connect(['host' => '127.0.0.1', 'user' => 'foo', 'password' => 'bar', 'port' => 4900, 'dbname' => 'DBHF_CF']);
        $tableName = $this->generateTableName();
        $connection->exec("CREATE TABLE $tableName (id INTEGER, code VARCHAR(255), name VARCHAR(255))");
        $result = $connection->query("SELECT * FROM $tableName");
        $this->assertInstanceOf(Result::class, $result);
        $connection->exec("DROP TABLE $tableName");
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testQuote(): void
    {
        $driver = new Driver();
        $connection = $driver->connect(['host' => '127.0.0.1', 'user' => 'foo', 'password' => 'bar', 'port' => 4900, 'dbname' => 'DBHF_CF']);
        $string = 'test';
        $quotedString = $connection->quote($string);
        $this->assertEquals("'$string'", $quotedString);
        $string = 'test\'test';
        $quotedString = $connection->quote($string);
        $this->assertEquals("'$string'", $quotedString);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testPrepare(): void
    {
        $driver = new Driver();
        $connection = $driver->connect(['host' => '127.0.0.1', 'user' => 'foo', 'password' => 'bar', 'port' => 4900, 'dbname' => 'DBHF_CF']);
        $tableName = $this->generateTableName();
        $connection->exec("CREATE TABLE $tableName (id INTEGER, code VARCHAR(255), name VARCHAR(255))");
        $statement = $connection->prepare("INSERT INTO $tableName (id, code, name) VALUES (?, ?, ?)");
        $this->assertInstanceOf(Statement::class, $statement);
        $connection->exec("DROP TABLE $tableName");
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testLastInsertId(): void
    {
        $driver = new Driver();
        $connection = $driver->connect(['host' => '127.0.0.1', 'user' => 'foo', 'password' => 'bar', 'port' => 4900, 'dbname' => 'DBHF_CF']);
        $tableName = $this->generateTableName();
        $connection->exec("CREATE TABLE $tableName (id INTEGER AUTO_INCREMENT, code VARCHAR(255), name VARCHAR(255))");
        $connection->exec("INSERT INTO $tableName (code, name) VALUES ('code', 'name')");
        $lastInsertId = $connection->lastInsertId($tableName);
        $this->assertEquals(1, $lastInsertId);
        $connection->exec("DROP TABLE $tableName");
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testTransaction(): void
    {
        $driver = new Driver();
        $connection = $driver->connect(['host' => '127.0.0.1', 'user' => 'foo', 'password' => 'bar', 'port' => 4900, 'dbname' => 'DBHF_CF']);
        $tableName = $this->generateTableName();
        $connection->exec("CREATE TABLE $tableName (id INTEGER, code VARCHAR(255), name VARCHAR(255))");
        $this->assertTrue($connection->beginTransaction());
        $connection->exec("INSERT INTO $tableName (id, code, name) VALUES (1, 'code', 'name')");
        $this->assertTrue($connection->rollBack());
        $this->assertTrue($connection->beginTransaction());
        $connection->exec("INSERT INTO $tableName (id, code, name) VALUES (1, 'code', 'name')");
        $this->assertTrue($connection->commit());
        $connection->exec("DROP TABLE $tableName");
    }
}