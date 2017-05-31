<?php
/**
 * Created by PhpStorm.
 * User: mt
 * Date: 02.04.17
 * Time: 10:06
 */

$clientDomains   = [ 'c001.r2o', 'c002.r2o', 'c003.r2o', 'c004.r2o' ];

if ( in_array( $_SERVER["HTTP_HOST"], $clientDomains ) ) {
	$_domain         = explode( '.', $_SERVER["HTTP_HOST"] );
	$dbName          = $_domain[0];

	$servername = $dbName . '_db_1';
	$username   = "root";
	$password   = "1234567";

	$runsOnContainer = true;

} else {
	$servername = "127.0.0.1";
	$username   = "root";
	$password   = "root";

	$runsOnContainer = false;
}


$dbname = "fh_todo";

// Create connection
$conn = new mysqli( $servername, $username, $password, $dbname );

// Check connection
if ( $conn->connect_error ) {
	die( "Connection failed: " . $conn->connect_error );
}