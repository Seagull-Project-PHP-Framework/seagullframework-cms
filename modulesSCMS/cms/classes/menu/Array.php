<?php
/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Seagull 0.6                                                               |
// +---------------------------------------------------------------------------+
// | Array.php                                                             |
// +---------------------------------------------------------------------------+
// | Copyright (c) 2006 Demian Turner                                          |
// |                                                                           |
// | Author: Demian Turner <demian@phpkitchen.com>                             |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | This library is free software; you can redistribute it and/or             |
// | modify it under the terms of the GNU Library General Public               |
// | License as published by the Free Software Foundation; either              |
// | version 2 of the License, or (at your option) any later version.          |
// |                                                                           |
// | This library is distributed in the hope that it will be useful,           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU         |
// | Library General Public License for more details.                          |
// |                                                                           |
// | You should have received a copy of the GNU Library General Public         |
// | License along with this library; if not, write to the Free                |
// | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA |
// |                                                                           |
// +---------------------------------------------------------------------------+
//
require_once SGL_CORE_DIR . '/Category.php';

/**
 * Creates a select menu from db category structure.
 *
 * @package seagull
 * @subpackage cms
 * @author  Demian Turner <demian@phpkitchen.com>
 */
class Menu_Array extends CmsCategory
{
    function Menu_Array($options, $conf)
    {
        SGL::logMessage(null, PEAR_LOG_DEBUG);
        parent::SGL_Category();
        $this->conf = $conf;
    }

    function render($id = 0)
    {
        //  iterate through whole DB resultset, return array
        $result = $this->getChildren($id);
        foreach ($result as $k => $aValue) {
            if ($this->isBranch($aValue['category_id'])) {
                $result[$k]['children'] = $this->render($aValue['category_id']);
            }
        }
        return $result;
    }
}
?>