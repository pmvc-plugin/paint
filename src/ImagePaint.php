<?php
namespace PMVC\PlugIn\paint;

use PMVC\PlugIn\image\ImageFile;
use PMVC\PlugIn\image\ImageSize;
use PMVC\PlugIn\image\Coord2D;
use PMVC\PlugIn\color\BaseColor;

class ImagePaint
{
    private $im = null;

    /**
     * @param mixed $input ImageFile (for real file) or ImageSize (for empty page)
     */
    public function __construct($input)
    {
        $this->im = \PMVC\plug('image')->create($input);
    }

    public function getPixel(Coord2D $point)
    {
        return $this->im->getPixel($point);
    }

    public function setPixel(Coord2D $point, BaseColor $color)
    {
        return $this->im->setPixel($point, $color);
    }

    public function fillRect(Coord2D $point, ImageSize $size, BaseColor $color)
    {
        imagefilledrectangle(
            $this->toGd(),
            $point->x,
            $point->y,
            $point->x + $size->w,
            $point->y + $size->h,
            $color->toGd($this->toGd())
        );
    }

    public function fillCircle(Coord2D $point, $radius, BaseColor $color)
    {
        imagefilledellipse(
            $this->toGd(),
            $point->x,
            $point->y,
            $radius * 2,
            $radius * 2,
            $color->toGd($this->toGd())
        );
    }

    public function paintCross(Coord2D $point, BaseColor $color, $size)
    {
        $points = \PMVC\plug('paint')
            ->cross()
            ->getPoints($point, $size);
        foreach ($points->points as $p) {
            $this->setPixel($p, $color);
        }
    }

    public function overlayPixel(Coord2D $point, BaseColor $color, $alpha)
    {
        $existing = $this->getPixel($point);
        $newColor = $color->getClone()->setAlpha($existing, $alpha);
        $this->setPixel($point, $newColor);
    }

    public function overlayRect(
        Coord2D $point,
        ImageSize $size,
        BaseColor $color,
        $alpha
    ) {
        \PMVC\plug('image')->process($size, function (
            $point,
        ) use ($color, $alpha) {
            $this->overlayPixel($point, $color, $alpha);
        }, $point);
    }

    public function text(
        $text,
        Coord2D $point,
        BaseColor $color,
        $size = 13,
        $angle = 1
    ) {
        $fontfile = \PMVC\plug('image')->getResource('Slabo13px-Regular.ttf');
        imagettftext(
            $this->toGd(),
            $size,
            $angle,
            $point->x,
            $point->y,
            $color->toGd($this->toGd()),
            $fontfile,
            $text
        );
    }

    public function toGd()
    {
        return $this->im->toGd();
    }
}
