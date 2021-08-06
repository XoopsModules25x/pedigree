<?php

namespace XoopsModules\Pedigree\Form;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Module: Pedigree
 *
 * @category        Module
 * @package         pedigree
 * @author          XOOPS Development Team <https://xoops.org>
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         GPL 2.0 or later
 * @link            https://xoops.org/
 * @since           1.0.0
 */

use CriteriaCompo;
use RuntimeException;
use Xmf\Module\Helper\Permission;
use XoopsDatabaseFactory;
use XoopsFormButton;
use XoopsFormElementTray;
use XoopsFormFile;
use XoopsFormHidden;
use XoopsFormLabel;
use XoopsFormSelect;
use XoopsFormSelectUser;
use XoopsFormText;
use XoopsFormTextArea;
use XoopsLists;
use XoopsObjectTree;
use XoopsThemeForm;
use XoopsModules\Pedigree\{
    Helper,
    Utility
};

require_once \dirname(__DIR__, 2) . '/include/common.php';

$moduleDirName = \basename(\dirname(__DIR__, 2));
//$helper = Helper::getInstance();
$permHelper = new Permission();

\xoops_load('XoopsFormLoader');

/**
 * Class RegistryForm
 */
class RegistryForm extends XoopsThemeForm
{
    public $targetObject;

    /**
     * Constructor
     *
     * @param $target
     */
    public function __construct($target)
    {
        //  global $helper;
        $this->helper       = $target->helper;
        $this->targetObject = $target;

        $title = $this->targetObject->isNew() ? \sprintf(\AM_PEDIGREE_REGISTRY_ADD) : \sprintf(AM_PEDIGREE_REGISTRY_EDIT);
        parent::__construct($title, 'form', \xoops_getenv('SCRIPT_NAME'), 'post', true);
        $this->setExtra('enctype="multipart/form-data"');

        //include ID field, it's needed so the module knows if it is a new form or an edited form

        $hidden = new \XoopsFormHidden('id', $this->targetObject->getVar('id'));
        $this->addElement($hidden);
        unset($hidden);

        // Id
        $this->addElement(new \XoopsFormLabel(\AM_PEDIGREE_REGISTRY_ID, $this->targetObject->getVar('id'), 'id'));
        // Pname
        $this->addElement(new \XoopsFormTextArea(\AM_PEDIGREE_REGISTRY_PNAME, 'pname', $this->targetObject->getVar('pname'), 4, 47), false);
        // Id_owner
        $this->addElement(new \XoopsFormText(\AM_PEDIGREE_REGISTRY_ID_OWNER, 'id_owner', 50, 255, $this->targetObject->getVar('id_owner')), false);
        // Id_breeder
        $this->addElement(new \XoopsFormText(\AM_PEDIGREE_REGISTRY_ID_BREEDER, 'id_breeder', 50, 255, $this->targetObject->getVar('id_breeder')), false);
        // User
        $this->addElement(new \XoopsFormSelectUser(\AM_PEDIGREE_REGISTRY_USER, 'user', false, $this->targetObject->getVar('user'), 1, false), false);
        // Roft
        $roft         = new \XoopsFormSelect(\AM_PEDIGREE_REGISTRY_ROFT, 'roft', $this->targetObject->getVar('roft'));
        $optionsArray = Utility::enumerate('pedigree_registry', 'roft');
        if (!\is_array($optionsArray)) {
            throw new RuntimeException($optionsArray . ' must be an array.');
        }
        foreach ($optionsArray as $enum) {
            $roft->addOption($enum, (\defined($enum) ? \constant($enum) : $enum));
        }
        $this->addElement($roft, false);
        // Mother
        require_once XOOPS_ROOT_PATH . '/class/tree.php';
        /** @var \XoopsModules\Pedigree\RegistryHandler $Handler */
        $registryHandler = $this->helper->getHandler('Registry');

        $criteria      = new CriteriaCompo();
        $categoryArray = $registryHandler->getObjects($criteria);
        if ($categoryArray) {
            $categoryTree = new \XoopsObjectTree($categoryArray, 'id', 'pid');

            if (Utility::checkVerXoops($GLOBALS['xoopsModule'], '2.5.9')) {
                $registryPid = $categoryTree->makeSelectElement('pid', 'title', '--', $this->targetObject->getVar('pname'), true, 0, '', \AM_PEDIGREE_REGISTRY_MOTHER);
                $this->addElement($registryPid);
            } else {
                $registryPid = $categoryTree->makeSelBox('pname', 'title', '--', $this->targetObject->getVar('pname', 'e'), true);
                $this->addElement(new \XoopsFormLabel(\AM_PEDIGREE_REGISTRY_MOTHER, $registryPid));
            }
        }
        // Father
        require_once XOOPS_ROOT_PATH . '/class/tree.php';
        /** @var \XoopsModules\Pedigree\RegistryHandler $Handler */
        $registryHandler = $this->helper->getHandler('Registry');

        $criteria      = new CriteriaCompo();
        $categoryArray = $registryHandler->getObjects($criteria);
        if ($categoryArray) {
            $categoryTree = new \XoopsObjectTree($categoryArray, 'id', 'pid');

            if (Utility::checkVerXoops($GLOBALS['xoopsModule'], '2.5.9')) {
                $registryPid = $categoryTree->makeSelectElement('pid', 'title', '--', $this->targetObject->getVar('pname'), true, 0, '', \AM_PEDIGREE_REGISTRY_FATHER);
                $this->addElement($registryPid);
            } else {
                $registryPid = $categoryTree->makeSelBox('pname', 'title', '--', $this->targetObject->getVar('pname', 'e'), true);
                $this->addElement(new \XoopsFormLabel(\AM_PEDIGREE_REGISTRY_FATHER, $registryPid));
            }
        }
        // Foto
        $foto = $this->targetObject->getVar('foto') ?: 'blank.png';

        $uploadDir   = '/uploads/pedigree/images/';
        $imgtray     = new \XoopsFormElementTray(\AM_PEDIGREE_REGISTRY_FOTO, '<br>');
        $imgpath     = \sprintf(\AM_PEDIGREE_FORMIMAGE_PATH, $uploadDir);
        $imageselect = new \XoopsFormSelect($imgpath, 'foto', $foto);
        $imageArray  = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . $uploadDir);
        foreach ($imageArray as $image) {
            $imageselect->addOption((string)$image, $image);
        }
        $imageselect->setExtra("onchange='showImgSelected(\"image_foto\", \"foto\", \"" . $uploadDir . '", "", "' . XOOPS_URL . "\")'");
        $imgtray->addElement($imageselect);
        $imgtray->addElement(new \XoopsFormLabel('', "<br><img src='" . XOOPS_URL . '/' . $uploadDir . '/' . $foto . "' name='image_foto' id='image_foto' alt='' >"));
        $fileseltray = new \XoopsFormElementTray('', '<br>');
        $fileseltray->addElement(new \XoopsFormFile(\AM_PEDIGREE_FORMUPLOAD, 'foto', \xoops_getModuleOption('maxsize')));
        $fileseltray->addElement(new \XoopsFormLabel(''));
        $imgtray->addElement($fileseltray);
        $this->addElement($imgtray);
        // Coi
        $this->addElement(new \XoopsFormText(\AM_PEDIGREE_REGISTRY_COI, 'coi', 50, 255, $this->targetObject->getVar('coi')), false);

        $this->addElement(new \XoopsFormHidden('op', 'save'));
        $this->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    }
}
