<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Category;
use Core\Domain\Exception\EntityValidationException;
use PHPUnit\Framework\TestCase;
use Throwable;

class CategoryUnitTest extends TestCase
{

    public function testAttributes()
    {
        $category = new Category(
            id: '123',
            name: 'New Category',
            description: 'New Description',
            isActive: true
        );

        $this->assertEquals('New Category', $category->name);
        $this->assertEquals('New Description', $category->description);
        $this->assertEquals(true, $category->isActive);
    }

    public function  testActivated()
    {
        $category = new Category(
            name: 'Category activated',
            isActive: false
        );

        $this->assertFalse($category->isActive);

        $category->activate();
        $this->assertTrue($category->isActive);
    }

    public function  testDisable()
    {
        $category = new Category(
            name: 'Category activated',
        );

        $this->assertTrue($category->isActive);

        $category->disable();
        $this->assertFalse($category->isActive);
    }

    public function testUpdate()
    {
        $uuid = '123';

        $category = new Category(
            id: $uuid,
            name: 'New Category',
            description: 'New Description',
            isActive: true
        );

        $category->update(
            name: 'New name',
            description: 'New description'
        );

        $this->assertEquals('New name', $category->name);
        $this->assertEquals('New description', $category->description);
    }

    public function testExceptionName()
    {
        try {
            new Category(
                name: 'Na',
                description: 'New Desc'
            );

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
        }
    }

    public function testExceptionDescription()
    {
        try {
            new Category(
                name: 'Name Cat',
                description: random_bytes(999999)
            );

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
        }
    }
}