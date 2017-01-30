<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tarun
 * Date: 08/07/12
 * Time: 11:27 PM
 * To change this template use File | Settings | File Templates.
 */

include_once("../include/common.php");
include_once("ConnectionFactory.php");

class DataAccess{

    public static function execute($query, $parameterArray){
		self::logSql($query, $parameterArray);

		checkNotEmpty($parameterArray);
        $connection = ConnectionFactory::createNewConnection();
        $statement = $connection->prepare($query);
        $statement->execute($parameterArray);
        $result = $statement->fetchAll();
        ConnectionFactory::closeConnection($connection);

        return $result;
    }



	/* This returns last auto_increment*/
	public static function insert($query, $parameterArray, $connection){
		self::logSql($query, $parameterArray);

		checkNotEmpty($parameterArray);
		$closeConnection = false;
		if(!isset($connection)){
			$closeConnection = true;
			$connection = ConnectionFactory::createNewConnection();
		}
		$statement = $connection->prepare($query);
		$success = $statement->execute($parameterArray);
		$lastAutoincrement = 0;
		if($success == 1){
			$lastAutoincrement = $connection->lastInsertId();
		}

		if($closeConnection){
			ConnectionFactory::closeConnection($connection);
		}

		return $lastAutoincrement;
	}



	public static function logSql($query, $parameterArray) {
//		print("About to run query : '" . $query . "' with parameter : '" . $parameterArray . "'");
		logToFile("About to run query : '" . $query . "' with parameter : '" . $parameterArray . "'");
	}

}


