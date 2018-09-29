<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright      {@link https://xoops.org/ XOOPS Project}
 * @license        {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author         XOOPS Development Team
 */

/**
 * @param $category
 * @param $item_id
 *
 * @return mixed
 */

function lookup($category, $item_id)
{
    global $xoopsModule, $xoopsModuleConfig, $xoopsConfig;
    $moduleDirName = basename(dirname(__DIR__));
    if (empty($xoopsModule) || $xoopsModule->getVar('dirname') !== $moduleDirName) {
        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->getByDirname($moduleDirName);
        $configHandler = xoops_getHandler('config');
        $config        = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
    } else {
        $module = $xoopsModule;
        $config = $xoopsModuleConfig;
    }

    if ('global' === $category) {
        $item['name'] = '';
        $item['url']  = '';

        return $item;
    }
    $item_id = (int)$item_id;

    global $xoopsDB;
    if ('dog' === $category) {
        // Assume we have a valid forum id
        $sql = 'SELECT pname FROM ' . $GLOBALS['xoopsDB']->prefix('pedigree_registry') . ' WHERE id = ' . $item_id;
        if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
            redirect_header('index.php', 2, _MD_ERRORFORUM);
        }
        $result_array = $GLOBALS['xoopsDB']->fetchArray($result);
        $item['name'] = $result_array['pname'];
        $item['url']  = XOOPS_URL . '/modules/' . $module->getVar('dirname') . '/dog.php?id=' . $item_id;

        return $item;
    }

    if ('thread' === $category) {
        // Assume we have a valid topid id
        $sql = 'SELECT t.topic_title,f.forum_id,f.forum_name FROM ' . $GLOBALS['xoopsDB']->prefix('bb_topics') . ' t, ' . $GLOBALS['xoopsDB']->prefix('bb_forums') . ' f WHERE t.forum_id = f.forum_id AND t.topic_id = ' . $item_id . ' LIMIT 1';
        if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
            redirect_header('index.php', 2, _MD_ERROROCCURED);
        }
        $result_array = $GLOBALS['xoopsDB']->fetchArray($result);
        $item['name'] = $result_array['topic_title'];
        $item['url']  = XOOPS_URL . '/modules/' . $module->getVar('dirname') . '/viewtopic.php?forum=' . $result_array['forum_id'] . '&topic_id=' . $item_id;

        return $item;
    }

    if ('post' === $category) {
        // Assume we have a valid post id
        $sql = 'SELECT subject,topic_id,forum_id FROM ' . $GLOBALS['xoopsDB']->prefix('bb_posts') . ' WHERE post_id = ' . $item_id . ' LIMIT 1';
        if (!$result = $GLOBALS['xoopsDB']->query($sql)) {
            redirect_header('index.php', 2, _MD_ERROROCCURED);
        }
        $result_array = $GLOBALS['xoopsDB']->fetchArray($result);
        $item['name'] = $result_array['subject'];
        $item['url']  = XOOPS_URL . '/modules/' . $module->getVar('dirname') . '/viewtopic.php?forum= ' . $result_array['forum_id'] . '&amp;topic_id=' . $result_array['topic_id'] . '#forumpost' . $item_id;

        return $item;
    }
}
