#!/bin/bash
versao="1.0"
clear
echo "Instalando ferramentas úteis..."
echo ""
yum install wget tcpdump -y
updatedb
echo ""
echo "Atualizando o sistema..."
echo ""
yum -y update && yum -y upgrade
clear
