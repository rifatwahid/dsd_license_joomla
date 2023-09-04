<?php 

include_once __DIR__ . '/providerbase.php';

class VimeoProvider extends ProviderBase
{
    const HOSTING_NAME = 'vimeo';

    protected $embedUrl = 'https://player.vimeo.com/video/';
    protected $urlAddresses = [
        'vimeo.com',
        'player.vimeo.com'
    ];

    public function getThumb(string $urlOrId): string
    {
        $result = '';
        $id = $this->getIdFromUrl($urlOrId);

        if (!empty($id)) {
            $result = (json_decode(file_get_contents("http://vimeo.com/api/v2/video/{$id}.json"))['0']->thumbnail_large) ?: '';
        }

        return $result;
    }

    public function getIdFromUrl(string $url): string
    {
        $regs = [];
        preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $url, $regs);
    
        return $regs['3'] ?: '';
    }
}