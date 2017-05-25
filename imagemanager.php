<?php
//
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                  Copyright (c) 2000-2016 XOOPS.org                        //
//                       <http://xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
if (!isset($HTTP_GET_VARS['target']) && !isset($HTTP_POST_VARS['target'])) {
    exit();
}
$op = 'list';
if (isset($HTTP_GET_VARS['op']) && $HTTP_GET_VARS['op'] === 'upload') {
    $op = 'upload';
}
if (isset($HTTP_POST_VARS)) {
    foreach ($HTTP_POST_VARS as $k => $v) {
        ${$k} = $v;
    }
}
if (!$xoopsUser) {
    $group = array(XOOPS_GROUP_ANONYMOUS);
} else {
    $group = $xoopsUser->getGroups();
}
if ($op === 'list') {
    require_once XOOPS_ROOT_PATH . '/class/template.php';
    $xoopsTpl = new XoopsTpl();
    $xoopsTpl->assign('lang_imgmanager', _IMGMANAGER);
    $xoopsTpl->assign('sitename', $xoopsConfig['sitename']);
    $target = htmlspecialchars($HTTP_GET_VARS['target'], ENT_QUOTES);
    $xoopsTpl->assign('target', $target);
    $imgcatHandler = xoops_getHandler('imagecategory');
    $catlist       = $imgcatHandler->getList($group, 'imgcat_read', 1);
    $catcount      = count($catlist);
    $xoopsTpl->assign('lang_align', _ALIGN);
    $xoopsTpl->assign('lang_add', _ADD);
    $xoopsTpl->assign('lang_close', _CLOSE);
    if ($catcount > 0) {
        $xoopsTpl->assign('lang_go', _GO);
        $catshow = !isset($HTTP_GET_VARS['cat_id']) ? 0 : (int)$HTTP_GET_VARS['cat_id'];
        $catshow = (!empty($catshow) && in_array($catshow, array_keys($catlist))) ? $catshow : 0;
        $xoopsTpl->assign('show_cat', $catshow);
        if ($catshow > 0) {
            $xoopsTpl->assign('lang_addimage', _ADDIMAGE);
        }
        $catlist     = array('0' => '--') + $catlist;
        $cat_options = '';
        foreach ($catlist as $c_id => $c_name) {
            $sel = '';
            if ($c_id == $catshow) {
                $sel = ' selected="selected"';
            }
            $cat_options .= '<option value="' . $c_id . '"' . $sel . '>' . $c_name . '</option>';
        }
        $xoopsTpl->assign('cat_options', $cat_options);
        if ($catshow > 0) {
            $imageHandler = xoops_getHandler('image');
            $criteria     = new CriteriaCompo(new Criteria('imgcat_id', $catshow));
            $criteria->add(new Criteria('image_display', 1));
            $total = $imageHandler->getCount($criteria);
            if ($total > 0) {
                $imgcatHandler = xoops_getHandler('imagecategory');
                $imgcat        = $imgcatHandler->get($catshow);
                $xoopsTpl->assign('image_total', $total);
                $xoopsTpl->assign('lang_image', _IMAGE);
                $xoopsTpl->assign('lang_imagename', _IMAGENAME);
                $xoopsTpl->assign('lang_imagemime', _IMAGEMIME);
                $start = isset($HTTP_GET_VARS['start']) ? (int)$HTTP_GET_VARS['start'] : 0;
                $criteria->setLimit(10);
                $criteria->setStart($start);
                $storetype = $imgcat->getVar('imgcat_storetype');
                if ($storetype === 'db') {
                    $images = $imageHandler->getObjects($criteria, false, true);
                } else {
                    $images = $imageHandler->getObjects($criteria, false, false);
                }
                $imgcount = count($images);
                $max      = ($imgcount > 10) ? 10 : $imgcount;

                for ($i = 0; $i < $max; ++$i) {
                    if ($storetype === 'db') {
                        $lcode = '[img align=left id=' . $images[$i]->getVar('image_id') . ']' . $images[$i]->getVar('image_nicename') . '[/img]';
                        $code  = '[img id=' . $images[$i]->getVar('image_id') . ']' . $images[$i]->getVar('image_nicename') . '[/img]';
                        $rcode = '[img align=right id=' . $images[$i]->getVar('image_id') . ']' . $images[$i]->getVar('image_nicename') . '[/img]';
                        $src   = XOOPS_URL . '/image.php?id=' . $images[$i]->getVar('image_id');
                    } else {
                        $lcode = '[img align=left]' . XOOPS_UPLOAD_URL . '/' . $images[$i]->getVar('image_name') . '[/img]';
                        $code  = '[img]' . XOOPS_UPLOAD_URL . '/' . $images[$i]->getVar('image_name') . '[/img]';
                        $rcode = '[img align=right]' . XOOPS_UPLOAD_URL . '/' . $images[$i]->getVar('image_name') . '[/img]';
                        $src   = XOOPS_UPLOAD_URL . '/' . $images[$i]->getVar('image_name');
                    }
                    $xoopsTpl->append('images', array(
                        'id'       => $images[$i]->getVar('image_id'),
                        'nicename' => $images[$i]->getVar('image_nicename'),
                        'mimetype' => $images[$i]->getVar('image_mimetype'),
                        'src'      => $src,
                        'lxcode'   => $lcode,
                        'xcode'    => $code,
                        'rxcode'   => $rcode
                    ));
                }
                if ($total > 10) {
                    include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
                    $nav = new XoopsPageNav($total, 10, $start, 'start', 'target=' . $target . '&amp;cat_id=' . $catshow);
                    $xoopsTpl->assign('pagenav', $nav->renderNav());
                }
            } else {
                $xoopsTpl->assign('image_total', 0);
            }
        }
        $xoopsTpl->assign('xsize', 600);
        $xoopsTpl->assign('ysize', 400);
    } else {
        $xoopsTpl->assign('xsize', 400);
        $xoopsTpl->assign('ysize', 180);
    }
    $xoopsTpl->display('db:system_imagemanager.html');
    exit();
}

