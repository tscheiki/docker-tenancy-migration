#!/bin/bash

ROOT_PATH=$1
ID=$2

echo "Starting migration"
php $ROOT_PATH'migration/createMigration.php' $ID

#ansible-playbook -i ansible/hosts -s ansible/migrationPlaybook.yml
