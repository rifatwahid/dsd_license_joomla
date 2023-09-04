<?php 

class VideoHostings
{
    protected $providers = [];
    protected $pathToProviders = [];

    public function __construct()
    {
        $this->includeProviders();
    }

    public function getListsOfProviders(): array
    {
        return $this->providers;
    }

    public function isProviderSupports(string $providerName): bool
    {
        $providerName = strtolower($providerName);
        return isset($this->providers[$providerName]);
    }

    public function getProviderInstanceByUrl(string $url): ?ProviderBase
    {
        $listOfProviders = $this->getListsOfProviders();

        if (!empty($listOfProviders)) {
            foreach ($listOfProviders as $providerInstance) {
                if ($providerInstance->isSupportUrl($url)) {
                    return $providerInstance;
                }
            }
        }

        return null;
    }

    protected function includeProviders()
    {
        $fillArrPathToProviders = function () {
            $pathToProviders = __DIR__ . '/providers/';
            $listOfFileProviders = scandir($pathToProviders);

            if (!empty($listOfFileProviders)) {
                $temp = [];

                foreach ($listOfFileProviders as $fileName) {
                    if (!empty($fileName) && strpos($fileName, 'provider.php') !== false) {
                        $fullPath = $pathToProviders . $fileName;

                        if (file_exists($fullPath) && !in_array($fullPath, $this->pathToProviders)) {
                            $temp[] = $fullPath;
                        }

                    }
                }

                $listOfFileProviders = $temp;
            }
            $this->pathToProviders = $listOfFileProviders;
        };

        $createProviders = function () {
            if (!empty($this->pathToProviders)) {
                foreach ($this->pathToProviders as $path) {
                    $fileName = pathinfo($path)['filename'];

                    include_once $path;
                    $object = new $fileName();
                    $hostingName = strtolower($object::HOSTING_NAME);

                    $this->providers[$hostingName] = $object;
                }
            }
        };

        $fillArrPathToProviders();
        $createProviders();
    }
}