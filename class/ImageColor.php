<?php

namespace XoopsModules\Pedigree;

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Color.php is the implementation of ImageColor.
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category    Image
 * @package     ImageColor
 * @author      Jason Lotito <jason@lehighweb.com>
 * @author      Andrew Morton <drewish@katherinehouse.com>
 * @copyright   2003-2005 The PHP Group
 * @license     http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version     1.1 2006/04/30
 * @link        http://pear.php.net/package/ImageColor
 */

/**
 * ImageColor handles color conversion and mixing.
 *
 * The class is quick, simple to use, and does its job fairly well but it's got
 * some code smells:
 *  - Call setColors() for some functions but not others.
 *  - Different functions expect different color formats. setColors() only
 *    accepts hex while allocateColor() will accept named or hex (provided the
 *    hex ones start with the # character).
 *  - Some conversions go in only one direction, ie HSV->RGB but no RGB->HSV.
 * I'm going to try to straighten out some of this but I'll be hard to do so
 * without breaking backwards compatibility.
 *
 * @category    Image
 * @package     ImageColor
 * @author      Jason Lotito <jason@lehighweb.com>
 * @author      Andrew Morton <drewish@katherinehouse.com>
 * @copyright   2003-2005 The PHP Group
 * @license     http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version     Release: 0.1.2
 * @link        http://pear.php.net/package/ImageColor
 */

/**
 * Class ImageColor
 */
class ImageColor
{
    /**
     * First color that the class handles for ranges and mixes.
     *
     * @var array
     * @access  public
     * @see     setColors()
     */
    public $color1 = [];

    /**
     * Second color that the class handles for ranges and mixes.
     *
     * @var array
     * @access  public
     * @see     setColors()
     */
    public $color2 = [];

    /**
     * Boolean value for determining whether colors outputted should be limited
     * to the web safe pallet or not.
     *
     * @var bool
     * @access  private
     * @see     setWebSafe()
     */
    private $websafeb = false;

    /**
     * Mix two colors together by finding their average. If the colors are not
     * passed as parameters, the class's colors will be mixed instead.
     *
     * @param bool|string $col1 The first color you want to mix
     * @param bool|string $col2 The second color you want to mix
     *
     * @return string The mixed color.
     * @access  public
     * @author  Jason Lotito <jason@lehighweb.com>
     * @uses    setInternalColors() to assign the colors if any are passed to the
     *                class.
     */
    public function mixColors($col1 = false, $col2 = false)
    {
        if ($col1) {
            $this->setInternalColors($col1, $col2);
        }

        // after finding the average, it will be a float. add 0.5 and then
        // cast to an integer to properly round it to an integer.
        $color3[0] = (int)((($this->color1[0] + $this->color2[0]) / 2) + 0.5);
        $color3[1] = (int)((($this->color1[1] + $this->color2[1]) / 2) + 0.5);
        $color3[2] = (int)((($this->color1[2] + $this->color2[2]) / 2) + 0.5);

        if ($this->websafeb) {
            array_walk($color3, '_makeWebSafe');
        }

        return $this->rgb2hex($color3);
    }

    /**
     * Determines whether colors the returned by this class will be rounded to
     * the nearest web safe value.
     *
     * @param bool $bool Indicates if colors should be limited to the
     *                      websafe pallet.
     *
     * @access  public
     * @author  Jason Lotito <jason@lehighweb.com>
     */
    public function setWebSafe($bool = true)
    {
        $this->websafeb = (bool)$bool;
    }

    /**
     * Set the two colors this class uses for mixing and ranges.
     *
     * @param string $col1 The first color in hex format
     * @param string $col2 The second color in hex format
     *
     * @access  public
     * @author  Jason Lotito <jason@lehighweb.com>
     */
    public function setColors($col1, $col2)
    {
        $this->setInternalColors($col1, $col2);
    }

