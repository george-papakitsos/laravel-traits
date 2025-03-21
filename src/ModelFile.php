<?php

namespace GPapakitsos\LaravelTraits;

use ErrorException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ModelFile
{
    /**
     * Checks if constant is defined
     *
     * @return void
     *
     * @throws ErrorException
     */
    private static function modelConstantsExists()
    {
        foreach (['FILE_INPUT_FIELD', 'FILE_MODEL_ATTRIBUTE', 'FILE_FOLDER', 'FILE_STORAGE_DISK', 'FILE_DEFAULT_ASSET_URL'] as $constant) {
            if (! defined(self::class.'::'.$constant)) {
                throw new ErrorException('Undefined constant '.self::class.'::'.$constant);
            }
        }
    }

    /**
     * Stores file if exists & adds the path of the uploaded file into request object
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws ErrorException|\Illuminate\Validation\ValidationException
     */
    public static function storeFile($request)
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
                self::FILE_MODEL_ATTRIBUTE => $file->store($path, self::FILE_STORAGE_DISK),
            ]);
        }
    }

    /**
     * Deletes model’s file by provided id
     *
     * @param  int  $id
     * @return void
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    private static function deleteFileByID($id)
    {
        $model = self::findOrFail($id);

        $model->deleteFile();
    }

    /**
     * Deletes model’s file if exists
     *
     * @return void
     */
    public function deleteFile()
    {
        if (! empty($this->{$this::FILE_MODEL_ATTRIBUTE}) && Storage::disk($this::FILE_STORAGE_DISK)->exists($this->{$this::FILE_MODEL_ATTRIBUTE})) {
            Storage::disk($this::FILE_STORAGE_DISK)->delete($this->{$this::FILE_MODEL_ATTRIBUTE});

            if (defined(self::class.'::FILE_USE_SUBFOLDER') && self::FILE_USE_SUBFOLDER === true) {
                $path = Str::beforeLast($this->{$this::FILE_MODEL_ATTRIBUTE}, '/');
                if (empty(Storage::disk($this::FILE_STORAGE_DISK)->allFiles($path))) {
                    Storage::disk($this::FILE_STORAGE_DISK)->deleteDirectory($path);
                }
            }
        }
    }

    /**
     * Removes the previous file if exists & stores the new one
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws ErrorException|\Illuminate\Validation\ValidationException
     */
    public static function changeFile($request)
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
     * Returns file’s URL
     *
     * @return string|null
     */
    public function getFileURL()
    {
        return ! empty($this->{$this::FILE_MODEL_ATTRIBUTE}) && Storage::disk($this::FILE_STORAGE_DISK)->exists($this->{$this::FILE_MODEL_ATTRIBUTE})
            ? Storage::disk($this::FILE_STORAGE_DISK)->url($this->{$this::FILE_MODEL_ATTRIBUTE})
            : ($this::FILE_DEFAULT_ASSET_URL === null ? null : Storage::disk($this::FILE_STORAGE_DISK)->url($this::FILE_DEFAULT_ASSET_URL));
    }

    /**
     * Returns file’s path
     *
     * @return string|null
     */
    public function getFilePath()
    {
        return ! empty($this->{$this::FILE_MODEL_ATTRIBUTE}) && Storage::disk($this::FILE_STORAGE_DISK)->exists($this->{$this::FILE_MODEL_ATTRIBUTE})
            ? Storage::disk($this::FILE_STORAGE_DISK)->path($this->{$this::FILE_MODEL_ATTRIBUTE})
            : ($this::FILE_DEFAULT_ASSET_URL === null ? null : Storage::disk($this::FILE_STORAGE_DISK)->path($this::FILE_DEFAULT_ASSET_URL));
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
