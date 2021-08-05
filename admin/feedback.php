<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Feedback plugin for xoops modules
 *
 * @copyright      XOOPS Project  (https://xoops.org)
 * @license        GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author         Michael Beck <mambax7@gmailc.com>
 * @author         Wedega - Email:<webmaster@wedega.com>
 * @author         Fernando Santos (topet05) <fernando@mastop.com.br>
 */
use Xmf\Request;
use XoopsModules\Pedigree;
use XoopsModules\Pedigree\Constants;

require __DIR__ . '/admin_header.php';

/**
 * @var XoopsModules\Pedigree\Helper $helper
 * @var \Xmf\Module\Admin $adminObject
 */

$feedback = XoopsModules\Pedigree\Common\ModuleFeedback::getInstance();

// It recovered the value of argument op in URL$
$op = Request::getCmd('op', 'list');
$moduleDirNameUpper = mb_strtoupper($moduleDirName);
$helper->loadLanguage('common');

xoops_cp_header();

switch ($op) {
    case 'list':
    default:
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('feedback.php'));
        $feedback->name = $GLOBALS['xoopsUser']->getVar('name');
        $feedback->email = $GLOBALS['xoopsUser']->getVar('email');
        $feedback->site = XOOPS_URL;
        $form = $feedback->getFormFeedback();
        echo $form->display();
        break;
    case 'send':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            $helper->redirect('admin/index.php', Constants::REDIRECT_DELAY_MEDIUM, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }

        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('feedback.php'));

        $your_name = Request::getString('your_name', '');
        $your_site = Request::getUrl('your_site', '');
        $your_mail = Request::getEmail('your_mail', '');
        $fb_type = Request::getString('fb_type', '');
        $fb_content = Request::getText('fb_content', '');
        $fb_content = str_replace(["\r\n", "\n", "\r"], '<br>', $fb_content); //clean line break from dhtmltextarea

        $title = constant('CO_' . $moduleDirNameUpper . '_' . 'FB_SEND_FOR') . $GLOBALS['xoopsModule']->getVar('dirname');
        $body = constant('CO_' . $moduleDirNameUpper . '_' . 'FB_NAME') . ': ' . $your_name . '<br>';
        $body .= constant('CO_' . $moduleDirNameUpper . '_' . 'FB_MAIL') . ': ' . $your_mail . '<br>';
        $body .= constant('CO_' . $moduleDirNameUpper . '_' . 'FB_SITE') . ': ' . $your_site . '<br>';
        $body .= constant('CO_' . $moduleDirNameUpper . '_' . 'FB_TYPE') . ': ' . $fb_type . '<br><br>';
        $body .= constant('CO_' . $moduleDirNameUpper . '_' . 'FB_TYPE_CONTENT') . ':<br>';
        $body .= $fb_content;
        $xoopsMailer = xoops_getMailer();
        $xoopsMailer->useMail();
        $xoopsMailer->setToEmails($GLOBALS['xoopsModule']->getInfo('author_mail'));
        $xoopsMailer->setFromEmail($your_mail);
        $xoopsMailer->setFromName($your_name);
        $xoopsMailer->setSubject($title);
        $xoopsMailer->multimailer->isHTML(true);
        $xoopsMailer->setBody($body);
        $ret = $xoopsMailer->send();
        if ($ret) {
            $helper->redirect('admin/index.php', Constants::REDIRECT_DELAY_MEDIUM, constant('CO_' . $moduleDirNameUpper . '_' . 'FB_SEND_SUCCESS'));
        }

        // show form with content again
        $feedback->name = $your_name;
        $feedback->email = $your_mail;
        $feedback->site = $your_site;
        $feedback->type = $fb_type;
        $feedback->content = $fb_content;
        echo '<div class="center width80" style="padding: 10px; border: 2px solid #ff0000; color: #ff0000; margin-right:auto;margin-left:auto;">
            <h3>' . constant('CO_' . $moduleDirNameUpper . '_' . 'FB_SEND_ERROR') . '</h3>
            </div>';
        $form = $feedback->getFormFeedback();
        echo $form->display();
        break;
}
require __DIR__ . '/admin_footer.php';
