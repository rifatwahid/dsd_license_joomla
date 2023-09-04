<?php 

use Joomla\Session\Session;

class FlashData
{
    protected $storage = null;
    public const STORAGE_NAME = 'smartshop.flashdata';

    public function __construct($storage)
    {
        $this->storage = $storage;
    }

    public function get(string $key)
    {
        $flashData = $this->storage->get(static::STORAGE_NAME);

        if (!empty($flashData) && array_key_exists($key, $flashData) ) {
            $this->deleteByKey($key);
            return $flashData[$key];
        }
    }

    /**
     * @return bool
     */
    public function set(string $key, $data): bool
    {
        $isSaved = true;

        try {
            $flashData = $this->storage->get(static::STORAGE_NAME);
            $flashData[$key] = $data;
            $this->storage->set(static::STORAGE_NAME, $flashData);
        } catch (\Exception $e) {
            $isSaved = false;
        }

        return $isSaved;
    }

    /**
     * @return bool
     */
    public function deleteByKey(string $key): bool
    {
        $isDeleted = true;

        try {
            $flashData = $this->storage->get(static::STORAGE_NAME);
            unset($flashData[$key]);
            $this->storage->set(static::STORAGE_NAME, $flashData);
        } catch (\Exception $e) {
            $isDeleted = false;
        }

        return $isDeleted;
    }
}