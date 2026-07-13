<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testإدارةالصالة extends TestCase
{
    private MockObject $pdo;
    private MockObject $statement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->statement = $this->createMock(PDOStatement::class);
    }

    public function testGetإدارةالصالة(): void
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM إدارة_الصالة')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->statement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'صالة 1'],
                ['id' => 2, 'name' => 'صالة 2'],
            ]);

        $result = $this->getإدارةالصالة($this->pdo);
        $this->assertCount(2, $result);
    }

    public function testPostإدارةالصالة(): void
    {
        $data = ['name' => 'صالة جديدة'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO إدارة_الصالة (name) VALUES (:name)')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->statement->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $result = $this->postإدارةالصالة($this->pdo, $data);
        $this->assertTrue($result);
    }

    public function testPutإدارةالصالة(): void
    {
        $id = 1;
        $data = ['name' => 'صالة محدثة'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE إدارة_الصالة SET name = :name WHERE id = :id')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->statement->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $result = $this->putإدارةالصالة($this->pdo, $id, $data);
        $this->assertTrue($result);
    }

    public function testDeleteإدارةالصالة(): void
    {
        $id = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM إدارة_الصالة WHERE id = :id')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->statement->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $result = $this->deleteإدارةالصالة($this->pdo, $id);
        $this->assertTrue($result);
    }

    private function getإدارةالصالة(PDO $pdo): array
    {
        $statement = $pdo->prepare('SELECT * FROM إدارة_الصالة');
        $statement->execute();
        return $statement->fetchAll();
    }

    private function postإدارةالصالة(PDO $pdo, array $data): bool
    {
        $statement = $pdo->prepare('INSERT INTO إدارة_الصالة (name) VALUES (:name)');
        $statement->bindParam(':name', $data['name']);
        $statement->execute();
        return $statement->rowCount() > 0;
    }

    private function putإدارةالصالة(PDO $pdo, int $id, array $data): bool
    {
        $statement = $pdo->prepare('UPDATE إدارة_الصالة SET name = :name WHERE id = :id');
        $statement->bindParam(':name', $data['name']);
        $statement->bindParam(':id', $id);
        $statement->execute();
        return $statement->rowCount() > 0;
    }

    private function deleteإدارةالصالة(PDO $pdo, int $id): bool
    {
        $statement = $pdo->prepare('DELETE FROM إدارة_الصالة WHERE id = :id');
        $statement->bindParam(':id', $id);
        $statement->execute();
        return $statement->rowCount() > 0;
    }
}