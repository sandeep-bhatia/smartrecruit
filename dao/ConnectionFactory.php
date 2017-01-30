<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tarun
 * Date: 09/07/12
 * Time: 1:33 AM
 * To change this template use File | Settings | File Templates.
 */

/* Tarun : Check if this needs to implement connection pooling ? Check if connection pooling is done at driver layer ?*/
/* Currently creating connection object for every request. Does Apache does connection pooling */

include_once("../include/common.php");
include_once('../include/config.php');

class ConnectionFactory{


    public static function getConnection(){
        return self::createNewConnection(); // at the momemnt no connection pooling but interface is this
    }


    public static function createNewConnection(){
        logToFile("Creating new database connection");
        $password = 'root';
        $user = 'root';
        $connectionUrl = CONNECT_URL;
        $connection = new PDO($connectionUrl, $user, $password);
        return $connection;
    }

    public static function closeConnection($connection){
//        print("Database connection closed");
        if(!isset($connection)){
			logToFile("Closing database connection");
            $connection = null;
        }
    }
}
