<?php
namespace PMVC\PlugIn\paint;

use PMVC\PlugIn\image\ImageFile;
use PMVC\PlugIn\image\ImageSize;

\PMVC\l(__DIR__.'/src/ImagePaint.php');

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\paint';

class paint extends \PMVC\PlugIn
{
    public function getPaintBySize($w, $h)
    {
        return new ImagePaint(new ImageSize($w, $h));
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
}
