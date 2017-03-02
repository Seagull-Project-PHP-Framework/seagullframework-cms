<?php

class SimpleCms_Task_LoadData extends SGL_Task
{
    public function run($data)
    {
        // file to load
        $file = SGL_MOD_DIR . '/other.my.sql';

        // prepare cmd
        $conf   = SGL_Config::singleton()->getAll();
        $passwd = !empty($conf['db']['pass'])
            ? "-p{$conf['db']['pass']}"
            : '';
        $cmd    = "mysql -u{$conf['db']['user']} $passwd {$conf['db']['name']} < $file";

        SGL::logMessage($cmd, PEAR_LOG_DEBUG);
        `$cmd`;
    }
}
?>