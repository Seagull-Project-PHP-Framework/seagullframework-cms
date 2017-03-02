<?php

/**
 * Dashboard data access object.
 *
 * @package dashboard
 * @author Andrey Baigozin <a.baigozin@gmail.com>
 */
class DashboardDAO extends SGL_Manager
{
    /**
     * Returns a singleton DashboardDAO instance.
     *
     * @return DashboardDAO
     */
    public static function &singleton()
    {
        static $instance;

        // If the instance is not there, create one
        if (!isset($instance)) {
            $class = __CLASS__;
            $instance = new $class();
        }
        return $instance;
    }

    public function addWidget($usr_id, $name, $page, $column, $position)
    {
        $query = "
            INSERT INTO widget (usr_id, name, page, col, position)
            VALUES (" . intval($usr_id) . ", " . $this->dbh->quoteSmart($name)
                . ", " . $this->dbh->quoteSmart($page) . ", "
                . intval($column) . " , " . intval($position) . ");
        ";
        return $this->dbh->query($query);

    }


    public function updateWidget($usr_id, $name, $page, $column, $position)
    {
        $ret = false;
        $query = "
            UPDATE widget SET col = " . intval($column) . ", position = "
                . intval($position) . ", "
                . "last_updated = '" . SGL_Date::getTime(true) . "'
            WHERE usr_id = " . intval($usr_id)
                . " AND name = " . $this->dbh->quoteSmart($name)
                . " AND page = " . $this->dbh->quoteSmart($page) . ";
        ";
        $ret = $this->dbh->query($query);
        if (!$this->dbh->affectedRows()) {
            $this->addWidget($usr_id, $name, $page, $column, $position);
            $ret = $this->dbh->query($query);
        }

        return $ret;
    }

    public function getWidgetsByUserId($usr_id, $page)
    {
        $query = "
            SELECT name, col, position
            FROM widget
            WHERE usr_id = " . intval($usr_id)
                . " AND page = " . $this->dbh->quoteSmart($page) . "
            ORDER BY col, position;
        ";
        $aWidgets = $this->dbh->getAll($query);
        $ret = array();
        foreach ($aWidgets as $aWidget) {
            if (!isset($ret[$aWidget->col])) {
                $ret[$aWidget->col] = array();
            }
            $ret[$aWidget->col][] = $aWidget->name;
        }
        return $ret;
    }
}
?>