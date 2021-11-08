#!/bin/bash
versao="1.0"
clear
echo "||||||||| ||||||||| |||  ||||||||  ||\\    //|||  ||||||||||
echo "|||   ||| |||   |||      |||       |||\\  // |||  |||    |||
echo "||||||||| ||||||||| |||  ||||||||  ||| \\//  |||  |||||||||| 
echo "|||       ||| \\\   |||       |||  |||       |||  |||    |||
echo "|||       |||  \\\  |||  ||||||||  |||       |||  |||    |||
echo "==========================================================="
echo "Patch Prisma Telecom para Issabel"
echo "Autor Leandro Saltori - Empresa Prisma Telecom - Franca"
echo "https://www.prismatelecom.com / 016 3702 - 7844"
echo "==========================================================="
echo ""
sleep 20
echo ""
echo "INICIANDO O PROCESSO..."
echo ""
echo "Instalando ferramentas úteis..."
echo ""
yum install wget tcpdump -y
updatedb
echo ""
echo "Atualizando o sistema..."
echo ""
yum -y update && yum -y upgrade
clear
echo "||||||||| ||||||||| |||  ||||||||  ||\\    //|||  ||||||||||
echo "|||   ||| |||   |||      |||       |||\\  // |||  |||    |||
echo "||||||||| ||||||||| |||  ||||||||  ||| \\//  |||  |||||||||| 
echo "|||       ||| \\\   |||       |||  |||       |||  |||    |||
echo "|||       |||  \\\  |||  ||||||||  |||       |||  |||    |||
echo "==========================================================="
echo "Patch Prisma Telecom para Issabel"
echo "Autor Leandro Saltori - Empresa Prisma Telecom - Franca"
echo "https://www.prismatelecom.com / 016 3702 - 7844"
echo "==========================================================="
echo ""
echo "===================  FIM  ================================="
