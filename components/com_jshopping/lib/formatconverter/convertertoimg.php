<?php 

include __DIR__ . '/formatconverter.php';

class ConverterToImg
{

    public function convert($fullPathToSourceFile, $fullPathToOutputFile)
    {

        if (file_exists($fullPathToSourceFile)) {
            $isAlreadyImg = (exif_imagetype($fullPathToSourceFile) !== false);

            if ($isAlreadyImg) {
                return $this->convertByImagemagick($fullPathToSourceFile, $fullPathToOutputFile);
            }

            return $this->convertByGhostScript($fullPathToSourceFile, $fullPathToOutputFile);
        }

        return false;
    }

    protected function convertByImagemagick($fullPathToSourceFile, $fullPathToOutputFile): bool
    {
        exec('/usr/bin/convert -quality 100 -density 200 ' . $fullPathToSourceFile . '  -colorspace RGB -resize 800  ' . $fullPathToOutputFile, $output, $return_var);

        if ($return_var == 1) {
            exec('convert -quality 100 -density 200 ' . $fullPathToSourceFile . '  -colorspace RGB -resize 800  ' . $fullPathToOutputFile, $output, $return_var);
        }

        return !$return_var;
    }

    protected function convertByGhostScript($fullPathToSourceFile, $fullPathToOutputFile): bool
    {
        exec('gs -dNOPAUSE -dBATCH -dNOPROMPT -dDEVICEWIDTHPOINTS=560 -r150 -dPDFFitPage -q -dLastPage=1 -sDEVICE=jpeg -sOutputFile=' . $fullPathToOutputFile . ' ' . $fullPathToSourceFile, $output, $return_var);
        return !$return_var;
    }

}