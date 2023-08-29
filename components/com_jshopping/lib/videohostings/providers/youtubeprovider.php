<?php 

include_once __DIR__ . '/providerbase.php';

class YoutubeProvider extends ProviderBase
{
    const HOSTING_NAME = 'youtube';
    protected $key = 'AIzaSyD9UdwvZobrsaIO-OH_-UJVv7ihB80ZHEs';
    
    protected $embedUrl = 'https://www.youtube.com/embed/';
    protected $urlAddresses = [
        'youtube.com',
        'youtu.be'
    ];

    public function getThumb(string $urlOrId): string
    {
        $result = '';

        if (!empty($urlOrId)) {
            $isUrl = (strpos($urlOrId, 'http') !== false || strpos($urlOrId, '://') !== false);
            $id = $isUrl ? $this->getIdFromUrl($urlOrId) : $urlOrId;

            if (!empty($id)) {
                $urlToVideoData = 'https://www.googleapis.com/youtube/v3/videos?key=' . $this->key . '&part=snippet&id=' . $id;
                $videoData = file_get_contents($urlToVideoData);
                
                if (!empty($videoData)) {
                    $decodedVideoData = json_decode($videoData);

                    if (!empty($decodedVideoData->items['0']->snippet->thumbnails->default)) {
                        $thumbnails = (array)$decodedVideoData->items['0']->snippet->thumbnails;
                        $lastThumbnail = end($thumbnails);

                        if (!empty($lastThumbnail) && !empty($lastThumbnail->url)) {
                            $result = $lastThumbnail->url;
                        }
                    }
                }
            }
        }

        return $result;
    }

    public function getIdFromUrl(string $url): string
    {
        $pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
        preg_match($pattern, $url, $matches);

        return $matches['1'] ?: '';
    }
}