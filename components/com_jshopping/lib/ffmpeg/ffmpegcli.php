<?php 

use Joomla\CMS\Filter\OutputFilter;

class FfmpegCli 
{
    protected $cadrPosition = '00:00:02';
    protected $numbOfFrames = 1;
    protected $imageQuality = 3;

    public function generateImgFromVideoOrImg(string $source, $params): array
    {
		$savedThumbInfo = [];

        if (!empty($source)) {
            $sourceInfo = getInfoAboutSource($source);
            $abstrNameOfLink = $sourceInfo['abstrName'] ?: '';
            $formatOfLink = $sourceInfo['format'] ?: '';
            $sourceInfo['name'] = OutputFilter::cleanText(str_replace('.', '', $sourceInfo['name']));

			$outputName = ($params['prefix'] ?: '') . ($sourceInfo['name'] ?: '') . '_' . time() . '.jpg';
			$isImage = ($abstrNameOfLink == 'image' && !empty($params['pathToImageFolder']));
			$isVideo = ($abstrNameOfLink == 'video' && !empty($params['pathToVideoFolder']));

            if ($isImage) {
				$outputPath = "{$params['pathToImageFolder']}/$outputName";
				$tempThumbUrl = $this->generateFromImgToImg($source, $outputPath);
            } elseif ($isVideo) {
                $outputPath = "{$params['pathToVideoFolder']}/$outputName";

                if ($formatOfLink == 'videohosting') {
					$instanceOfVideoHostingProvider = JSFactory::getVideoHostings()->getProviderInstanceByUrl($source);
					$imgFromProvider = $instanceOfVideoHostingProvider->getThumb($source) ?: '';

					if (!empty($imgFromProvider)) {
						$tempThumbUrl = $this->generateFromImgToImg($imgFromProvider, $outputPath) ?: '';
					}
                } else {					
					$tempThumbUrl = $this->generateFromVideoToImg($source, $outputPath) ?: '';
				} 
			}

			if (!empty($tempThumbUrl)) {
				$savedThumbInfo = [
					'name' => $outputName,
					'path' => $tempThumbUrl
				];
			}
		}

        return $savedThumbInfo;
    }

    /**
     * @return string path to image
     */
    public function generateFromVideoToImg(string $source, string $outputPathWithFileExtension): string
    {
        $cadrPosition = !empty($this->cadrPosition) ? '-ss ' . $this->cadrPosition : '';
        $imageQuality = '-q:v ' . $this->imageQuality;
        $command = "-i {$source} -vframes {$this->numbOfFrames} {$imageQuality} -y {$cadrPosition} {$outputPathWithFileExtension}";

        $isSuccess = execCmdCommand('ffmpeg', $command);
        $pathToImage = $isSuccess ? $outputPathWithFileExtension : '';

        return $pathToImage;
    }

    public function generateFromImgToImg(string $source, string $outputPathWithFileExtension): string
    {
        $imageQuality = '-q:v ' . $this->imageQuality;
        $command = "-i {$source} {$imageQuality} -y {$outputPathWithFileExtension}";

        $isSuccess = execCmdCommand('ffmpeg', $command);
        $pathToImage = $isSuccess ? $outputPathWithFileExtension : '';

        return $pathToImage;
    }

    public function setImageQuality(int $imageQuality = 1)
    {
        if (!empty($imageQuality)) {
            $this->imageQuality = $imageQuality;
        }

        return $this;
    }

    public function setCadrPosition(string $cadrPosition)
    {
        if (!empty($cadrPosition)) {
            $this->cadrPosition = $cadrPosition;
        }

        return $this;
    }

    public function setNumberOfFrames(int $numbOfFrames = 1)
    {
        if (!empty($numbOfFrames)) {
            $this->numbOfFrames = $numbOfFrames;
        }

        return $this;
    }
}