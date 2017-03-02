<?php

class SGL_Task_RebuildRouteCache extends SGL_Task
{
    public function run($data = array())
    {
        $aRoutes = array();

        // get routes from db, highest priority
        $aRoutes += self::_getDbRoutes();

        // get default routes
        $aRoutes += SGL_Routes::read(SGL_ETC_DIR . '/routes.php.dist');

        SGL_Routes::write($aRoutes, SGL_VAR_DIR . '/routes.php');
    }

    protected function _getDbRoutes()
    {
        $aReturn = array();

        $dbh = SGL_DB::singleton();

        $query = "
                SELECT
                    r.*
                FROM `" . SGL_Config::get('table.route') . "` AS r
                WHERE r.is_active = 1
        ";

        $res = $dbh->getAll($query);

        foreach ($res as $rawRoute) {
        	$route = new SGL_Routes_Route();
        	$route->setFromRouteArray(unserialize($rawRoute->route_data));

        	if ($route->isValid()) {
        	   $aReturn[$route->path] = $route;
        	}
        }

        return $aReturn;
    }
}
?>