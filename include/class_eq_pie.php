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
    function eq_pie()
    {
    }

    /**
     * @param $number
     *
     * @return mixed
     */
    function GetColor($number)
    {
        $color = array(
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
        );

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
    function MakePie($filename, $pieWidth, $pieHeight, $ShadowDistance, $pieBackgroundColor, $EQpieData, $legend)
    {
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

        $pieWidth           = $pieWidth * 3;
        $pieHeight          = $pieHeight * 3;
        $ShadowDistance     = $ShadowDistance * 3;
        $pieBackgroundColor = $pieBackgroundColor;

        $pie = @ImageCreateTrueColor($pieWidth, $pieHeight + $ShadowDistance);

        $colR  = hexdec(substr($pieBackgroundColor, 1, 2));
        $colG  = hexdec(substr($pieBackgroundColor, 3, 2));
        $colB  = hexdec(substr($pieBackgroundColor, 5, 2));
        $pieBG = ImageColorAllocate($pie, $colR, $colG, $colB);
        ImageFill($pie, 0, 0, $pieBG);

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
        $legendWidth = (($legend > 0) ? ImageFontWidth(2) * ($maxStringLenght + 6) + 40 : 0);

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
            $PartColor = ImageColorAllocate($pie, $colR, $colG, $colB);

            $ShadowColR = (($colR > 79) ? $colR - 80 : 0);
            $ShadowColG = (($colG > 79) ? $colG - 80 : 0);
            $ShadowColB = (($colB > 79) ? $colB - 80 : 0);

            $ShadowColor = ImageColorAllocate($pie, $ShadowColR, $ShadowColG, $ShadowColB);

            //Here we create the shadow down-worths
            for ($i = 0; $i < $ShadowDistance; ++$i) {
                ImageFilledArc($pie, $pieWidth / 2, $pieHeight / 2 + $i, $pieWidth - 20, $pieHeight - 20, round($pieStart), round($pieStart + $piePart360), $ShadowColor, IMG_ARC_NOFILL);
            }

            $pieStart = $pieStart + $piePart360;

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
            $PartColor = ImageColorAllocate($pie, $colR, $colG, $colB);

            //Here we create the real pie chart
            ImageFilledArc($pie, $pieWidth / 2, $pieHeight / 2, $pieWidth - 20, $pieHeight - 20, round($pieStart), round($pieStart + $piePart360), $PartColor, IMG_ARC_PIE);

            $pieStart = $pieStart + $piePart360;

        }
        reset($EQpieData);

        // create final pie picture with proper background color
        $finalPie = ImageCreateTrueColor($pieWidth / 3 + $legendWidth, ($pieHeight + $ShadowDistance) / 3);
        ImageFill($finalPie, 0, 0, $pieBG);

        // resample with pieGraph inside (3x smaller)
        ImageCopyResampled($finalPie, $pie, 0, 0, 0, 0, $pieWidth / 3, ($pieHeight + $ShadowDistance) / 3, $pieWidth, $pieHeight + $ShadowDistance);

        // Create the ledgend ...
        if ($legendWidth > 0) {
            // Legend Box
            $leg_width   = $legendWidth - 10;
            $leg_height  = $pieParts * (ImageFontHeight(2) + 2) + 2;
            $legendImage = ImageCreateTrueColor($leg_width, $leg_height);
            //ImageFill($legendImage, 0, 0, $pieBG);

            $borderColor = ImageColorAllocate($pie, '155', '155', '155');
            $boxColor    = ImageColorAllocate($pie, '255', '255', '255');
            $textColor   = ImageColorAllocate($pie, '55', '55', '55');

            ImageFilledRectangle($legendImage, 0, 0, $leg_width, $leg_height, $boxColor);
            ImageRectangle($legendImage, 0, 0, $leg_width - 1, $leg_height - 1, $borderColor);

            $box_width  = ImageFontHeight(2) - 5;
            $box_height = ImageFontHeight(2) - 5;
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
                $PartColor = ImageColorAllocate($legendImage, $colR, $colG, $colB);

                ImageFilledRectangle($legendImage, 5, $yOffset + 2, 5 + $box_width, $yOffset + $box_height + 2, $PartColor);
                ImageRectangle($legendImage, 5, $yOffset + 2, 5 + $box_width, $yOffset + $box_height + 2, $borderColor);

                $text = $value[0] . ' ' . $piePart100 . '%';
                ImageString($legendImage, 2, '20', $yOffset, $text, $textColor);
                $yOffset = $yOffset + 15;

            }

            reset($EQpieData); // reset pointer in array to first

            ImageCopyResampled($finalPie, $legendImage, $pieWidth / 3, 10, 0, 0, $leg_width, $leg_height, $leg_width, $leg_height);
            ImageDestroy($legendImage);

        }
        header('Content-type: image/png');
        imagepng($finalPie, $filename);
        ImageDestroy($pie);
        ImageDestroy($finalPie);

        return;
    }

}
