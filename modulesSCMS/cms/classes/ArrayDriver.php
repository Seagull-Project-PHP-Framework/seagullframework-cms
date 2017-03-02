<?php

require_once SGL_MOD_DIR . '/navigation/classes/ArrayDriver.php';

/**
 * Cms version of array driver.
 *
 * @package cms
 * @subpackage navigation
 * @author Dmitri Lakachauskis <lakiboy83@gmail.com>
 */
class CmsArrayDriver extends ArrayDriver
{
    /**
     * Constructor.
     *
     * @param SGL_Output $output
     */
    public function __construct(SGL_Output $output)
    {
        parent::ArrayDriver($output);
    }
}

?>