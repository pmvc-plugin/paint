<?php
namespace PMVC\PlugIn\paint;

use PMVC\TestCase;
use PMVC\PlugIn\image\Coord2D;

const DEMO_IMG = __DIR__.'/../../vendor/pmvc-plugin/image/tests/resource/demo.jpg';

class PixelateTes extends TestCase
{
    private $_plug = 'paint';
    public function testPixelate()
    {
        $oPlug = \PMVc\plug($this->_plug);
        $out = $oPlug->pixelate(DEMO_IMG);
        $color = $out->getPixel(new Coord2D(10, 10));
        $this->assertEquals(['r'=>38, 'g'=>101, 'b'=>207], $color->toArray());
    }
}
