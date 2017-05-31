<?php
/**
 * Created by PhpStorm.
 * User: mt
 * Date: 02.04.17
 * Time: 11:03
 */

$companyId = !empty($_POST["companyId"]) ? intval($_POST["companyId"]) : 2;

// 1. Start Ansible and follow the playbook
$runAnsibleCommand = "";
$runAnsibleCommand .= "/usr/local/bin/ansible-playbook -i ";
$runAnsibleCommand .= __DIR__ . "/ansible/hosts -s ";
$runAnsibleCommand .= __DIR__ . "/ansible/stopContainerPlaybook.yml ";
$runAnsibleCommand .= "--extra-vars 'dest_path=/home/rbole/ company_id=".$companyId."'";

$result = shell_exec($runAnsibleCommand);

echo $result;