<?php

/*
 * The file is part of the WoWUltimate project 
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Author Thomas Beauchataud
 * From 19/09/2022
 */

namespace TBCD\Doctrine\HFSQLDriver\Tests;

use Doctrine\DBAL\Driver\Exception;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use TBCD\Doctrine\HFSQLDriver\Driver;
use TBCD\Doctrine\HFSQLDriver\Result;
use TypeError;

class ResultTest extends TestCase
{

    /**
     * @return void
     */
    public function testCreation(): void
    {
        try {
            new Result(false);
            $this->expectError();
        } catch (TypeError) {
            $this->addToAssertionCount(1);
        }
    }

    /**
     * @throws Exception
     */
    public function testFetchNumeric(): void
    {
        $driver = new Driver();
        $connection = $driver->connect(['host' => '127.0.0.1', 'user' => 'foo', 'password' => 'bar', 'port' => 4900, 'dbname' => 'DBHF_CF']);
        $tableName = Factory::create()->word();
        $connection->exec("CREATE TABLE $tableName (id INTEGER, code VARCHAR(255), name VARCHAR(255))");
        $connection->exec("INSERT INTO $tableName (id, code, name) VALUES (1, 'code_data', 'name_data'), (2, 'code_data', 'name_data')");
        $result = $connection->query("SELECT * FROM $tableName");
        $row = $result->fetchNumeric();
        $this->assertArrayHasKey(0, $row);
        $this->assertArrayHasKey(1, $row);
        $this->assertArrayHasKey(2, $row);
        $this->assertEquals(1, $row[0]);
        $this->assertEquals('code_data', $row[1]);
        $this->assertEquals('name_data', $row[2]);
        $row = $result->fetchNumeric();
        $this->assertArrayHasKey(0, $row);
        $this->assertArrayHasKey(1, $row);
        $this->assertArrayHasKey(2, $row);
        $this->assertEquals(2, $row[0]);
        $this->assertEquals('code_data', $row[1]);
        $this->assertEquals('name_data', $row[2]);
        $row = $result->fetchNumeric();
        $this->assertFalse($row);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testFetchAssociative(): void
    {
        $driver = new Driver();
        $connection = $driver->connect(['host' => '127.0.0.1', 'user' => 'foo', 'password' => 'bar', 'port' => 4900, 'dbname' => 'DBHF_CF']);
        $tableName = Factory::create()->word();
        $connection->exec("CREATE TABLE $tableName (id INTEGER, code VARCHAR(255), name VARCHAR(255))");
        $connection->exec("INSERT INTO $tableName (id, code, name) VALUES (1, 'code_data', 'name_data'), (2, 'code_data', 'name_data')");
        $result = $connection->query("SELECT * FROM $tableName");
        $row = $result->fetchAssociative();
        $this->assertArrayHasKey('id', $row);
        $this->assertArrayHasKey('code', $row);
        $this->assertArrayHasKey('name', $row);
        $this->assertEquals(1, $row['id']);
        $this->assertEquals('code_data', $row['code']);
        $this->assertEquals('name_data', $row['name']);
        $row = $result->fetchAssociative();
        $this->assertArrayHasKey('id', $row);
        $this->assertArrayHasKey('code', $row);
        $this->assertArrayHasKey('name', $row);
        $this->assertEquals(2, $row['id']);
        $this->assertEquals('code_data', $row['code']);
        $this->assertEquals('name_data', $row['name']);
        $row = $result->fetchAssociative();
        $this->assertFalse($row);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testFetchAllNumeric(): void
    {
        $driver = new Driver();
        $connection = $driver->connect(['host' => '127.0.0.1', 'user' => 'foo', 'password' => 'bar', 'port' => 4900, 'dbname' => 'DBHF_CF']);
        $tableName = Factory::create()->word();
        $connection->exec("CREATE TABLE $tableName (id INTEGER, code VARCHAR(255), name VARCHAR(255))");
        $connection->exec("INSERT INTO $tableName (id, code, name) VALUES (1, 'code', 'name'), (2, 'code', 'name')");
        $result = $connection->query("SELECT * FROM $tableName");
        $data = $result->fetchAllNumeric();
        $this->assertCount(2, $data);
        foreach ($data as $row) {
            $this->assertArrayHasKey(0, $row);
            $this->assertArrayHasKey(1, $row);
            $this->assertArrayHasKey(2, $row);
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testFetchAllAssociative(): void
    {
        $driver = new Driver();
        $connection = $driver->connect(['host' => '127.0.0.1', 'user' => 'foo', 'password' => 'bar', 'port' => 4900, 'dbname' => 'DBHF_CF']);
        $tableName = Factory::create()->word();
        $connection->exec("CREATE TABLE $tableName (id INTEGER, code VARCHAR(255), name VARCHAR(255))");
        $connection->exec("INSERT INTO $tableName (id, code, name) VALUES (1, 'code', 'name'), (2, 'code', 'name')");
        $result = $connection->query("SELECT * FROM $tableName");
        $data = $result->fetchAllAssociative();
        $this->assertCount(2, $data);
        foreach ($data as $row) {
            $this->assertArrayHasKey('id', $row);
            $this->assertArrayHasKey('code', $row);
            $this->assertArrayHasKey('name', $row);
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testFetchOne(): void
    {
        $driver = new Driver();
        $connection = $driver->connect(['host' => '127.0.0.1', 'user' => 'foo', 'password' => 'bar', 'port' => 4900, 'dbname' => 'DBHF_CF']);
        $tableName = Factory::create()->word();
        $connection->exec("CREATE TABLE $tableName (id INTEGER, code VARCHAR(255), name VARCHAR(255))");
        $connection->exec("INSERT INTO $tableName (id, code, name) VALUES (1, 'code_data', 'name_data'), (2, 'code_data', 'name_data')");
        $result = $connection->query("SELECT code FROM $tableName WHERE id = 1");
        $value = $result->fetchOne();
        $this->assertEquals('code_data', $value);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testFetchFirstColumn(): void
    {
        $driver = new Driver();
        $connection = $driver->connect(['host' => '127.0.0.1', 'user' => 'foo', 'password' => 'bar', 'port' => 4900, 'dbname' => 'DBHF_CF']);
        $tableName = Factory::create()->word();
        $connection->exec("CREATE TABLE $tableName (id INTEGER, code VARCHAR(255), name VARCHAR(255))");
        $connection->exec("INSERT INTO $tableName (id, code, name) VALUES (1, 'code_data_1', 'name_data'), (2, 'code_data_2', 'name_data')");
        $result = $connection->query("SELECT code FROM $tableName");
        $data = $result->fetchFirstColumn();
        $this->assertContains('code_data_1', $data);
        $this->assertContains('code_data_2', $data);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testFree(): void
    {
        $driver = new Driver();
        $connection = $driver->connect(['host' => '127.0.0.1', 'user' => 'foo', 'password' => 'bar', 'port' => 4900, 'dbname' => 'DBHF_CF']);
        $tableName = Factory::create()->word();
        $connection->exec("CREATE TABLE $tableName (id INTEGER, code VARCHAR(255), name VARCHAR(255))");
        $connection->exec("INSERT INTO $tableName (id, code, name) VALUES (1, 'code_data', 'name_data'), (2, 'code_data', 'name_data')");
        $result = $connection->query("SELECT code FROM $tableName WHERE id = 1");
        $result->free();
        $this->assertFalse($result->fetchAssociative());
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testColumnCount(): void
    {
        $driver = new Driver();
        $connection = $driver->connect(['host' => '127.0.0.1', 'user' => 'foo', 'password' => 'bar', 'port' => 4900, 'dbname' => 'DBHF_CF']);
        $tableName = Factory::create()->word();
        $connection->exec("CREATE TABLE $tableName (id INTEGER, code VARCHAR(255), name VARCHAR(255))");
        $connection->exec("INSERT INTO $tableName (id, code, name) VALUES (1, 'code_data', 'name_data'), (2, 'code_data', 'name_data')");
        $result = $connection->query("SELECT * FROM $tableName");
        $count = $result->columnCount();
        $this->assertEquals(3, $count);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testRowCount(): void
    {
        $driver = new Driver();
        $connection = $driver->connect(['host' => '127.0.0.1', 'user' => 'foo', 'password' => 'bar', 'port' => 4900, 'dbname' => 'DBHF_CF']);
        $tableName = Factory::create()->word();
        $connection->exec("CREATE TABLE $tableName (id INTEGER, code VARCHAR(255), name VARCHAR(255))");
        $connection->exec("INSERT INTO $tableName (id, code, name) VALUES (1, 'code_data', 'name_data'), (2, 'code_data', 'name_data')");
        $result = $connection->query("SELECT * FROM $tableName");
        $count = $result->rowCount();
        $this->assertEquals(2, $count);
    }
}