    /**
     * Get the range of colors between the class's two colors, given a degree.
     *
     * @param int $degrees How large a 'step' we should take between the
     *                         colors.
     *
     * @return array Returns an array of hex strings, one element for each
     *               color.
     * @access  public
     * @author  Jason Lotito <jason@lehighweb.com>
     * @todo    Allow for degrees for individual parts of the colors.
     */
    public function getRange($degrees = 2)
    {
        if (0 == $degrees) {
            $degrees = 1;
        }

        // The degrees give us how much we should advance each color at each
        // phase of the loop.  This way, the advance is equal throughout all
        // the colors.

        $red_steps = ($this->color2[0] - $this->color1[0]) / $degrees;
        $green_steps = ($this->color2[1] - $this->color1[1]) / $degrees;
        $blue_steps = ($this->color2[2] - $this->color1[2]) / $degrees;

        $allcolors = [];

        /**
         * The loop stops once any color has gone beyond the end color.
         */

        // Loop through all the degrees between the colors
        for ($x = 0; $x < $degrees; ++$x) {
            $col[0] = $red_steps * $x;
            $col[1] = $green_steps * $x;
            $col[2] = $blue_steps * $x;

            // Loop through each R, G, and B
            for ($i = 0; $i < 3; ++$i) {
                $partcolor = $this->color1[$i] + $col[$i];
                // If the color is less than 256
                if ($partcolor < 256) {
                    // Makes sure the colors is not less than 0
                    if ($partcolor > -1) {
                        $newcolor[$i] = $partcolor;
                    } else {
                        $newcolor[$i] = 0;
                    }
                    // Color was greater than 255
                } else {
                    $newcolor[$i] = 255;
                }
            }

            if ($this->websafeb) {
                array_walk($newcolor, '_makeWebSafe');
            }

            $allcolors[] = $this->rgb2hex($newcolor);
        }

        return $allcolors;
    }

    /**
     * Change the lightness of the class's two colors.
     *
     * @param int $degree The degree of the change. Positive values
     *                        lighten the color while negative values will darken it.
     *
     * @access  public
     * @author  Jason Lotito <jason@lehighweb.com>
     * @uses    ImageColor::$color1 as an input and return value.
     * @uses    ImageColor::$color2 as an input and return value.
     */
    public function changeLightness($degree = 10)
    {
        $color1 = &$this->color1;
        $color2 = &$this->color2;

        for ($x = 0; $x < 3; ++$x) {
            if (($color1[$x] + $degree) < 256) {
                if (($color1[$x] + $degree) > -1) {
                    $color1[$x] += $degree;
                } else {
                    $color1[$x] = 0;
                }
            } else {
                $color1[$x] = 255;
            }

            if (($color2[$x] + $degree) < 256) {
                if (($color2[$x] + $degree) > -1) {
                    $color2[$x] += $degree;
                } else {
                    $color2[$x] = 0;
                }
            } else {
                $color2[$x] = 255;
            }
        }
    }

    /**
     * Determine if a light or dark text color would be more readable on a
     * background of a given color. This is determined by the G(reen) value of
     * RGB. You can change the dark and the light colors from their default
     * black and white.
     *
     * @param string|array $color The hex color to analyze
     * @param string $light The light color value to return if we should
     *                      have light text.
     * @param string $dark  The dark color value to return if we should have
     *                      dark text.
     *
     * @return string The light or dark value which would make the text most
     *                readable.
     * @access  public
     * @static
     * @author  Jason Lotito <jason@lehighweb.com>
     */
    public function getTextColor($color, $light = '#FFFFFF', $dark = '#000000')
    {
        $color = $this->splitColor($color);
        if ($color[1] > hexdec('66')) {
            return $dark;
        }

        return $light;
    }

    /**
     * Internal method to set the colors.
     *
     * @param string $col1 First color, either a name or hex value
     * @param string $col2 Second color, either a name or hex value
     *
     * @access  private
     * @author  Jason Lotito <jason@lehighweb.com>
     */
    private function setInternalColors($col1, $col2)
    {
        if ($col1) {
            $this->color1 = $this->splitColor($col1);
        }
        if ($col2) {
            $this->color2 = $this->splitColor($col2);
        }
    }

    /**
     * Given a color, properly split it up into a 3 element RGB array.
     *
     * @param string $color The color.
     *
     * @return array A three element RGB array.
     * @access  private
     * @static
     * @author  Jason Lotito <jason@lehighweb.com>
     */
    private function splitColor($color)
    {
        $color = str_replace('#', '', $color);
        $c[] = hexdec(mb_substr($color, 0, 2));
        $c[] = hexdec(mb_substr($color, 2, 2));
        $c[] = hexdec(mb_substr($color, 4, 2));

        return $c;
    }

    /**
     * This is deprecated. Use rgb2hex() instead.
     *
     * @access     private
     * @deprecated Function deprecated after 1.0.1
     * @see        rgb2hex().
     *
     * @param $color
     *
     * @return string
     */
    private function returnColor($color)
    {
        return $this->rgb2hex($color);
    }

