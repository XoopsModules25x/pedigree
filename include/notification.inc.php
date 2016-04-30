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

/**
 * @param $category
 * @param $item_id
 *
 * @return mixed
 */

function lookup($category, $item_id)
{
    global $xoopsModule, $xoopsDB, $xoopsModuleConfig, $xoopsConfig;
    $moduleDirName = basename(dirname(__DIR__));
    $item_id = (int)$item_id;

    switch (strtolower($category)) {
        case 'global':
            $item['name'] = '';
            $item['url']  = '';
            break;

        case 'dog':
            // Assume we have a valid forum id
            $sql = 'SELECT NAAM FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_tree') . ' WHERE Id = ' . $item_id;
            if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
                redirect_header('index.php', 2, _MD_ERRORFORUM);
            }
            $result_array = $GLOBALS['xoopsDB']->fetchArray($result);
            $item['name'] = $result_array['NAAM'];
            $item['url']  = XOOPS_URL . "/modules/{$moduleDirName}/dog.php?Id={$item_id}";
            break;

        case 'thread':
            // Assume we have a valid topid id
            $sql = 'SELECT t.topic_title,f.forum_id,f.forum_name FROM ' . $GLOBALS['xoopsDB']->prefix('bb_topics') . ' t, ' . $GLOBALS['xoopsDB']->prefix('bb_forums') . ' f WHERE t.forum_id = f.forum_id AND t.topic_id = ' . $item_id . ' limit 1';
            if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
                redirect_header('index.php', 2, _MD_ERROROCCURED);
            }
            $result_array = $GLOBALS['xoopsDB']->fetchArray($result);
            $item['name'] = $result_array['topic_title'];
            $item['url']  = XOOPS_URL . "/modules/{$moduleDirName}/viewtopic.php?forum=" . $result_array['forum_id'] . "&topic_id={$item_id}";
            break;

        case 'post':
            // Assume we have a valid post id
            $sql = 'SELECT subject,topic_id,forum_id FROM ' . $GLOBALS['xoopsDB']->prefix('bb_posts') . ' WHERE post_id = ' . $item_id . ' LIMIT 1';
            if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
                redirect_header('index.php', 2, _MD_ERROROCCURED);
            }
            $result_array = $GLOBALS['xoopsDB']->fetchArray($result);
            $item['name'] = $result_array['subject'];
            $item['url']  = XOOPS_URL . "/modules/{$moduleDirName}/viewtopic.php?forum=" . $result_array['forum_id'] . '&amp;topic_id=' . $result_array['topic_id'] . "#forumpost{$item_id}";
            break;
    }

    return $item;
}
