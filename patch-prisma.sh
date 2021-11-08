#!/bin/bash
yum install wget tcpdump -y
updatedb
yum -y update && yum -y upgrade
clear