    /**
     * Convert an RGB array to a hex string.
     *
     * @param array $color 3 element RGB array.
     *
     * @return string Hex color string.
     * @access  public
     * @static
     * @author  Jason Lotito <jason@lehighweb.com>
     * @see     hex2rgb()
     */
    public function rgb2hex($color)
    {
        return sprintf('%02X%02X%02X', $color[0], $color[1], $color[2]);
    }

    /**
     * Convert a hex color string into an RGB array. An extra fourth element
     * will be returned with the original hex value.
     *
     * @param string $hex Hex color string.
     *
     * @return array RGB color array with an extra 'hex' element containing
     *               the original hex string.
     * @access  public
     * @static
     * @author  Jason Lotito <jason@lehighweb.com>
     * @see     rgb2hex()
     */
    public function hex2rgb($hex)
    {
        $return = $this->splitColor($hex);
        $return['hex'] = $hex;

        return $return;
    }

    /**
     * Convert an HSV (Hue, Saturation, Brightness) value to RGB.
     *
     * @param int $h Hue
     * @param int $s Saturation
     * @param int $v Brightness
     *
     * @return array RGB array.
     * @access  public
     * @static
     * @author  Jason Lotito <jason@lehighweb.com>
     * @uses    hsv2hex() to convert the HSV value to Hex.
     * @uses    hex2rgb() to convert the Hex value to RGB.
     */
    public function hsv2rgb($h, $s, $v)
    {
        return $this->hex2rgb($this->hsv2hex($h, $s, $v));
    }

    /**
     * Convert HSV (Hue, Saturation, Brightness) to a hex color string.
     *
     * Originally written by Jurgen Schwietering. Integrated into the class by
     * Jason Lotito.
     *
     * @param int $h Hue
     * @param int $s Saturation
     * @param int $v Brightness
     *
     * @return string The hex string.
     * @access  public
     * @static
     * @author  Jurgen Schwietering <jurgen@schwietering.com>
     * @uses    rgb2hex() to convert the return value to a hex string.
     */
    public function hsv2hex($h, $s, $v)
    {
        $s /= 256.0;
        $v /= 256.0;
        if (0.0 == $s) {
            $r = $g = $b = $v;

            return '';
        }
        $h = $h / 256.0 * 6.0;
        $i = floor($h);
        $f = $h - $i;

        $v *= 256.0;
        $p = (int)($v * (1.0 - $s));
        $q = (int)($v * (1.0 - $s * $f));
        $t = (int)($v * (1.0 - $s * (1.0 - $f)));
        switch ($i) {
                case 0:
                    $r = $v;
                    $g = $t;
                    $b = $p;
                    break;
                case 1:
                    $r = $q;
                    $g = $v;
                    $b = $p;
                    break;
                case 2:
                    $r = $p;
                    $g = $v;
                    $b = $t;
                    break;
                case 3:
                    $r = $p;
                    $g = $q;
                    $b = $v;
                    break;
                case 4:
                    $r = $t;
                    $g = $p;
                    $b = $v;
                    break;
                default:
                    $r = $v;
                    $g = $p;
                    $b = $q;
                    break;
            }

        return $this->rgb2hex([$r, $g, $b]);
    }

    /**
     * Allocates a color in the given image.
     *
     * User defined color specifications get translated into an array of RGB
     * values.
     *
     * @param resource     $img   Image handle
     * @param string|array $color Name or hex string or an RGB array.
     *
     * @return bool Image color handle.
     * @access  public
     * @static
     * @uses    imagefilledarc() to allocate the color.
     * @uses    color2RGB() to parse the color into RGB values.
     */
    public function allocateColor(&$img, $color)
    {
        $color = $this->color2RGB($color);

        return imagefilledarc($img, $color[0], $color[1], $color[2]);
    }

    /**
     * Convert a named or hex color string to an RGB array. If the color begins
     * with the # character it will be treated as a hex value. Everything else
     * will be treated as a named color. If the named color is not known, black
     * will be returned.
     *
     * @param string $color
     *
     * @return array RGB color
     * @access  public
     * @static
     * @author  Laurent Laville <pear@laurent-laville.org>
     * @uses    hex2rgb() to convert colors begining with the # character.
     * @uses    namedColor2RGB() to convert everything not starting with a #.
     */
    public function color2RGB($color)
    {
        $c = [];

        if ('#' === $color[0]) {
            $c = $this->hex2rgb($color);
        } else {
            $c = $this->namedColor2RGB($color);
        }

        return $c;
    }

