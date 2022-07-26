<?php 

namespace Tests\Unit\Domain\Notification;

use Core\Domain\Notification\Notification;
use PHPUnit\Framework\TestCase;

class NotificationUnitTest extends TestCase 
{

    public function testGetErrors()
    {
        $notification = new Notification();
        $errors = $notification->getErrors();

        $this->assertIsArray($errors);
    }

    public function testAddErrors()
    {
        $notification = new Notification();
        $notification->addError([
            'context' => 'video',
            'message' => 'video title is required'
        ]);

        $errors = $notification->getErrors();

        $this->assertIsArray($errors);
        $this->assertCount(1, $errors);
    }

    public function testHasErrors()
    {
        $notification = new Notification();
        $hasErrors = $notification->hasErrors();
        $this->assertFalse($hasErrors);

        $notification->addError([
            'context' => 'video',
            'message' => 'video title is required'
        ]);

        $hasErrors = $notification->hasErrors();
        $this->assertTrue($hasErrors);
    }

    public function testMessage()
    {
        $notification = new Notification();
        $notification->addError([
            'context' => 'video',
            'message' => 'title is required'
        ]);

        $notification->addError([
            'context' => 'video',
            'message' => 'description is required'
        ]);

        $message = $notification->messages();
        $this->assertIsString($message);
        $this->assertEquals('video: title is required, video: description is required, ', $message);
    }

    public function testMessageFilterContext()
    {
        $notification = new Notification();
        $notification->addError([
            'context' => 'video',
            'message' => 'title is required'
        ]);

        $notification->addError([
            'context' => 'category',
            'message' => 'name is required'
        ]);

        $this->assertCount(2, $notification->getErrors());
        $message = $notification->messages(
            context: 'video'
        );

        $this->assertIsString($message);
        $this->assertEquals('video: title is required, ', $message);
    }
}