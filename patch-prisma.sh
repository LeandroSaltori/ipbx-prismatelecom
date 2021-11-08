#!/bin/bash
versao="1.0"
clear
echo ""
echo "||||||||| ||||||||| |||  ||||||||  |||\\    //|||  ||||||||||"
echo "|||   ||| |||   |||      |||       ||| \\  // |||  |||    |||"
echo "||||||||| ||||||||| |||  ||||||||  |||  \\//  |||  ||||||||||"
echo "|||       ||| \\\   |||       |||  |||        |||  |||    |||"
echo "|||       |||  \\\  |||  ||||||||  |||        |||  |||    |||"
echo "==========================================================="
echo "Patch Prisma Telecom para Issabel"
echo "Autor Leandro Saltori - Empresa Prisma Telecom - Franca"
echo "https://www.prismatelecom.com / 016 3702 - 7844"
echo "==========================================================="
echo ""
sleep 10
echo ""
echo "INICIANDO O PROCESSO..."
echo ""
echo "Instalando ferramentas úteis...[TCP DUMP]"
echo ""
yum install wget tcpdump -y
updatedb
echo ""
echo "Atualizando o sistema..."
echo ""
yum -y update && yum -y upgrade
clear
echo ""
echo "Instalando OpenVPN"
yum install issabel-easyvpn -y
echo ""
echo "Instalando  subversion"
yum -y install subversion
echo ""
echo "Baixando o Favicon.."
echo ""
svn co https://github.com/LeandroSaltori/ipbx-prismatelecom/trunk/web/favicon.ico /var/www/html 
#wget -c -P /var/www/html  https://github.com/LeandroSaltori/ipbx-prismatelecom/blob/main/web/favicon.ico
echo ""
echo "Baixando o tema Prisma Telecom..."
echo ""
svn co https://github.com/LeandroSaltori/ipbx-prismatelecom/trunk/web/themes /var/www/html/themes
echo ""
echo "Renomeando as pastas 'lang' e modules'"
cd /var/www/html/ 
mv lang lang_old
mv modules modules_old
echo ""
echo "Baixando as pastas 'lang' e 'modules' "
svn co https://github.com/LeandroSaltori/ipbx-prismatelecom/trunk/web/lang /var/www/html/lang
echo ""
svn co https://github.com/LeandroSaltori/ipbx-prismatelecom/trunk/web/modules /var/www/html/modules
echo ""
echo ""
echo ""
echo ""

echo ""
echo "||||||||| ||||||||| |||  ||||||||  |||\\    //|||  ||||||||||"
echo "|||   ||| |||   |||      |||       ||| \\  // |||  |||    |||"
echo "||||||||| ||||||||| |||  ||||||||  |||  \\//  |||  ||||||||||"
echo "|||       ||| \\\   |||       |||  |||        |||  |||    |||"
echo "|||       |||  \\\  |||  ||||||||  |||        |||  |||    |||"
echo "============================================================"
echo "Patch Prisma Telecom para Issabel"
echo "Autor Leandro Saltori - Empresa Prisma Telecom - Franca"
echo "https://www.prismatelecom.com / 016 3702 - 7844"
echo "==========================================================="
echo ""
echo "===================  FIM  ================================="