    /**
     * Convert a named color to an RGB array. If the color is unknown black
     * is returned.
     *
     * @param string $color Case insensitive color name.
     *
     * @return array RGB color array. If the color was unknown, the result
     *               will be black.
     * @access  public
     * @static
     * @author  Sebastian Bergmann <sb@sebastian-bergmann.de>
     */
    public function namedColor2RGB($color)
    {
        static $colornames;

        if (!isset($colornames)) {
            $colornames = [
                'aliceblue' => [240, 248, 255],
                'antiquewhite' => [250, 235, 215],
                'aqua' => [0, 255, 255],
                'aquamarine' => [127, 255, 212],
                'azure' => [240, 255, 255],
                'beige' => [245, 245, 220],
                'bisque' => [255, 228, 196],
                'black' => [0, 0, 0],
                'blanchedalmond' => [255, 235, 205],
                'blue' => [0, 0, 255],
                'blueviolet' => [138, 43, 226],
                'brown' => [165, 42, 42],
                'burlywood' => [222, 184, 135],
                'cadetblue' => [95, 158, 160],
                'chartreuse' => [127, 255, 0],
                'chocolate' => [210, 105, 30],
                'coral' => [255, 127, 80],
                'cornflowerblue' => [100, 149, 237],
                'cornsilk' => [255, 248, 220],
                'crimson' => [220, 20, 60],
                'cyan' => [0, 255, 255],
                'darkblue' => [0, 0, 13],
                'darkcyan' => [0, 139, 139],
                'darkgoldenrod' => [184, 134, 11],
                'darkgray' => [169, 169, 169],
                'darkgreen' => [0, 100, 0],
                'darkkhaki' => [189, 183, 107],
                'darkmagenta' => [139, 0, 139],
                'darkolivegreen' => [85, 107, 47],
                'darkorange' => [255, 140, 0],
                'darkorchid' => [153, 50, 204],
                'darkred' => [139, 0, 0],
                'darksalmon' => [233, 150, 122],
                'darkseagreen' => [143, 188, 143],
                'darkslateblue' => [72, 61, 139],
                'darkslategray' => [47, 79, 79],
                'darkturquoise' => [0, 206, 209],
                'darkviolet' => [148, 0, 211],
                'deeppink' => [255, 20, 147],
                'deepskyblue' => [0, 191, 255],
                'dimgray' => [105, 105, 105],
                'dodgerblue' => [30, 144, 255],
                'firebrick' => [178, 34, 34],
                'floralwhite' => [255, 250, 240],
                'forestgreen' => [34, 139, 34],
                'fuchsia' => [255, 0, 255],
                'gainsboro' => [220, 220, 220],
                'ghostwhite' => [248, 248, 255],
                'gold' => [255, 215, 0],
                'goldenrod' => [218, 165, 32],
                'gray' => [128, 128, 128],
                'green' => [0, 128, 0],
                'greenyellow' => [173, 255, 47],
                'honeydew' => [240, 255, 240],
                'hotpink' => [255, 105, 180],
                'indianred' => [205, 92, 92],
                'indigo' => [75, 0, 130],
                'ivory' => [255, 255, 240],
                'khaki' => [240, 230, 140],
                'lavender' => [230, 230, 250],
                'lavenderblush' => [255, 240, 245],
                'lawngreen' => [124, 252, 0],
                'lemonchiffon' => [255, 250, 205],
                'lightblue' => [173, 216, 230],
                'lightcoral' => [240, 128, 128],
                'lightcyan' => [224, 255, 255],
                'lightgoldenrodyellow' => [250, 250, 210],
                'lightgreen' => [144, 238, 144],
                'lightgrey' => [211, 211, 211],
                'lightpink' => [255, 182, 193],
                'lightsalmon' => [255, 160, 122],
                'lightseagreen' => [32, 178, 170],
                'lightskyblue' => [135, 206, 250],
                'lightslategray' => [119, 136, 153],
                'lightsteelblue' => [176, 196, 222],
                'lightyellow' => [255, 255, 224],
                'lime' => [0, 255, 0],
                'limegreen' => [50, 205, 50],
                'linen' => [250, 240, 230],
                'magenta' => [255, 0, 255],
                'maroon' => [128, 0, 0],
                'mediumaquamarine' => [102, 205, 170],
                'mediumblue' => [0, 0, 205],
                'mediumorchid' => [186, 85, 211],
                'mediumpurple' => [147, 112, 219],
                'mediumseagreen' => [60, 179, 113],
                'mediumslateblue' => [123, 104, 238],
                'mediumspringgreen' => [0, 250, 154],
                'mediumturquoise' => [72, 209, 204],
                'mediumvioletred' => [199, 21, 133],
                'midnightblue' => [25, 25, 112],
                'mintcream' => [245, 255, 250],
                'mistyrose' => [255, 228, 225],
                'moccasin' => [255, 228, 181],
                'navajowhite' => [255, 222, 173],
                'navy' => [0, 0, 128],
                'oldlace' => [253, 245, 230],
                'olive' => [128, 128, 0],
                'olivedrab' => [107, 142, 35],
                'orange' => [255, 165, 0],
                'orangered' => [255, 69, 0],
                'orchid' => [218, 112, 214],
                'palegoldenrod' => [238, 232, 170],
                'palegreen' => [152, 251, 152],
                'paleturquoise' => [175, 238, 238],
                'palevioletred' => [219, 112, 147],
                'papayawhip' => [255, 239, 213],
                'peachpuff' => [255, 218, 185],
                'peru' => [205, 133, 63],
                'pink' => [255, 192, 203],
                'plum' => [221, 160, 221],
                'powderblue' => [176, 224, 230],
                'purple' => [128, 0, 128],
                'red' => [255, 0, 0],
                'rosybrown' => [188, 143, 143],
                'royalblue' => [65, 105, 225],
                'saddlebrown' => [139, 69, 19],
                'salmon' => [250, 128, 114],
                'sandybrown' => [244, 164, 96],
                'seagreen' => [46, 139, 87],
                'seashell' => [255, 245, 238],
                'sienna' => [160, 82, 45],
                'silver' => [192, 192, 192],
                'skyblue' => [135, 206, 235],
                'slateblue' => [106, 90, 205],
                'slategray' => [112, 128, 144],
                'snow' => [255, 250, 250],
                'springgreen' => [0, 255, 127],
                'steelblue' => [70, 130, 180],
                'tan' => [210, 180, 140],
                'teal' => [0, 128, 128],
                'thistle' => [216, 191, 216],
                'tomato' => [255, 99, 71],
                'turquoise' => [64, 224, 208],
                'violet' => [238, 130, 238],
                'wheat' => [245, 222, 179],
                'white' => [255, 255, 255],
                'whitesmoke' => [245, 245, 245],
                'yellow' => [255, 255, 0],
                'yellowgreen' => [154, 205, 50],
            ];
        }

        $color = mb_strtolower($color);

        if (isset($colornames[$color])) {
            return $colornames[$color];
        }

        return [0, 0, 0];
    }

