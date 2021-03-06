<?php
//
// +----------------------------------------------------------------------+
// | PEAR :: DB_NestedSet_MDB                                             |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Daniel Khan <dk@webcluster.at>                              |
// +----------------------------------------------------------------------+
// Thanks to Hans Lellelid for suggesting support for PEAR::MDB
// and for his help in implementing this.
//
// $Id: MDB.php,v 1.16 2004/07/29 11:31:04 datenpunk Exp $
//

require_once 'MDB.php';
// {{{ DB_NestedSet_MDB:: class

/**
* Wrapper class for PEAR::MDB
*
* @author       Daniel Khan <dk@webcluster.at>
* @package      DB_NestedSet
* @version      $Revision: 1.16 $
* @access       public
*/
// }}}
class DB_NestedSet_MDB extends DB_NestedSet {
    // {{{ properties

    /**
    * @var object The MDB object
    */
    var $db;

    // }}}
    // {{{ constructor

    /**
    * Constructor
    *
    * @param mixed $dsn DSN as PEAR dsn URI or dsn Array
    * @param array $params Database column fields which should be returned
    *
    */
    function & DB_NestedSet_MDB(&$dsn, $params = array())
    {
        $this->_debugMessage('DB_NestedSet_MDB($dsn, $params = array())');
        $this->DB_NestedSet($params);
        $this->db =& $this->_db_Connect($dsn);
        $this->db->setFetchMode(MDB_FETCHMODE_ASSOC);
    }

    // }}}
    // {{{ destructor

    /**
    * Destructor
    */
    function _DB_NestedSet_MDB()
    {
        $this->_debugMessage('_DB_NestedSet_MDB()');
        $this->_DB_NestedSet();
        $this->_db_Disconnect();
    }

    // }}}
    // {{{ _db_Connect()

    /**
    * Connects to the db
    *
    * @return object DB The database object
    * @access private
    */
    function &_db_Connect(&$dsn)
    {
        $this->_debugMessage('_db_Connect($dsn)');
        if (is_object($this->db)) {
            return $this->db;
        }

        if (is_object($dsn)) {
            return $dsn;
        }

        $db =& MDB::connect($dsn);
        $this->_testFatalAbort($db, __FILE__, __LINE__);

        return $db;
    }

    // }}}
    // {{{ _isDBError()

    function _isDBError($err)
    {
        if (!MDB::isError($err)) {
            return false;
        }
        return true;
    }

    // }}}

    // {{{ _query()
    function _query($sql) {
      return $this->db->query($sql);
    }

    // {{{ _nextId()

    function _nextId($sequence)
    {
        return $this->db->nextId($sequence);
    }

    // }}}
    // {{{ _dropSequence()

    function _dropSequence($sequence)
    {
        $this->db->loadManager();
        return $this->db->dropSequence($sequence);
    }

    // }}}
    // {{{ _getOne()

    /**
     * @param string $sql SQL query
     * @return mixed
     * @access private
     */
    function _getOne($sql)
    {
        return $this->db->queryOne($sql);
    }

    // }}}
    // {{{ _getAll()

    function _getAll($sql)
    {
        return $this->db->queryAll($sql, null, MDB_FETCHMODE_ASSOC);
    }

    // }}}
    // {{{ _numRows()

    function _numRows($res)
    {
        return $this->db->numRows($res);
    }

    // }}}
    // {{{ _quote()

    function _quote($str)
    {
        return $this->db->getTextValue($str);
    }

    // }}}
    // {{{ _quoteIdentifier()
    function _quoteIdentifier($str) {

    // will work as soon as MDB supports this
        if (method_exists($this->db, 'quoteIdentifier')) {
            return $this->db->quoteIdentifier($str);
        }
        return $str;
    }
  // }}}
    // {{{ _db_Disconnect()
    /**
    * Disconnects from db
    *
    * @return void
    * @access private
    */
    function _db_Disconnect()
    {
        $this->_debugMessage('_db_Disconnect()');
        if (is_object($this->db)) {
            @$this->db->disconnect();
        }

        return true;
    }

    // }}}
}

?>