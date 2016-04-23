<?php

/**
 * Created by PhpStorm.
 * User: Mamba
 * Date: 2014-11-19
 * Time: 3:05
 */
class pedigreeUtilities
{

    /**
     * Function responsible for checking if a directory exists, we can also write in and create an index.html file
     *
     * @param string $folder Le chemin complet du répertoire à vérifier
     *
     * @return void
     */
    public static function prepareFolder($folder)
    {
        if (!is_dir($folder)) {
            mkdir($folder, 0777);
            file_put_contents($folder . '/index.html', '<script>history.go(-1);</script>');
        }
        chmod($folder, 0777);
    }
}
