<?php
/* ------------------------------------------------------------------------
      pedigree
          Copyright 2004, James Cotton
           http://www.dobermannvereniging.nl
      Template
          Copyright 2004 Thomas Hill
          <a href="http://www.worldware.com">worldware.com</a>
   ------------------------------------------------------------------------
   ------------------------------------------------------------------------
   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   You may not change or alter any portion of this comment or credits
   of supporting developers from this source code or any supporting
   source code which is considered copyrighted (c) material of the
   original comment or credit authors.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
   ------------------------------------------------------------------------
*/

/**
 * @package         XoopsModules\Pedigree
 * @copyright       Copyright 2004, James Cotton <https://www.dobermannvereniging.nl>
 * @copyright       Copyright 2004, Thomas Hill <https://www.worldware.com>
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @author          XOOPS Module Dev Team
 */

use XoopsModules\Pedigree\{
    Helper
};

require_once \dirname(__DIR__, 3) . '/include/cp_header.php';
$helper = Helper::getInstance();
$helper->loadLanguage('modinfo');
require_once $helper->path('admin/menu.php');
