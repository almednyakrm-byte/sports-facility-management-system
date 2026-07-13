<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testاشتراكات_الاعضاء extends TestCase
{
    private $pdo;
    private $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
    }

    public function testGetAllاشتراكات_الاعضاء()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM اشتراكات_الاعضاء')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'اشتراك1'],
                ['id' => 2, 'name' => 'اشتراك2'],
            ]);

        $result = $this->pdo->query('SELECT * FROM اشتراكات_الاعضاء')->fetchAll();
        $this->assertCount(2, $result);
    }

    public function testGetاشتراكات_الاعضاءById()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM اشتراكات_الاعضاء WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'اشتراك1']);

        $stmt = $this->pdo->prepare('SELECT * FROM اشتراكات_الاعضاء WHERE id = :id');
        $stmt->bindParam(':id', 1);
        $stmt->execute();
        $result = $stmt->fetch();
        $this->assertEquals(1, $result['id']);
    }

    public function testCreateاشتراكات_الاعضاء()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO اشتراكات_الاعضاء (name) VALUES (:name)')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'اشتراك3');

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $stmt = $this->pdo->prepare('INSERT INTO اشتراكات_الاعضاء (name) VALUES (:name)');
        $stmt->bindParam(':name', 'اشتراك3');
        $stmt->execute();
        $result = $stmt->rowCount();
        $this->assertEquals(1, $result);
    }

    public function testUpdateاشتراكات_الاعضاء()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE اشتراكات_الاعضاء SET name = :name WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'اشتراك1_updated');

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $stmt = $this->pdo->prepare('UPDATE اشتراكات_الاعضاء SET name = :name WHERE id = :id');
        $stmt->bindParam(':id', 1);
        $stmt->bindParam(':name', 'اشتراك1_updated');
        $stmt->execute();
        $result = $stmt->rowCount();
        $this->assertEquals(1, $result);
    }

    public function testDeleteاشتراكات_الاعضاء()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM اشتراكات_الاعضاء WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $stmt = $this->pdo->prepare('DELETE FROM اشتراكات_الاعضاء WHERE id = :id');
        $stmt->bindParam(':id', 1);
        $stmt->execute();
        $result = $stmt->rowCount();
        $this->assertEquals(1, $result);
    }
}