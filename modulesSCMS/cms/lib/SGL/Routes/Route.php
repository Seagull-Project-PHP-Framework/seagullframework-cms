<?php

class SGL_Routes_Route
{
    public $route_id;
    public $site_id;
    public $page_id;
    public $description;
    public $is_active;

    public $name;
    public $path;

    public $moduleName;
    public $controller;
    public $action;

    public $aParams = array();

    public $requirements = array();

    public function save()
    {
        $dao = PageDAO::singleton();
        $dao->saveRoute($this);
    }

    /**
     * A route is valid if it has set:
     *
     * path
     * moduleName
     * controller
     *
     * This is minimum to get a route working
     */
    public function isValid()
    {
        return !empty($this->path) &&
               !empty($this->moduleName) &&
               !empty($this->controller);
    }

    public function getById($routeId)
    {
        $dao = PageDAO::singleton();
        $rawRoute = $dao->getRouteById($routeId);

        $class = __CLASS__;
        $route = new $class();

        $route->setFrom(array(
            'route_id' => $rawRoute->route_id,
            'site_id' => $rawRoute->site_id,
            'page_id' => $rawRoute->page_id,
            'is_active' => $rawRoute->is_active,
            'description' => $rawRoute->description,
        ));

        if ($aRoute = unserialize($rawRoute->route_data)) {
            $route->setFromRouteArray($aRoute);
        }

        return $route;
    }

    /**
     * Sets up route from input array
     *
     * Supports $input['__params'] compact input variable with format
     * "fooVar/1/barVar/2"
     *
     * @param array $input
     * @param $resetParams
     */
    public function setFrom($input, $resetParams = true)
    {
        // setting params of existing route needs cleanup
        // to avoid zombie vars
        if ($resetParams) {
            $this->resetParams();
        }

        // support for __params
        if (array_key_exists('__params',$input)) {
            if (strlen($input['__params'])) {
                $input['__params'] = trim($input['__params'],' /');
                $aParts = explode('/', $input['__params']);

                for ($i = 0; $i < count($aParts) - 1 ; $i++) {
                    $key = $aParts[$i];
                	$this->$key = $aParts[++$i];
                }
            }
            unset($input['__params']);
        }

        foreach ($input as $key => $val) {
            $this->$key = $val;
        }
    }

    public function setFromRouteArray($aRoute)
    {
        switch (count($aRoute)) {
            // array($path,$aParams) format
        	case 2:
        	   $this->path = $aRoute[0];
        	   $this->setFrom($aRoute[1]);
        	   break;

            // array($name,$path,$aParams) format
        	case 3:
               $this->name = $aRoute[0];
               $this->path = $aRoute[1];
               $this->setFrom($aRoute[2]);
            	break;

        }
    }

    /**
     * Constructs array in format supported by Horde_Routes
     */
    public function toRouteArray()
    {
        $aRoute = array();

        // is it a named route?
        if (!is_null($this->name)) {
            $aRoute[] = $this->name;
        }
        $aRoute[] = $this->path;
        $aRoute[] = $this->getParams();

        return $aRoute;
    }

    /**
     * Returns params of the route
     *
     * @param $extended on true params include moduleName/controller/action/requirements
     * @return array
     */
    public function getParams($extended = true)
    {
        $aParams = array();

        if ($extended) {
            $aParams['moduleName'] = $this->moduleName;
            if (strlen($this->controller)) {
                $aParams['controller'] = $this->controller;
            }
            if (strlen($this->action)) {
                $aParams['action'] = $this->action;
            }
            if (!empty($this->requirements)) {
                $aParams['requirements'] = $this->requirements;
            }
        }

        $aParams += $this->aParams;

        return $aParams;
    }

    public function resetParams()
    {
        $this->aParams = array();
    }

    /**
     * Returns hash of parameters (name => value) that are in path
     * in formats: ':paramter' or ':(parameter)'
     *
     * @return array
     */
    public function getPathParams()
    {
        $aReturn = array();

        $aParams = array();
        if (preg_match_all('/:\(?(\w+)\)?/', $this->path, $aParams)) {
            // loop through matches and get their values
            foreach ($aParams[1] as $name) {
            	$aReturn[$name] = $this->{$name};
            }
        }

        return $aReturn;
    }

    public function __set($name, $value)
    {
        $this->aParams[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->aParams)) {
            return $this->aParams[$name];
        }
    }

}

?>