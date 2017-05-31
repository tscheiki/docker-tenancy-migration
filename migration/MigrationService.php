<?php

/**
 * Created by PhpStorm.
 * User: mt
 * Date: 02.04.17
 * Time: 12:00
 */
class MigrationService {

	const FILENAME_INSERT_DATA = "data.sql";

	/**
	 * @var array
	 */
	private $config = [];
	/**
	 * @var mysqli
	 */
	private $conn;
	/**
	 * @var int
	 */
	private $clientId;
	/**
	 * @var array
	 */
	private $keys = [];

	private $file;

	/**
	 * MigrationService constructor.
	 */
	public function __construct( $dbConnection ) {
		$this->conn = $dbConnection;
	}

	/**
	 * @param mixed $config
	 */
	public function setConfig( $config ) {
		$this->config = $config;
	}

	/**
	 * Creates the full migration.
	 */
	public function start() {
		error_reporting( 0 );
		set_time_limit( 0 );

		$this->file = $this->openFile( self::FILENAME_INSERT_DATA );

		$this->createTableMigration();
		$this->createDataMigration();

		$this->closeFile( $this->file );

		echo "Successfully created dump!";
	}

	/**
	 * Creates the CREATE TABLE migration file.
	 */
	private function createTableMigration() {

		$allTables = $this->conn->query( "SHOW TABLES" );

		if ( $allTables->num_rows > 0 ) {

			while ( $rowTables = $allTables->fetch_array() ) {
				$tableName = $rowTables[0];
				fwrite( $this->file, "\n\nDROP TABLE IF EXISTS `" . $tableName . "`;\n" );

				$res = $this->conn->query( "SHOW CREATE TABLE `" . $tableName . "`" );

				if ( $res ) {
					$create    = $res->fetch_array();
					$create[1] .= ";";
					$line      = str_replace( "\n", "", $create[1] );
					fwrite( $this->file, $line . "\n" );
				}
			}
		}

	}

	/**
	 * Creates all the INSERTS for each client in a seperate file.
	 */
	private function createDataMigration() {

		$where = !is_int( $this->config['keyTable']['key'] ) ? '' : " WHERE " . $this->config['keyTable']['keyName'] . " = " .$this->config['keyTable']['key']. ";";

		// First loop over keyTable
		$sqlKeyTable    = "SELECT " . $this->config['keyTable']['keyName'] . " FROM " . $this->config['keyTable']['tableName'] . $where;
		$resultKeyTable = $this->conn->query( $sqlKeyTable );

		if ( $resultKeyTable->num_rows > 0 ) {

			while ( $rowKeyTable = $resultKeyTable->fetch_assoc() ) {

				$this->clientId = $rowKeyTable[ $this->config['keyTable']['keyName'] ];

				$this->keys = [];

				// get key table data
				$this->getKeyTableDataInserts();

				// Loop over all other tables
				$this->getExportOrderDataInserts();

			}

		}

	}

	/**
	 * @param $file
	 */
	private function getKeyTableDataInserts() {
		$tableData = $this->conn->query( "SELECT * FROM `" . $this->config['keyTable']['tableName'] . "` WHERE " . $this->config['keyTable']['keyName'] . ' = ' . $this->clientId );
		while ( $data = $tableData->fetch_assoc() ) {
			$line = $this->createInsertLine( $this->config['keyTable']['tableName'], $data );
			fwrite( $this->file, $line . "\n" );
		}
	}

	/**
	 * @param $file
	 */
	private function getExportOrderDataInserts() {
		foreach ( $this->config['exportOrder'] as $key => $value ) {

			$whereQuery = $this->getWhereQuery( $value["relation"] );

			$tableData     = $this->conn->query( "SELECT * FROM `" . $value["tableName"] . "`" . $whereQuery );
			$dataRowsCount = $tableData->num_rows;

			if ( $dataRowsCount > 0 ) {
				while ( $data = $tableData->fetch_assoc() ) {
					$line = $this->createInsertLine( $value["tableName"], $data );
					fwrite( $this->file, $line . "\n" );
				}
			}
		}
	}

	/**
	 * @param $relationValue
	 *
	 * @return string
	 */
	private function getWhereQuery( $relationValue ) {
		$where = "";
		if ( ! empty( $relationValue ) ) {
			$splittedRelation = explode( "|", $relationValue );
			$foreign_key      = $splittedRelation[0];
			$main_key         = $splittedRelation[1];
			$where            = "WHERE " . $foreign_key . " IN (" . $this->getWhereInStringFromArray( $this->keys[ $main_key ] ) . ");";
		}

		return $where;
	}

	private function getWhereInStringFromArray( $array ) {
		return join( ",", $array );
	}

	/**
	 * @param $tableName
	 * @param $data
	 *
	 * @return bool|string
	 */
	private function createInsertLine( $tableName, $data ) {
		$line     = "INSERT INTO `" . $tableName . "` VALUES (";
		$addedKey = false;

		foreach ( $data as $key => $value ) {

			if ( ! $addedKey ) {
				$this->keys[ $key ][] = $value;
				$addedKey             = true;
			}

			$valueToAdd = null;

			switch ( gettype( $value ) ) {
				case "string":
					$valueToAdd = "'" . $this->conn->escape_string( $value ) . "'";
					break;

				case "NULL":
					$valueToAdd = 'NULL';
					break;
			}

			$line .= $valueToAdd . ", ";
		}

		$line = substr( $line, 0, - 2 );
		$line .= ');';

		return $line;
	}

	/**
	 * @param $fileName
	 *
	 * @return resource
	 */
	private function openFile( $fileName ) {

		$pathToDumpsFolder = __DIR__ . '/dumps';
		if (!file_exists($pathToDumpsFolder)) {
			mkdir($pathToDumpsFolder, 0777, true);
		}

		$file = fopen( __DIR__ . '/dumps/' . $fileName, "w" );

		$sqlStart = "
# ************************************************************
# Migration created by Markus Tscheik
# ************************************************************
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE DATABASE IF NOT EXISTS `fh_todo` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE fh_todo;";

		fwrite( $file, $sqlStart . "\n\n" );

		return $file;
	}

	/**
	 * @param $file
	 */
	private function closeFile( $file ) {

		$sqlEnd = "
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";

		fwrite( $file, $sqlEnd . "\n\n" );

		fclose( $file );
	}

}