if ($op === 'upload') {
    $imgcatHandler = xoops_getHandler('imagecategory');
    $imgcat_id     = (int)$HTTP_GET_VARS['imgcat_id'];
    $imgcat        = $imgcatHandler->get($imgcat_id);
    $error         = false;
    if (!is_object($imgcat)) {
        $error = true;
    } else {
        $imgcatpermHandler = xoops_getHandler('groupperm');
        if ($xoopsUser) {
            if (!$imgcatpermHandler->checkRight('imgcat_write', $imgcat_id, $xoopsUser->getGroups())) {
                $error = true;
            }
        } else {
            if (!$imgcatpermHandler->checkRight('imgcat_write', $imgcat_id, XOOPS_GROUP_ANONYMOUS)) {
                $error = true;
            }
        }
    }
    if ($error != false) {
        xoops_header(false);
        echo '</head><body><div style="text-align:center;"><input value="' . _BACK . '" type="button" onclick="history.go(-1);" /></div>';
        xoops_footer();
        exit();
    }
    require_once XOOPS_ROOT_PATH . '/class/template.php';
    $xoopsTpl = new XoopsTpl();
    $xoopsTpl->assign('show_cat', $imgcat_id);
    $xoopsTpl->assign('lang_imgmanager', _IMGMANAGER);
    $xoopsTpl->assign('sitename', $xoopsConfig['sitename']);
    $xoopsTpl->assign('target', htmlspecialchars($HTTP_GET_VARS['target'], ENT_QUOTES));
    include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $form = new XoopsThemeForm('', 'image_form', 'imagemanager.php');
    $form->setExtra('enctype="multipart/form-data"');
    $form->addElement(new XoopsFormText(_IMAGENAME, 'image_nicename', 20, 255), true);
    $form->addElement(new XoopsFormLabel(_IMAGECAT, $imgcat->getVar('imgcat_name')));
    $form->addElement(new XoopsFormFile(_IMAGEFILE, 'image_file', $imgcat->getVar('imgcat_maxsize')), true);
    $form->addElement(new XoopsFormLabel(_IMGMAXSIZE, $imgcat->getVar('imgcat_maxsize')));
    $form->addElement(new XoopsFormLabel(_IMGMAXWIDTH, $imgcat->getVar('imgcat_maxwidth')));
    $form->addElement(new XoopsFormLabel(_IMGMAXHEIGHT, $imgcat->getVar('imgcat_maxheight')));
    $form->addElement(new XoopsFormHidden('imgcat_id', $imgcat_id));
    $form->addElement(new XoopsFormHidden('op', 'doupload'));
    $form->addElement(new XoopsFormHidden('target', $target));
    $form->addElement(new XoopsFormButton('', 'img_button', _SUBMIT, 'submit'));
    $form->assign($xoopsTpl);
    $xoopsTpl->assign('lang_close', _CLOSE);
    $xoopsTpl->display('db:system_imagemanager2.html');
    exit();
}

