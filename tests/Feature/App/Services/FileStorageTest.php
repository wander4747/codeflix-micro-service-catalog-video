<?php

namespace Tests\Feature\App\Services;

use App\Services\Storage\FileStorage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileStorageTest extends TestCase
{
    
    public function testStore()
    {
        $fakeFile = UploadedFile::fake()->create('video.mp4', 1, 'video/mp4');
        
        $file = [
            'tmp_name' => $fakeFile->getPathname(),
            'name' => $fakeFile->getFilename(),
            'type' => $fakeFile->getMimeType(),
            'error' => $fakeFile->getError(),
        ];

        $filePath = (new FileStorage())
                        ->store('videos', $file);

        Storage::assertExists($filePath);

        Storage::delete($filePath);
    }

    public function testDelete()
    {
        $file = UploadedFile::fake()->create('video.mp4', 1, 'video/mp4');

        $path = $file->store('videos');

        (new FileStorage())
            ->delete($path);

        Storage::assertMissing($path);
    }
}
