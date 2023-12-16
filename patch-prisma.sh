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
echo "Instalando sngrep"
echo "" 
rm -Rf /etc/yum.repos.d/irontec.repo
cat > /etc/yum.repos.d/irontec.repo <<EOF
[irontec]
name=Irontec RPMs repository
baseurl=http://packages.irontec.com/centos/\$releasever/\$basearch/
EOF
rpm --import http://packages.irontec.com/public.key
yum install sngrep -y
echo ""
echo "Atualizando o sistema..."
echo ""
yum -y update && yum -y upgrade
clear
echo ""
echo ""
echo "Instalando OpenVPN"
yum install issabel-easyvpn -y
echo ""
echo "Instalando  subversion"
yum -y install subversion
echo ""
echo "Baixando o Favicon.."
echo ""
cd /var/www/html/ 
mv favicon.ico favcon_old.icon
cd
echo ""
wget -c -P /var/www/html  https://github.com/LeandroSaltori/ipbx-prismatelecom/blob/main/web/favicon.ico
echo ""
echo "Baixando o tema Prisma Telecom..."
echo ""
svn co https://github.com/LeandroSaltori/ipbx-prismatelecom/trunk/web/themes /var/www/html/themes
echo ""
echo "Renomeando as pastas 'lang' e modules para _old'"
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
echo "Instalando GeoIP "
echo ""
sudo yum makecache
echo ""
sleep 10
echo ""
sudo yum -y install GeoIP
echo ""
sleep 2
echo ""
echo "Ajustes Tempo de Transferencia de Chamadas" 
echo ""
cd /etc/asterisk/
mv features_general_custom.conf features_general_custom_old.conf
echo ""
cd
echo ""
wget -c -P /etc/asterisk/ https://github.com/LeandroSaltori/ipbx-prismatelecom/blob/main/etc/asterisk/features_general_custom.conf
echo ""
cho ""
cho ""
sleep 2
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
