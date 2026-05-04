<?php

namespace GPapakitsos\LaravelTraits;

use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait ModelFile
{
    /**
     * Checks if constants are defined
     *
     * @throws ErrorException
     */
    private static function modelConstantsExists(): void
    {
        foreach (['FILE_INPUT_FIELD', 'FILE_MODEL_ATTRIBUTE', 'FILE_FOLDER', 'FILE_DEFAULT_ASSET_URL'] as $constant) {
            if (! defined(self::class.'::'.$constant)) {
                throw new ErrorException('Undefined constant '.self::class.'::'.$constant);
            }
        }
    }

    /**
     * Returns the storage disk
     */
    public static function getStorageDisk(): string
    {
        return defined(self::class.'::FILE_STORAGE_DISK') ? self::FILE_STORAGE_DISK : config('laraveltraits.ModelFile.default_storage_disk');
    }

    /**
     * Stores file if exists & adds the path of the uploaded file into request object
     *
     * @throws ErrorException|ValidationException
     */
    public static function storeFile(Request $request): void
    {
        self::modelConstantsExists();

        $file = $request->file(self::FILE_INPUT_FIELD);

        if (! empty($file)) {
            $request->validate([
                self::FILE_INPUT_FIELD => 'max:'.(self::convertToBytes(ini_get('upload_max_filesize')) / 1024),
            ]);

            $path = self::FILE_FOLDER;
            if (defined(self::class.'::FILE_USE_SUBFOLDER') && self::FILE_USE_SUBFOLDER === true) {
                $subfolder = substr(sha1($file->hashName()), 0, 2);
                $path .= '/'.$subfolder;
            }

            $request->request->add([
                self::FILE_MODEL_ATTRIBUTE => $file->store($path, self::getStorageDisk()),
            ]);
        }
    }

    /**
     * Deletes model’s file by provided id
     *
     * @throws ModelNotFoundException
     */
    private static function deleteFileByID(int|string $id): void
    {
        $model = self::findOrFail($id);

        $model->deleteFile();
    }

    /**
     * Deletes model’s file if exists
     */
    public function deleteFile(): void
    {
        if (! $this->fileExists()) {
            return;
        }

        $storageDisk = $this::getStorageDisk();
        $path = $this->{$this::FILE_MODEL_ATTRIBUTE};
        Storage::disk($storageDisk)->delete($path);

        if (defined(self::class.'::FILE_USE_SUBFOLDER') && self::FILE_USE_SUBFOLDER === true) {
            $path = Str::beforeLast($path, '/');
            if (empty(Storage::disk($storageDisk)->allFiles($path))) {
                Storage::disk($storageDisk)->deleteDirectory($path);
            }
        }
    }

    /**
     * Removes the previous file if exists & stores the new one
     *
     * @throws ErrorException|ValidationException
     */
    public static function changeFile(Request $request): void
    {
        self::modelConstantsExists();

        $file = $request->file(self::FILE_INPUT_FIELD);

        if (! empty($file)) {
            $request->validate([
                'id' => 'required',
                self::FILE_INPUT_FIELD => 'max:'.(self::convertToBytes(ini_get('upload_max_filesize')) / 1024),
            ]);

            self::deleteFileByID($request->id);
            self::storeFile($request);
        }
    }

    /**
     * Checks if file exists
     */
    public function fileExists(): bool
    {
        $path = $this->{$this::FILE_MODEL_ATTRIBUTE};

        return ! empty($path) && Storage::disk($this::getStorageDisk())->exists($path);
    }

    /**
     * Returns file’s URL
     */
    public function getFileURL(): ?string
    {
        $storageDisk = $this::getStorageDisk();

        return $this->fileExists()
            ? Storage::disk($storageDisk)->url($this->{$this::FILE_MODEL_ATTRIBUTE})
            : ($this::FILE_DEFAULT_ASSET_URL === null ? null : Storage::disk($storageDisk)->url($this::FILE_DEFAULT_ASSET_URL));
    }

    /**
     * Returns file’s path
     */
    public function getFilePath(): ?string
    {
        $storageDisk = $this::getStorageDisk();

        return $this->fileExists()
            ? Storage::disk($storageDisk)->path($this->{$this::FILE_MODEL_ATTRIBUTE})
            : ($this::FILE_DEFAULT_ASSET_URL === null ? null : Storage::disk($storageDisk)->path($this::FILE_DEFAULT_ASSET_URL));
    }

    /**
     * Converts provided string into bytes
     *
     * @param  string  $val
     * @return int
     */
    private static function convertToBytes($val)
    {
        $val = trim($val);
        if (is_numeric($val)) {
            return $val;
        }

        $last = strtolower($val[strlen($val) - 1]);
        $val = substr($val, 0, -1);

        switch ($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }

        return $val;
    }
}
