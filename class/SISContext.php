<?php namespace XoopsModules\Pedigree;

use XoopsModules\Pedigree;

/**
 * Class Pedigree\SISContext
 */
class SISContext
{
    private $contexts;
    private $depth;

    /**
     * SISContext constructor.
     */
    public function __construct()
    {
        $this->contexts = [];
        $this->depth    = 0;
    }

    /**
     * @param $url
     * @param $name
     */
    public function myGoto($url, $name)
    {
        $keys = array_keys($this->contexts);
        for ($i = 0; $i < $this->depth; ++$i) {
            if ($keys[$i] == $name) {
                $this->contexts[$name] = $url; // the url might be slightly different
                $this->depth           = $i + 1;

                for ($x = count($this->contexts); $x > $i + 1; $x--) {
                    array_pop($this->contexts);
                }

                return;
            }
        }

        $this->contexts[$name] = $url;
        $this->depth++;
    }

    /**
     * @return array
     */
    public function getAllContexts()
    {
        return $this->contexts;
    }

    /**
     * @return array
     */
    public function getAllContextNames()
    {
        return array_keys($this->contexts);
    }
}
