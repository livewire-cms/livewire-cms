<?php namespace Modules\System\Models;

use URL;
use Config;
use File as FileHelper;
use Storage;
use Modules\LivewireCore\Database\Attach\File as FileBase;
use Modules\Backend\Http\Controllers\Files;

/**
 * File attachment model
 *
 * @package winter\wn-system-module
 * @author Alexey Bobkov, Samuel Georges
 */
class File extends FileBase
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'system_files';

    /**
     * {@inheritDoc}
     */
    public function getThumb($width, $height, $options = [])
    {
        $URL = '';
        $width = !empty($width) ? $width : 0;
        $height = !empty($height) ? $height : 0;

        if (!$this->isPublic() && class_exists(Files::class)) {
            $options = $this->getDefaultThumbOptions($options);
            // Ensure that the thumb exists first
            parent::getThumb($width, $height, $options);

            // Return the Files controller handler for the URL
            $URL = Files::getThumbURL($this, $width, $height, $options);
        } else {
            $URL = parent::getThumb($width, $height, $options);
        }

        return $URL;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath($fileName = null)
    {
        $URL = '';
        if (!$this->isPublic() && class_exists(Files::class)) {
            $URL = Files::getDownloadURL($this);
        } else {
            $URL = parent::getPath($fileName);
        }

        return $URL;
    }

    /**
     * If working with local storage, determine the absolute local path.
     */
    protected function getLocalRootPath()
    {
        return Config::get('filesystems.disks.local.root', storage_path('app'));
    }

    /**
     * Define the public address for the storage path.
     */
    public function getPublicPath()
    {
        // $uploadsPath = Config::get('cms.storage.uploads.path', '/storage/app/uploads');
        $uploadsPath = Config::get('cms.storage.uploads.path', '/storage');

        if ($this->isPublic()) {
            // $uploadsPath .= '/public';
            $uploadsPath .= '/';
        }
        else {
            $uploadsPath .= '/protected';
        }

        return URL::asset($uploadsPath) . '/';
    }

    /**
     * Define the internal storage path.
     */
    public function getStorageDirectory()
    {
        $uploadsFolder = Config::get('cms.storage.uploads.folder');

        if ($this->isPublic()) {
            return $uploadsFolder . '/public/';
        }

        return $uploadsFolder . '/protected/';
    }

    /**
     * Returns the storage disk the file is stored on
     * @return FilesystemAdapter
     */
    public function getDisk()
    {
        return Storage::disk(Config::get('cms.storage.uploads.disk'));
    }
}
