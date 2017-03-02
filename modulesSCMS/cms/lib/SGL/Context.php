<?php
/**
 * Context class and strategy
 *
 * @package SGL
 * @subpackage cms
 */

/**
 * Interface for CMS contexts.
 *
 * @package SGL
 * @subpackage cms
 */
interface SGL_CmsContextStrategy
{
    public function process($oData);
}

/**
 * A standard format for web post data.
 *
 * <code>
 *  //  pass data from post as arg
 *  $oContext = new SGL_Context($input);
 *  //  context strategy determines if Content or ContentType
 *  //  and maps data correctly
 *  $oContext->process();
 *
 *  //  SGL_Content object can be built from any input (php object, post, xml, etc)
 *  $oContent = new SGL_Content($oContext);
 *
 *  // must handle:
 *  //     - creating/updating existing content
 *  //     - creating/updating new content types
 *  //
 *  //  if caller doesn't finds SGL_WebContext->id, it's an insert, otherwise edit
 *  //  contentype call = $input->contentType
 *  //  content call    = $input->content
 * </code>
 *
 * @package SGL
 * @subpackage cms
 * @category module
 */
class SGL_Context
{
    protected $oData = null;
    protected $strategy = null;

    /**
     *
     * Initialises context object with SGL $input object.
     *
     * @param SGL_Registry $oData
     */
    function __construct($oData)
    {
        if (isset($oData->content)) {
            $this->oData = $oData->content;
            $this->strategy = 'WebContent';
        } elseif (isset($oData->contentType)) {
            $this->oData = $oData->contentType;
            $this->strategy = 'WebContentType';
        }
    }

    /**
     * Determines context from input and returns SGL_Context object.
     *
     * @return SGL_Context
     */
    function process()
    {
        if (!isset($this->strategy)) {
            return PEAR::raiseError('no strategy detected', SGL_ERROR_INVALIDCALL);
        }
        $strategyFile = SGL_MOD_DIR . '/cms/lib/SGL/Context/' . $this->strategy . '.php';
        if (!is_file($strategyFile)) {
            return PEAR::raiseError('no strategy file found', SGL_ERROR_NOFILE);
        }
        require_once $strategyFile;
        $strategyClass = 'SGL_Context_' . $this->strategy;
        if (!class_exists($strategyClass)) {
            return PEAR::raiseError('no strategy class found', SGL_ERROR_NOCLASS);
        }
        $processor = new $strategyClass();
        $oResult = $processor->process($this->oData);
        return $oResult;
    }
}
?>