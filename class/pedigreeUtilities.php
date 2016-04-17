<?php

/**
 * Created by PhpStorm.
 * User: Mamba
 * Date: 2014-11-19
 * Time: 3:05
 */
class PedigreeUtilities
{

    /**
     * Function responsible for checking if a directory exists, we can also write in and create an index.html file
     *
     * @param string $folder The full path of the directory to check
     *
     * @return void
     */
    public static function prepareFolder($folder)
    {
//        $filteredFolder = XoopsFilterInput::clean($folder, 'PATH');
        if (!is_dir($folder)) {
            mkdir($folder);
            file_put_contents($folder . '/index.html', '<script>history.go(-1);</script>');
        }
//        chmod($filteredFolder, 0777);
    }
}
