<?php

namespace GPapakitsos\LaravelTraits\Tests\Feature;

use GPapakitsos\LaravelTraits\Tests\FeatureTestCase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ModelFileTest extends FeatureTestCase
{
    private function getRequestWithFile($fileSizeInMB = 1)
    {
        Storage::fake($this->user::FILE_FOLDER);

        $request = new Request([], [], [], [], [
            $this->user::FILE_INPUT_FIELD => UploadedFile::fake()->image('avatar.jpg')->size($fileSizeInMB * 1024),
        ]);
        $request->setMethod('POST');

        return $request;
    }

    public function test_store_file_upload()
    {
        $request = $this->getRequestWithFile();
        $this->user::storeFile($request);

        Storage::disk($this->user::getStorageDisk())->assertExists($request->{$this->user::FILE_MODEL_ATTRIBUTE});
    }

    public function test_store_file_path_into_request()
    {
        $request = $this->getRequestWithFile();
        $this->user::storeFile($request);

        $this->assertTrue($request->has($this->user::FILE_MODEL_ATTRIBUTE));
    }

    public function test_store_file_path_validate_size()
    {
        $this->expectException(ValidationException::class);

        $request = $this->getRequestWithFile(20);
        $this->user::storeFile($request);
    }

    public function test_delete_file()
    {
        $request = $this->getRequestWithFile();
        $this->user::storeFile($request);
        $this->user->{$this->user::FILE_MODEL_ATTRIBUTE} = $request->{$this->user::FILE_MODEL_ATTRIBUTE};
        $this->user->save();

        $this->user->deleteFile();

        Storage::disk($this->user::getStorageDisk())->assertMissing($request->{$this->user::FILE_MODEL_ATTRIBUTE});
    }

    public function test_change_file_upload()
    {
        $request = $this->getRequestWithFile();
        $request->request->add([
            'id' => $this->user->id,
        ]);
        $this->user::changeFile($request);

        Storage::disk($this->user::getStorageDisk())->assertExists($request->{$this->user::FILE_MODEL_ATTRIBUTE});
    }

    public function test_get_file_url_without_file()
    {
        $this->assertStringContainsString($this->user::FILE_DEFAULT_ASSET_URL, $this->user->getFileURL());
    }

    public function test_get_file_url_with_file()
    {
        $request = $this->getRequestWithFile();
        $this->user::storeFile($request);
        $this->user->{$this->user::FILE_MODEL_ATTRIBUTE} = $request->{$this->user::FILE_MODEL_ATTRIBUTE};
        $this->user->save();

        $this->assertStringContainsString($this->user->{$this->user::FILE_MODEL_ATTRIBUTE}, $this->user->getFileURL());
    }

    public function test_get_file_path_without_file()
    {
        $this->assertStringContainsString($this->user::FILE_DEFAULT_ASSET_URL, $this->user->getFilePath());
    }

    public function test_get_file_path_with_file()
    {
        $request = $this->getRequestWithFile();
        $this->user::storeFile($request);
        $this->user->{$this->user::FILE_MODEL_ATTRIBUTE} = $request->{$this->user::FILE_MODEL_ATTRIBUTE};
        $this->user->save();

        $this->assertStringContainsString($this->user->{$this->user::FILE_MODEL_ATTRIBUTE}, $this->user->getFilePath());
    }
}
