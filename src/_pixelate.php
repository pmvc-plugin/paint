<?php

namespace PMVC\PlugIn\paint;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\Pixelate';

use PMVC\PlugIn\image\ImageSize;
use PMVC\PlugIn\image\ImageFile;
use PMVC\PlugIn\image\ImageOutput;

class Pixelate
{
    public function __invoke($input, $pixelBlockSize = [10, 10])
    {
        $pImage = \PMVC\plug('image');
        $pbSize = new ImageSize($pixelBlockSize);
        $pFile = new ImageFile($input);
        $pFileWH = $pFile->getSize();
        $pImage->process($pFileWH, function($curPoint, $movePoint) use ($pFile) {
          $color = $pFile->getPixel($curPoint)->toArray();
          $simColor = imagecolorclosest(
              $pFile->toGd(),
              $color['r'],
              $color['g'],
              $color['b'],
          );
          imagefilledrectangle(
              $pFile->toGd(),
              $curPoint->x,
              $curPoint->y,
              $movePoint->x,
              $movePoint->y,
              $simColor
          );
        }, null, $pbSize);
        return new ImageOutput($pFile);
    }
}