if ($op === 'doupload') {
    include_once XOOPS_ROOT_PATH . '/class/uploader.php';
    $imgcatHandler = xoops_getHandler('imagecategory');
    $imgcat        = $imgcatHandler->get((int)$imgcat_id);
    $error         = false;
    if (!is_object($imgcat)) {
        $error = true;
    } else {
        $imgcatpermHandler = xoops_getHandler('groupperm');
        if ($xoopsUser) {
            if (!$imgcatpermHandler->checkRight('imgcat_write', $imgcat_id, $xoopsUser->getGroups())) {
                $error = true;
            }
        } else {
            if (!$imgcatpermHandler->checkRight('imgcat_write', $imgcat_id, XOOPS_GROUP_ANONYMOUS)) {
                $error = true;
            }
        }
    }
    if ($error != false) {
        xoops_header(false);
        echo '</head><body><div style="text-align:center;"><input value="' . _BACK . '" type="button" onclick="history.go(-1);" /></div>';
        xoops_footer();
        exit();
    }
    $uploader = new XoopsMediaUploader(XOOPS_UPLOAD_PATH, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'), $imgcat->getVar('imgcat_maxsize'), $imgcat->getVar('imgcat_maxwidth'), $imgcat->getVar('imgcat_maxheight'));
    $uploader->setPrefix('img');
    if ($uploader->fetchMedia($HTTP_POST_VARS['xoops_upload_file'][0])) {
        if (!$uploader->upload()) {
            $err = $uploader->getErrors();
        } else {
            $imageHandler = xoops_getHandler('image');
            $image        = $imageHandler->create();
            $image->setVar('image_name', $uploader->getSavedFileName());
            $image->setVar('image_nicename', $image_nicename);
            $image->setVar('image_mimetype', $uploader->getMediaType());
            $image->setVar('image_created', time());
            $image->setVar('image_display', 1);
            $image->setVar('image_weight', 0);
            $image->setVar('imgcat_id', $imgcat_id);
            if ($imgcat->getVar('imgcat_storetype') === 'db') {
                $fp      = @fopen($uploader->getSavedDestination(), 'rb');
                $fbinary = @fread($fp, filesize($uploader->getSavedDestination()));
                @fclose($fp);
                $image->setVar('image_body', addslashes($fbinary));
                @unlink($uploader->getSavedDestination());
            }
            if (!$imageHandler->insert($image)) {
                $err = sprintf(_FAILSAVEIMG, $image->getVar('image_nicename'));
            }
        }
    } else {
        $err = _FAILFETCHIMG;
    }
    if (isset($err)) {
        xoops_header(false);
        xoops_error($err);
        echo '</head><body><div style="text-align:center;"><input value="' . _BACK . '" type="button" onclick="history.go(-1);" /></div>';
        xoops_footer();
        exit();
    }
    header('location: imagemanager.php?cat_id=' . $imgcat_id . '&target=' . $target);
}
