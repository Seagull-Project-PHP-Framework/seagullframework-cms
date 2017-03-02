<?php

/**
 * Enter description here...
 *
 * @package SGL
 * @subpackage cms
 */

/**
 * Requires
 */


/**
 * Enter description here...
 *
 * @package SGL
 * @subpackage cms
 * @category module
 */
class SGL_CmsImport_Strategy_Publisher extends SGL_CmsImport_StrategyAbstract
{
    private $_dbhRemote = null;

    protected $conf = array(
        'db'    => array(
           'type'         => 'mysql_SGL',
           'host'         => 'localhost',
           'protocol'     => 'unix',
           'socket'       => '',
           'port'         => '3306',
           'user'         => '',
           'pass'         => '',
           'name'         => '',
           'prefix'       => '',
           'postConnect'  => "SET NAMES 'utf8'",
        ),
        'table' => array(
            'item'              => 'item',
            'item_addition'     => 'item_addition',
            'item_type'         => 'item_type',
            'item_type_mapping' => 'item_type_mapping',
        ),
        'fields' => array(
            'title'       => 'Title',
            'description' => 'Description',
            'date_created'=> 'Date Created',
        ),
        'filters' => array(
            'status'              => '',
            'item_type_id'        => 1,
            'date_created_after'  => '',
            'date_created_before' => '',
            'orderBy'             => 'date_created ASC',
        ),
        'mapping' => array(
            'content_type_id' => '1',
            // content_type_id => mapping array
            '1' => array(
                'name'         => 'title',
                'dateCreated'  => 'date_created',
                'introduction' => 'description',
                'body'         => 'description',
                'isPublished'  => '',
            )
        )
    );

    /**
     * Checks whether the connection to remote DB could be set up.
     *
     * @return bool
     */
    function init()
    {
        if (!is_object($this->_dbhRemote)) {
            $this->conf['db']['phptype'] = $this->conf['db']['type'];
            $this->_dbhRemote = $this->_getRemoteDB($this->conf);
        }
        return (!PEAR::isError($this->_dbhRemote)) ? true : $this->_dbhRemote;
    }

    /**
     * Returns instance of PEAR's DB object.
     *
     * @param array $conf
     * @return object DB
     * @access protected
     */
    function &_getRemoteDB($conf)
    {
        return SGL_DB::singleton(SGL_DB::_getDsnAsString($conf));
    }

    /**
     * Reads items from remote SGL Publisher install
     * based on options. Should return array of objects.
     *
     * @return array
     */
    function read()
    {
        $aFilters = array();

        if (strlen($this->conf['filters']['status'])) {
            $aFilters[] = ' AND i.status  = ' . $this->conf['filters']['status'];
        }

        if (strlen($this->conf['filters']['item_type_id'])) {
            $aFilters[] = ' AND it.item_type_id = ' . $this->conf['filters']['item_type_id'];
        }

        if (strlen($this->conf['filters']['date_created_after'])) {
            $aFilters[] = " AND i.date_created >= '{$this->conf['filters']['date_created_after']}'";
        }

        if (strlen($this->conf['filters']['date_created_before'])) {
            $aFilters[] = " AND i.date_created <= '{$this->conf['filters']['date_created_before']}'";
        }

        $filter = implode('',$aFilters);

        $query = "
            SELECT
                i.item_id,
                i.category_id,
                i.date_created,
                ia.trans_id AS title_trans_id,
                ia.addition AS title,
                ia2.trans_id AS description_trans_id,
                ia2.addition AS description,
                i.start_date
            FROM    {$this->conf['db']['prefix']}{$this->conf['table']['item']} i,
                    {$this->conf['db']['prefix']}{$this->conf['table']['item_addition']} ia,
                    {$this->conf['db']['prefix']}{$this->conf['table']['item_addition']} ia2,
                    {$this->conf['db']['prefix']}{$this->conf['table']['item_type']} it,
                    {$this->conf['db']['prefix']}{$this->conf['table']['item_type_mapping']} itm,
                    {$this->conf['db']['prefix']}{$this->conf['table']['item_type_mapping']} itm2
            WHERE   ia.item_type_mapping_id = itm.item_type_mapping_id
            AND     ia2.item_type_mapping_id = itm2.item_type_mapping_id
            AND     it.item_type_id  = itm.item_type_id
            AND     i.item_id = ia.item_id
            AND     i.item_id = ia2.item_id
            AND     itm.field_name = 'title'
            AND     itm.field_type != itm2.field_type".
            $filter . "
            ORDER BY i.{$this->conf['filters']['orderBy']}
        ";

        return $this->_dbhRemote->getAll($query);
    }

    /**
     * Expects array of objects to be written, uses
     * $this->conf['mapping'] options to map
     * remote fields -> cms content fields
     *
     * @param array $aItems
     * @return bool
     */
    function write($aItems = array())
    {
        if (empty($aItems)) {
            return true;
        }

        $contentTypeId = $this->conf['mapping']['content_type_id'];
        $aFieldMapping = $this->conf['mapping'][$contentTypeId];

        foreach ($aItems as $item) {
            $oContent = SGL_Content::getByType($contentTypeId);
            foreach ($aFieldMapping as $cmsField => $publisherField) {
                $oContent->{$cmsField} = isset($item->{$publisherField})
                    ? $item->{$publisherField}
                    : '';
            }
            $oContent->langCode = SGL::getCurrentLang();
            $result = $oContent->save();
            if (PEAR::isError($result)) {
                return $result;
            }
        }
        return true;
    }
}

?>
