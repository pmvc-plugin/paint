<?php
namespace PMVC\PlugIn\paint;

use PMVC\TestCase;

class PaintTest extends TestCase
{
    private $_plug = 'paint';
    function testPlugin()
    {
        ob_start();
        print_r(\PMVC\plug($this->_plug));
        $output = ob_get_contents();
        ob_end_clean();
        $this->haveString($this->_plug,$output);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    function testPaint()
    {
        $p = \PMVC\plug($this->_plug);
        $canvas = $p->getPaintBySize(200,200); 
        $canvas->paintCross(
           $p->getPoint(5, 5), 
           $p->getColor(255),
           5
        );
        $this->assertEquals(
            '255,255,255',
            (string)$canvas->getPixel($p->getPoint(0,0))
        );
        $this->assertEquals(
            '255,0,0',
            (string)$canvas->getPixel($p->getPoint(0,5))
        );
    }

}
