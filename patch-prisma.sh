#!/bin/bash
versao="1.0"
clear
yum install wget tcpdump -y
updatedb
yum -y update && yum -y upgrade
clear