    /**
     * Convert an RGB percentage string into an RGB array.
     *
     * @param string|array $color Percentage color string like "50%,20%,100%".
     *
     * @return array RGB color array.
     * @access  public
     * @static
     */
    public function percentageColor2RGB($color)
    {
        // remove spaces
        $color = str_replace(' ', '', $color);
        // remove the percent signs
        $color = str_replace('%', '', $color);
        // split the string by commas
        $color = explode(',', $color);

        $ret = [];
        foreach ($color as $k => $v) {
            // range checks
            if ($v <= 0) {
                $ret[$k] = 0;
            } elseif ($v <= 100) {
                // add 0.5 then cast to an integer to round the value.
                $ret[$k] = (int)((2.55 * $v) + 0.5);
            } else {
                $ret[$k] = 255;
            }
        }

        return $ret;
    }
}

// For Array Walk
// {{{
/**
 * Function for array_walk() to round colors to the closest web safe value.
 *
 * @param int $color One channel of an RGB color.
 *
 * @return int The websafe equivalent of the color channel.
 * @author  Jason Lotito <jason@lehighweb.com>
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @access  private
 * @static
 */
function _makeWebSafe(&$color)
{
    if ($color < 0x1a) {
        $color = 0x00;
    } elseif ($color < 0x4d) {
        $color = 0x33;
    } elseif ($color < 0x80) {
        $color = 0x66;
    } elseif ($color < 0xB3) {
        $color = 0x99;
    } elseif ($color < 0xE6) {
        $color = 0xCC;
    } else {
        $color = 0xFF;
    }

    return $color;
}
// }}}
