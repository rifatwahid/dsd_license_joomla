<?php 

include_once __DIR__ . '/providerbase.php';

class DailymotionProvider extends ProviderBase
{
    const HOSTING_NAME = 'dailymotion';

    protected $embedUrl = 'https://www.dailymotion.com/embed/video/';
    protected $urlAddresses = [
        'dai.ly',
        'dailymotion.com'
    ];

    public function getThumb(string $urlOrId): string
    {
        $result = '';

        if (!empty($urlOrId)) {
            $id = $this->getIdFromUrl($urlOrId);

            if (!empty($id)) {
                $temp = json_decode(file_get_contents("https://api.dailymotion.com/video/{$id}?fields=thumbnail_720_url"));
                $result = $temp->thumbnail_720_url ?: '';
            }
        }

        return $result;
    }

    public function getIdFromUrl(string $url): string
    {
        preg_match_all('/^.+dailymotion.com\/(?:video|swf\/video|embed\/video|hub|swf)\/([^&?]+)/',$url,$matches);
        
        return $matches['1']['0'] ?: '';
    }
}

