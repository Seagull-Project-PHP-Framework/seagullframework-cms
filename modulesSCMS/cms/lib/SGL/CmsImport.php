<?php

/**
 * CMS Import class, strategy and interface
 *
 * @package SGL
 * @subpackage cms
 */


/**
 * Requires
 */


/**
 * Wrapper for various import strategies.
 *
 * @package SGL
 * @subpackage cms
 * @category module
 */
class SGL_CmsImport
{
    protected $strategy;

    public function __construct($strategy = null, $options = array())
    {
        if (is_string($strategy)) {
            $this->acceptStrategy($this->loadStrategy($strategy,$options));
        } elseif (is_object($strategy)) {
            $this->acceptStrategy($strategy);
        }
    }

    public function loadStrategy($name, $options = array())
    {
        if (require_once SGL_MOD_DIR . '/cms/lib/SGL/CmsImport/' . $name . '.php') {
            $classname = 'SGL_CmsImport_Strategy_' . $name;
            $this->acceptStrategy(new $classname($options));
        } else {
            throw new Exception('Import Strategy not found');
        }
    }

    /**
     * Strategy loader.
     *
     * @param SGL_CmsImportStrategy $strategy
     */
    public function acceptStrategy(SGL_CmsImportStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * Returns import strategy.
     *
     * @return SGL_CmsImportStrategy
     */
    public function getStrategy()
    {
        return $this->strategy;
    }

    /**
     * Returns strategy's config.
     *
     * @return array
     * @todo never called
     */
    public function getConfig()
    {
        return $this->strategy->getConfig();
    }

    /**
     * Sets strategy's config.
     *
     * @param array $aOptions
     * @return boolean
     */
    public function setConfig(array $aOptions = array())
    {
        return $this->strategy->setConfig($aOptions);
    }

    /**
     * Initialises strategy.
     *
     * @return boolean
     */
    public function init()
    {
        return $this->strategy->init();
    }

    /**
     * Performs data import.
     *
     * @return boolean
     */
    public function import()
    {
        $aRaw = $this->strategy->read();

        if (PEAR::isError($aRaw)) {
            return $aRaw;
        }
        $result = $this->strategy->write($aRaw);
        return $result;
    }
}

/**
 * Interface for import strategies.
 *
 * @package SGL
 */
interface SGL_CmsImportStrategy
{
    function init();
    function read();
    function write();
}

/**
 * Parent class of import strategies.
 *
 * @package SGL
 * @subpackage cms
 * @category module
 */
abstract class SGL_CmsImport_StrategyAbstract implements SGL_CmsImportStrategy
{
    /**
     * Configuration options
     *
     * @var array
     */
    protected $conf = array();

    /**
     * Constructor, accepts options.
     *
     * @param array $aOptions
     */
    public function __construct($aOptions = array())
    {
        if (!empty($aOptions)) {
            $this->setConfig($aOptions);
        }
    }

    /**
     * Return strategy config.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->conf;
    }

    /**
     * Set strategy config.
     */
    public function setConfig($aOptions = array())
    {
        if (!empty($aOptions)) {
            $this->conf = SGL_Array::mergeReplace($this->conf,$aOptions);
        }
    }
}

?>
