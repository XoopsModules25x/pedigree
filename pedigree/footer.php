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
 * Wfdownloads module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         wfdownload
 * @since           3.23
 * @author          Xoops Development Team
 * @version         svn:$id$
 */
$uid = is_object($xoopsUser) ? (int)$xoopsUser->getVar('uid') : 0;
/*
$xoopsTpl->assign("wfdownloads_adminpage", "<a href='" . PEDIGREE_URL . "/admin/index.php'>" . _MD_PEDIGREE_ADMIN_PAGE . "</a>");

$xoopsTpl->assign("isAdmin", $wfdownloads_isAdmin);
$xoopsTpl->assign('wfdownloads_url', PEDIGREE_URL . '/');

$xoopsTpl->assign(
    "ref_smartfactory",
    "WFDownloads is developed by The SmartFactory (http://www.smartfactory.ca), a division of InBox Solutions (http://www.inboxsolutions.net)"
);
*/
include_once XOOPS_ROOT_PATH . '/footer.php';

?>
<script type="text/javascript">

    $('.magnific_zoom').magnificPopup({
        type               : 'image',
        image              : {
            cursor     : 'mfp-zoom-out-cur',
            titleSrc   : "title",
            verticalFit: true,
            tError     : 'The image could not be loaded.' // Error message
        },
        iframe             : {
            patterns: {
                youtube : {
                    index: 'youtube.com/',
                    id   : 'v=',
                    src  : '//www.youtube.com/embed/%id%?autoplay=1'
                }, vimeo: {
                    index: 'vimeo.com/',
                    id   : '/',
                    src  : '//player.vimeo.com/video/%id%?autoplay=1'
                }, gmaps: {
                    index: '//maps.google.',
                    src  : '%id%&output=embed'
                }
            }
        },
        preloader          : true,
        showCloseBtn       : true,
        closeBtnInside     : false,
        closeOnContentClick: true,
        closeOnBgClick     : true,
        enableEscapeKey    : true,
        modal              : false,
        alignTop           : false,
        mainClass          : 'mfp-img-mobile mfp-fade',
        zoom               : {
            enabled : true,
            duration: 300,
            easing  : 'ease-in-out'
        },
        removalDelay       : 200
    });
</script>
