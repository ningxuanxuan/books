<?php

class cls_mysql extends mysqli
{
    function __construct($host=null, $username=null, $passwd= null, $dbname = null, $port = null, $socket = null, $charset = 'gbk')
    {
        parent::__construct($host, $username, $passwd, $dbname, $port, $socket);
        
        $this->cls_mysql($host, $username, $passwd, $dbname, $port, $socket, $charset);
    }
    
    
    function cls_mysql($host=null, $username=null, $passwd= null, $dbname = null, $port = null, $socket = null, $charset = 'gbk')
    {
        
    }
}

?>