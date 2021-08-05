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

use Xmf\Module\Helper\Permission;
use XoopsFormButton;
use XoopsFormHidden;
use XoopsFormLabel;
use XoopsFormText;
use XoopsFormTextArea;
use XoopsModules\Pedigree;
use XoopsThemeForm;






require_once \dirname(__DIR__, 2) . '/include/common.php';

$moduleDirName = \basename(\dirname(__DIR__, 2));
//$helper = Pedigree\Helper::getInstance();
$permHelper = new Permission();

\xoops_load('XoopsFormLoader');

/**
 * Class TrashForm
 */
class TrashForm extends XoopsThemeForm
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

        $title = $this->targetObject->isNew() ? \sprintf(AM_PEDIGREE_TRASH_ADD) : \sprintf(AM_PEDIGREE_TRASH_EDIT);
        parent::__construct($title, 'form', \xoops_getenv('SCRIPT_NAME'), 'post', true);
        $this->setExtra('enctype="multipart/form-data"');

        //include ID field, it's needed so the module knows if it is a new form or an edited form

        $hidden = new XoopsFormHidden('id', $this->targetObject->getVar('id'));
        $this->addElement($hidden);
        unset($hidden);

        // Id
        $this->addElement(new XoopsFormLabel(AM_PEDIGREE_TRASH_ID, $this->targetObject->getVar('id'), 'id'));
        // Pname
        $this->addElement(new XoopsFormTextArea(AM_PEDIGREE_TRASH_PNAME, 'pname', $this->targetObject->getVar('pname'), 4, 47), false);
        // Id_owner
        $this->addElement(new XoopsFormText(AM_PEDIGREE_TRASH_ID_OWNER, 'id_owner', 50, 255, $this->targetObject->getVar('id_owner')), false);
        // Id_breeder
        $this->addElement(new XoopsFormText(AM_PEDIGREE_TRASH_ID_BREEDER, 'id_breeder', 50, 255, $this->targetObject->getVar('id_breeder')), false);
        // User
        $this->addElement(new XoopsFormText(AM_PEDIGREE_TRASH_USER, 'user', 50, 255, $this->targetObject->getVar('user')), false);
        // Roft
        $this->addElement(new XoopsFormText(AM_PEDIGREE_TRASH_ROFT, 'roft', 50, 255, $this->targetObject->getVar('roft')), false);
        // Mother
        $this->addElement(new XoopsFormText(AM_PEDIGREE_TRASH_MOTHER, 'mother', 50, 255, $this->targetObject->getVar('mother')), false);
        // Father
        $this->addElement(new XoopsFormText(AM_PEDIGREE_TRASH_FATHER, 'father', 50, 255, $this->targetObject->getVar('father')), false);
        // Foto
        $this->addElement(new XoopsFormText(AM_PEDIGREE_TRASH_FOTO, 'foto', 50, 255, $this->targetObject->getVar('foto')), false);
        // Coi
        $this->addElement(new XoopsFormText(AM_PEDIGREE_TRASH_COI, 'coi', 50, 255, $this->targetObject->getVar('coi')), false);

        $this->addElement(new XoopsFormHidden('op', 'save'));
        $this->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    }
}
