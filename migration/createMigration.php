<?php
/**
 * Created by PhpStorm.
 * User: mt
 * Date: 02.04.17
 * Time: 11:03
 */

require( __DIR__ . '/../connectDB.php' );
require( __DIR__ . '/MigrationService.php' );

$id = $argv[1];

$config = [
	'keyTable'    => [
		'tableName' => 'tbl_company',
		'keyName'   => 'c_id',
		'key'      => $id
	],
	'exportOrder' => [
		1 => [
			'tableName' => 'tbl_type'
		],
		2 => [
			'tableName' => 'tbl_user',
			'relation'  => 'fk_c_id|c_id'
		],
		3 => [
			'tableName' => 'tbl_todo',
			'relation'  => 'fk_u_id|u_id'
		],
		4 => [
			'tableName' => 'tbl_comment',
			'relation'  => 'fk_t_id|t_id'
		]
	]
];

$migrationService = new MigrationService( $conn );
$migrationService->setConfig( $config );
$migrationService->start();