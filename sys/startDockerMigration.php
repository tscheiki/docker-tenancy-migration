<?php
/**
 * Created by PhpStorm.
 * User: mt
 * Date: 19.05.17
 * Time: 18:03
 */

$companyId = $_POST["companyId"];

$output = shell_exec(__DIR__ . '/../migration/startMigration.sh ' . $_SERVER['DOCUMENT_ROOT'] . ' ' . $companyId);

die($output);