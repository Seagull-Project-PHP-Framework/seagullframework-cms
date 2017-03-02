<?php

class SimpleCms_Observable
{
    public $input;
    public $conf;
    public $path;
    public $aObservers = array();

    public function __construct(SGL_Registry $input, SGL_Output $output)
    {
        $this->input  = $input;
        $this->output = $output;
        $this->conf   = $input->getConfig();
    }

    function attach($observer)
    {
        $this->aObservers[] = $observer;
    }

    function detach($observer)
    {
        $this->aObservers = array_diff($this->aObservers, array($observer));
    }

    function notify()
    {
        foreach ($this->aObservers as $obs) {
            $returnVal = $obs->update($this);
            if (PEAR::isError($returnVal)) {
                PEAR::raiseError($returnVal->getMessage(), $returnVal->getCode());
            }
        }
    }

    function getStatus() {}

    public function attachMany($observersString)
    {
        if (!empty($observersString)) {
            $aObservers = explode(',', $observersString);
            foreach ($aObservers as $observer) {
                list($moduleName, $observer) = explode('_', $observer);
                $moduleName = strtolower($moduleName);
                $path = SGL_MOD_DIR . '/' . $moduleName . '/classes/observers';
                $observerFile = "$path/$observer.php";
                if (file_exists($observerFile)) {
                    require_once $observerFile;
                    $this->attach(new $observer());
                }
            }
        }
    }
}

?>