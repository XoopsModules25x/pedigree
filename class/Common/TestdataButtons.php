<?php

declare(strict_types=1);

namespace XoopsModules\Pedigree\Common;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 *
 * @package        \XoopsModules\Pedigreee
 * @author         XOOPS Development Team <https://xoops.org>
 * @copyright      {@link https://xoops.org/ XOOPS Project}
 * @license        GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 */

use Xmf\{
    Module\Admin,
    Request,
    Yaml
};
use XoopsModules\Pedigree\{
    Constants,
    Helper
};

/** @var Helper $helper */

/**
 * Class TestdataButtons
 *
 * Contains methods for to create the Test buttons and change their visibility
 */
class TestdataButtons
{
    /** Button status constants */
    private const SHOW_BUTTONS = 1;
    private const HIDE_BUTTONS = 0;

    /**
     * Load the test button configuration
     *
     * @param \Xmf\Module\Admin $adminObject
     *
     * @return void
     */
    public static function loadButtonConfig(Admin $adminObject): void
    {
        $moduleDirName       = \basename(\dirname(__DIR__, 2));
        $moduleDirNameUpper  = mb_strtoupper($moduleDirName);
        $helper              = Helper::getInstance();
        $yamlFile            = $helper->path('/config/admin.yml');
        $config              = Yaml::readWrapped($yamlFile); // work with phpmyadmin YAML dumps
        $displaySampleButton = $config['displaySampleButton'];

        if (self::SHOW_BUTTONS == $displaySampleButton) {
            xoops_loadLanguage('admin/modulesadmin', 'system');
            $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'LOAD_SAMPLEDATA'), $helper->url('testdata/index.php?op=load'), 'add');
            $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'SAVE_SAMPLEDATA'), $helper->url('testdata/index.php?op=save'), 'add');
            $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'CLEAR_SAMPLEDATA'), $helper->url('testdata/index.php?op=clear'), 'alert');
            //    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA'), $helper->url( 'testdata/index.php?op=exportschema'), 'add');
            $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'HIDE_SAMPLEDATA_BUTTONS'), '?op=hide_buttons', 'delete');
        } else {
            $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLEDATA_BUTTONS'), '?op=show_buttons', 'add');
            // $displaySampleButton = $config['displaySampleButton'];
        }
    }

    /**
     * Hide the test buttons
     *
     * @return void
     */
    public static function hideButtons(): void
    {
        $helper                     = Helper::getInstance();
        $yamlFile                   = $helper->path('config/admin.yml');
        $app                        = [];
        $app['displaySampleButton'] = self::HIDE_BUTTONS;
        Yaml::save($app, $yamlFile);
        $helper->redirect('admin/index.php', Constants::REDIRECT_DELAY_NONE, '');
    }

    /**
     * Show the test buttons
     *
     * @return void
     */
    public static function showButtons(): void
    {
        $helper                     = Helper::getInstance();
        $yamlFile                   = $helper->path('config/admin.yml');
        $app                        = [];
        $app['displaySampleButton'] = self::SHOW_BUTTONS;
        Yaml::save($app, $yamlFile);
        $helper->redirect('admin/index.php', Constants::REDIRECT_DELAY_NONE, '');
    }
}