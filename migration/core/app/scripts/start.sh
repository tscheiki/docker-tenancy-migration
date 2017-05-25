#!/bin/bash
#----------------------------------------------
## deletes a pod and recreates it
## ./start.sh 001 c1.r2o
## ./start.sh [PortID] [Domain]
## @author Roland Bole
#----------------------------------------------
echo "start of the transformation process"
echo "`/bin/bash ./scripts/clear.sh $1 $2`"
echo "`/bin/bash ./scripts/newClient.sh $1 $2`"