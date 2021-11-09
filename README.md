# IPBX-Prisma Telecom

### En - Description: ###
This is a patch and files for installing custom Isabel Linux by Prisma Telecom.

### BR - Descrição: ###
Este é um patch e arquivos para instalação de Issabel Linux personalizada pela Prisma Telecom.

### Sobre ###
  - CentOS 7 [https://store.docker.com/images/centos] - Install Docker
  - Patch Prisma Telecom [https://github.com/LeandroSaltori/ipbx-prismatelecom]
      - Instalaçao do TCP Dump
      - Instalação do SNGREP
      - Atualização do sistema (yum -y update && yum -y upgrade)
      - Instalação do OpenVPN
      - Instalação do subversion
      - Alteração do Favicon.ico
      - Downlod de Tema Prisma Telecom
      - Download das Pastas LANG e MODULES atualizadas      
 
## Outras Informações ##
  - CentOS 7
  - Asterisk 16

```
wget -O - https://github.com/LeandroSaltori/ipbx-prismatelecom/raw/main/patch-prisma.sh| bash
```

## ISSABEL ##
Você pode instalar o Issabel a partir de um script diretamente num CentOS mínimo com os seguintes comandos:
```
sudo wget -O - http://repo.issabel.org/issabel4-netinstall.sh | bash
```
Observações: Requer yum install -y wget

Executar o Path para atualização de pastas e arquivos Prisma Telecom:
```
wget -O - https://github.com/LeandroSaltori/ipbx-prismatelecom/raw/main/patch-prisma.sh| bash
```

## Como eu posso ajudar? ##
Ajude-nos a entregar um conteúdo de qualidade. Toda ajuda é bem vinda.

## Autor ##
Autor: Leandro Saltori - Prisma Telecom
