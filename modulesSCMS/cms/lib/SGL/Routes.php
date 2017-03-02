<?php
class SGL_Routes
{
    /**
     * Reads routes stored in Horde_Routes format from file
     *
     * @param $routesFile the location of the file
     * @return array SGL_Routes_Route
     */
    public static function read($routesFile)
    {
        if (!file_exists($routesFile)) {
            return false;
        }

        // no custom routes by default or in case $aRoutes var is not set
        $aRoutes = array();

        include $routesFile;

        $aReturn = array();
        foreach ($aRoutes as $key => $route) {
            $oRoute = new SGL_Routes_Route();
            $oRoute->setFromRouteArray($route);
            $aReturn[$oRoute->path] = $oRoute;
        }

        return $aReturn;
    }

    /**
     * Writes array of SGL_Routes_Route routes to file
     *
     * @param $routesFile the location of the file
     */
    public static function write($aRoutes, $routesFile)
    {
        $tmp = array();
        foreach ($aRoutes as $oRoute) {
        	$tmp[] = $oRoute->toRouteArray();
        }

        $routesStr = var_export($tmp, true);

        $routesData = <<< EOT
<?php
\$aRoutes = {$routesStr}
?>
EOT;

        if (is_writable($routesFile)) {
            if (!$handle = fopen($routesFile, 'w')) {
                $err = PEAR::raiseError('could not open routes file for writing');
                return false;

            }
            if (fwrite($handle, $routesData) === false) {
                $err = PEAR::raiseError('could not write to file' . $routesFile);
            }
            fclose($handle);
        }

        return true;
    }
}
?>
