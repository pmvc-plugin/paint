<?php
namespace PMVC\PlugIn\paint;
use PHPUnit_Framework_TestCase;

\PMVC\Load::plug();
\PMVC\addPlugInFolders(['../']);

class PaintTest extends PHPUnit_Framework_TestCase
{
    private $_plug = 'paint';
    function testPlugin()
    {
        ob_start();
        print_r(\PMVC\plug($this->_plug));
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains($this->_plug,$output);
    }

    function testPaint()
    {
        $canvas = \PMVC\plug($this->_plug)->getPaintBySize(200,200); 
    }

}
