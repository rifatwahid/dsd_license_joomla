<?php 

defined('_JEXEC') or die('Restricted access');

class Repository
{
    protected $data = [];
    protected $encryptedData = [];

    public function get($key, $isEncrypted = false)
    {
        if ($isEncrypted) {
            $enctyptedKey = crc32(serialize($key));

            if (array_key_exists($enctyptedKey, $this->encryptedData)) {
                return $this->encryptedData[$enctyptedKey];
            }

            return;
        }

        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
    }

    public function set($key, $data, $isEncrypted = false): bool
    {
        if ($isEncrypted) {
            $enctyptedKey = crc32(serialize($key));
            $this->encryptedData[$enctyptedKey] = $data;

            return true;
        }

        $this->data[$key] = $data;
        return true;
    }

    public function delete($key, $isEncrypted = false): bool
    {
        if ($isEncrypted) {
            $enctyptedKey = crc32(serialize($key));

            if (array_key_exists($enctyptedKey, $this->encryptedData)) {
                unset($this->encryptedData[$enctyptedKey]);
                return true;
            }

            return false;
        }

        if (array_key_exists($key, $this->data)) {
            unset($this->data[$key]);
            return true;
        }

        return false;
    }

    public function getAllData()
    {
        return $this->data;
    }

    public function getAllEncryptedData()
    {
        return $this->encryptedData;
    }
}
