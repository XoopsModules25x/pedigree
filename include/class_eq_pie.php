<?php

// eq_pie written by ellardus (C) 2005
// for more info look at www.eq-home.com
// or email at ellardus@eq-home.com
// Feel free to use it, a reference to me would be nice.
// Thank you and good luck!

/**
 * Class eq_pie
 */
class eq_pie
{
    /**
     * eq_pie constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $number
     *
     * @return mixed
     */
    public function getColor($number)
    {
        $color = [
            '#ff0000',
            '#00ff00',
            '#0000ff',
            '#ffff00',
            '#ff00ff',
            '#00ffff',
            '#cc0000',
            '#00cc00',
            '#0000cc',
            '#990000',
            '#009900',
            '#000099',
            '#660000',
            '#006600',
            '#000066',
            '#330000',
            '#003300',
            '#000033'
        ];

        return $color[$number];
    }

    /**
     * @param $filename
     * @param $pieWidth
     * @param $pieHeight
     * @param $ShadowDistance
     * @param $pieBackgroundColor
     * @param $EQpieData
     * @param $legend
     */
    public function MakePie(
        $filename,
        $pieWidth,
        $pieHeight,
        $ShadowDistance,
        $pieBackgroundColor,
        $EQpieData,
        $legend
    ) {
        if (!function_exists('imagecreatetruecolor')) {
            die('Error, GD Library 2 needed.');
        }

        //set some limitations
        if ($pieWidth < 100 | $pieWidth > 500) {
            $pieWidth = 100;
        }
        if ($pieHeight < 100 | $pieHeight > 500) {
            $pieHeight = 100;
        }
        if ($ShadowDistance < 1 | $ShadowDistance > 50) {
            $ShadowDistance = 10;
        }

        $pieWidth           *= 3;
        $pieHeight          *= 3;
        $ShadowDistance     *= 3;
        $pieBackgroundColor = $pieBackgroundColor;

        $pie = @imagecreatetruecolor($pieWidth, $pieHeight + $ShadowDistance);

        $colR  = hexdec(substr($pieBackgroundColor, 1, 2));
        $colG  = hexdec(substr($pieBackgroundColor, 3, 2));
        $colB  = hexdec(substr($pieBackgroundColor, 5, 2));
        $pieBG = imagefilledarc($pie, $colR, $colG, $colB);
        imagefill($pie, 0, 0, $pieBG);

        // get the total value for percentage calculations
        $this->total = 0;

        $maxStringLenght = 0;
        foreach ($EQpieData as $i => $value) {
            $this->total += $value[1];
            if (strlen($value[0]) > $maxStringLenght) {
                $maxStringLenght = strlen($value[0]);
            }
        }

        $pieParts = $i + 1;
        reset($EQpieData);
        $legendWidth = (($legend > 0) ? imagefontwidth(2) * ($maxStringLenght + 6) + 40 : 0);

        // the first pie-part starts with offset in degrees up from horizantal right, looks better this way
        $pieStart = 135;

        foreach ($EQpieData as $i => $value) {

            // the name  for each part is $value[0]
            // the value for each part is $value[1]
            // the color for each part is $value[2]

            $piePart = $value[1];
            if (isset($this->total) && $this->total > 0) {
                $piePart100 = round($piePart / $this->total * 100, 2);  // value in percentage, the rounding and * 100 for extra accuracy for pie w/o gaps
            } else {
                $piePart100 = 0;
            }

            $piePart360 = $piePart100 * 3.6;                    // in degrees

            $colR      = hexdec(substr($value[2], 1, 2));
            $colG      = hexdec(substr($value[2], 3, 2));
            $colB      = hexdec(substr($value[2], 5, 2));
            $PartColor = imagefilledarc($pie, $colR, $colG, $colB);

            $ShadowColR = (($colR > 79) ? $colR - 80 : 0);
            $ShadowColG = (($colG > 79) ? $colG - 80 : 0);
            $ShadowColB = (($colB > 79) ? $colB - 80 : 0);

            $ShadowColor = imagefilledarc($pie, $ShadowColR, $ShadowColG, $ShadowColB);

            //Here we create the shadow down-worths
            for ($i = 0; $i < $ShadowDistance; ++$i) {
                imagefilledarc($pie, $pieWidth / 2, $pieHeight / 2 + $i, $pieWidth - 20, $pieHeight - 20, round($pieStart), round($pieStart + $piePart360), $ShadowColor, IMG_ARC_NOFILL);
            }

            $pieStart += $piePart360;
        }
        reset($EQpieData);

        $pieStart = 135;

        foreach ($EQpieData as $i => $value) {
            $piePart = $value[1];
            if (isset($this->total) && $this->total > 0) {
                $piePart100 = round($piePart / $this->total * 100, 2);  // value in percentage, the rounding and * 100 for extra accuracy for pie w/o gaps
            } else {
                $piePart100 = 0;
            }
            $piePart360 = $piePart100 * 3.6;                    // in degrees

            $colR      = hexdec(substr($value[2], 1, 2));
            $colG      = hexdec(substr($value[2], 3, 2));
            $colB      = hexdec(substr($value[2], 5, 2));
            $PartColor = imagefilledarc($pie, $colR, $colG, $colB);

            //Here we create the real pie chart
            imagefilledarc($pie, $pieWidth / 2, $pieHeight / 2, $pieWidth - 20, $pieHeight - 20, round($pieStart), round($pieStart + $piePart360), $PartColor, IMG_ARC_PIE);

            $pieStart += $piePart360;
        }
        reset($EQpieData);

        // create final pie picture with proper background color
        $finalPie = imagecreatetruecolor($pieWidth / 3 + $legendWidth, ($pieHeight + $ShadowDistance) / 3);
        imagefill($finalPie, 0, 0, $pieBG);

        // resample with pieGraph inside (3x smaller)
        imagecopyresampled($finalPie, $pie, 0, 0, 0, 0, $pieWidth / 3, ($pieHeight + $ShadowDistance) / 3, $pieWidth, $pieHeight + $ShadowDistance);

        // Create the ledgend ...
        if ($legendWidth > 0) {
            // Legend Box
            $leg_width   = $legendWidth - 10;
            $leg_height  = $pieParts * (imagefontheight(2) + 2) + 2;
            $legendImage = imagecreatetruecolor($leg_width, $leg_height);
            //ImageFill($legendImage, 0, 0, $pieBG);

            $borderColor = imagefilledarc($pie, '155', '155', '155');
            $boxColor    = imagefilledarc($pie, '255', '255', '255');
            $textColor   = imagefilledarc($pie, '55', '55', '55');

            imagefilledrectangle($legendImage, 0, 0, $leg_width, $leg_height, $boxColor);
            imagerectangle($legendImage, 0, 0, $leg_width - 1, $leg_height - 1, $borderColor);

            $box_width  = imagefontwidth(2) - 5;
            $box_height = imagefontheight(2) - 5;
            $yOffset    = 2;

            foreach ($EQpieData as $i => $value) {
                $piePart = $value[1];
                if (isset($this->total) && $this->total > 0) {
                    $piePart100 = round($piePart / $this->total * 100, 2);  // value in percentage, the rounding and * 100 for extra accuracy for pie w/o gaps
                } else {
                    $piePart100 = 0;
                }
                $colR      = hexdec(substr($value[2], 1, 2));
                $colG      = hexdec(substr($value[2], 3, 2));
                $colB      = hexdec(substr($value[2], 5, 2));
                $PartColor = imagefilledarc($legendImage, $colR, $colG, $colB);

                imagefilledrectangle($legendImage, 5, $yOffset + 2, 5 + $box_width, $yOffset + $box_height + 2, $PartColor);
                imagerectangle($legendImage, 5, $yOffset + 2, 5 + $box_width, $yOffset + $box_height + 2, $borderColor);

                $text = $value[0] . ' ' . $piePart100 . '%';
                imagestring($legendImage, 2, '20', $yOffset, $text, $textColor);
                $yOffset += 15;
            }

            reset($EQpieData); // reset pointer in array to first

            imagecopyresampled($finalPie, $legendImage, $pieWidth / 3, 10, 0, 0, $leg_width, $leg_height, $leg_width, $leg_height);
            imagedestroy($legendImage);
        }
        header('Content-type: image/png');
        imagepng($finalPie, $filename);
        imagedestroy($pie);
        imagedestroy($finalPie);

        return;
    }
}
