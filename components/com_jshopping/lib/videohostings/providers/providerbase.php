<?php 

abstract class ProviderBase
{
    protected $urlAddresses = [];
    protected $embedUrl = '';

    public abstract function getThumb(string $urlOrId);
    public abstract function getIdFromUrl(string $url);

    public function isSupportUrl(string $url): bool
    {
        $result = false;

        if (!empty($url) && !empty($this->urlAddresses)) {
            foreach ($this->urlAddresses as $address) {
                if (strpos($url, $address) !== false) {
                    $result = true;
                    break;
                }
            }
        }

        return $result;
    }

    public function getEmbedUrl(string $url): string
    {
        $embedUrl = '';
        $id = $this->getIdFromUrl($url);

        if (!empty($id)) {
            $embedUrl = $this->embedUrl . $id;
        }

        return $embedUrl;
    }
}