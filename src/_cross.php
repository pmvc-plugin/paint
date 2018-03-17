<?php
namespace PMVC\PlugIn\paint;

use PMVC\PlugIn\image\ImageSize;
use PMVC\PlugIn\image\Coord2D;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\Cross';

class Cross
{
    const left='left';
    const right='right';
    const top='top';
    const bottom='bottom';
    const center='center';

    public function __invoke()
    {
        return $this;
    }

    public function getPoints(Coord2D $point, $size, ImageSize $imgSize=null)
    {
       $points = array();
       $directions = array();
       $this->storePoint(
            $point,
            $points,
            $directions,
            self::center
        );
       for ($i=1; $i<=$size; $i++) {
           $this->storePoint(
                new Coord2D($point->x, $point->y-$i),
                $points,
                $directions,
                self::top,
                $i,
                $imgSize
            );
           $this->storePoint(
                new Coord2D($point->x+$i, $point->y),
                $points,
                $directions,
                self::right,
                $i,
                $imgSize
            );
           $this->storePoint(
                new Coord2D($point->x, $point->y+$i),
                $points,
                $directions,
                self::bottom,
                $i,
                $imgSize
            );
           $this->storePoint(
                new Coord2D($point->x-$i, $point->y),
                $points,
                $directions,
                self::left,
                $i,
                $imgSize
            );
       }
       return (object)array(
            'points'=>$points,
            'directions'=>$directions
       );
    }

    private function storePoint(
        Coord2D $point,
        &$points,
        &$directions,
        $direction,
        $seqNum=0,
        ImageSize $imgSize=null
    ) {
        if (0>$point->x || 0>$point->y) {
            return false;
        }
        if (!is_null($imgSize) && $point->x >= $imgSize->w) {
            return false;
        }
        if (!is_null($imgSize) && $point->y >= $imgSize->h) {
            return false;
        }
        $p_key = $point->toString();
        $points[$p_key] = $point;
        $directions[$p_key] = (object)array(
            'seq'=>$seqNum,
            'dir'=>$direction
        );
    }
}
