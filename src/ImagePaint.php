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

    public function __destruct()
    {
        imagedestroy($this->im);
        $this->im = null;
    }
 
    public function getPixel(Coord2D $point)
    {
        $rgb = imagecolorat($this->im, $point->x, $point->y);
        $red = ($rgb >> 16) & 0xff;
        $green = ($rgb >> 8) & 0xff;
        $blue = $rgb & 0xff;
        $color = \PMVC\plug('color');
        return $color->getColor($red, $green, $blue);
    }
        
    public function setPixel(Coord2D $point, BaseColor $color)
    {
        imagesetpixel($this->im, $point->x, $point->y, $color->toGd($this->im));
    }
        
    public function fillRect(Coord2D $point, ImageSize $size, BaseColor $color)
    {
        imagefilledrectangle(
            $this->im,
            $point->x,
            $point->y,
            $point->x + $size->w,
            $point->y + $size->h,
            $color->toGd($this->im)
        );
    }

    public function fillCircle(Coord2D $point, $radius, BaseColor $color)
    {
        imagefilledellipse(
            $this->im, 
            $point->x, 
            $point->y, 
            $radius * 2, 
            $radius * 2, 
            $color->toGd($this->im)
        );
    }

    public function paintCross(Coord2D $point, BaseColor $color, $size)
    {
        $points = \PMVC\plug('paint')->cross()->getPoints($point, $size);
        foreach ($points->points as $p) {
            $this->setPixel($p, $color);
        }
    }

    public function overlayPixel(Coord2D $point, BaseColor $color, $alpha)
    {
        $existing = $this->getPixel($point);
        $newColor = $color->getClone()->setAlpha($existing,$alpha);
        $this->setPixel($point, $newColor);
    }

    public function overlayRect(Coord2D $point, ImageSize $size, BaseColor $color, $alpha)
    {
        $self = $this;
        \PMVC\plug('image')->process($point,$size,array($color,$alpha),function($point,$color,$alpha) use($self) {
            $self->overlayPixel($point, $color, $alpha);
        });
    }

    public function text($text, Coord2D $point, BaseColor $color, $size=13, $angle=1) 
    {
        $fontfile = \PMVC\plug('image')->getResource('Slabo13px-Regular.ttf');
        imagettftext ( 
            $this->im , 
            $size , 
            $angle , 
            $point->x , 
            $point->y , 
            $color->toGd($this->im) , 
            $fontfile , 
            $text
        );
    }

    public function toGd()
    {
        return $this->im;
    }
}
