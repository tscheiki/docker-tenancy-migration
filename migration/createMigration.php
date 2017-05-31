<?php
/**
 * Created by PhpStorm.
 * User: mt
 * Date: 02.04.17
 * Time: 11:03
 */

require( __DIR__ . '/../connectDB.php' );
require( __DIR__ . '/MigrationService.php' );

$companyId = !empty($_POST["companyId"]) ? intval($_POST["companyId"]) : 1;

$config = [
	'keyTable'    => [
		'tableName' => 'tbl_company',
		'keyName'   => 'c_id',
		'key'       => $companyId
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

// 1. Create Database Dumps

$migrationService = new MigrationService( $conn );
$migrationService->setConfig( $config );
$migrationService->start();

die("DONE");
// 2. Start Ansible and follow the playbook

$explodeDir = explode('/migration',__DIR__);

$runAnsibleCommand = "";
$runAnsibleCommand .= "/usr/local/bin/ansible-playbook -i ";
$runAnsibleCommand .= __DIR__ . "/ansible/hosts -s ";
$runAnsibleCommand .= __DIR__ . "/ansible/migrationPlaybook.yml ";
$runAnsibleCommand .= "--extra-vars 'company_id=".$companyId." dest_path=/home/rbole/ app_path=".$explodeDir[0]."/'";

$result = shell_exec($runAnsibleCommand);

echo $result;