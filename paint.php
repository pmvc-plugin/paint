<?php
namespace PMVC\PlugIn\paint;

use PMVC\PlugIn\image\ImageFile;
use PMVC\PlugIn\image\ImageSize;
use PMVC\PlugIn\image\Coord2D;
use PMVC\PlugIn\color\BaseColor;

\PMVC\l(__DIR__.'/src/ImagePaint.php');

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\paint';

class paint extends \PMVC\PlugIn
{
    public function getPaintBySize($w, $h, BaseColor $color = null)
    {
        $canvas =  new ImagePaint(new ImageSize($w, $h));
        if (is_null($color)) {
            $color = $this->getColor(255,255,255);
        }
        \PMVC\plug('color')->fill(
            $canvas,
            $color
        );
        return $canvas;
    }

    public function getPaintByFile($f)
    {
        return new ImagePaint(new ImageFile($f));
    }

    public function getResource($file)
    {
        $file = \PMVC\realpath($this->getDir().'resource/'.$file);
        if($file){
            return $file;
        }
    }

    public function getPoint($x, $y)
    {
        return new Coord2D($x, $y);        
    }

    public function getColor($r=null, $g=null, $b=null)
    {
        return \PMVC\plug('color')->getColor(
            $r,
            $g,
            $b
        );
    }
}
