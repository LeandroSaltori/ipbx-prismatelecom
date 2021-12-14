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
      - Download das Pastas LANG e MODULES atualizadas para PT-BR     
 
## Outras Informações ##
  - CentOS 7.7
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

yum update
yum -y install wget

Executar o Path para atualização de pastas e arquivos Prisma Telecom:
```
wget -O - https://github.com/LeandroSaltori/ipbx-prismatelecom/raw/main/patch-prisma.sh| bash
```
## ISSABEL Versão BETA Issabel 2021 ##

Link Download: [https://sourceforge.net/projects/issabelpbx/files/Issabel%204/issabel4-BETA-USB-DVD-x86_64-20210714.iso/download]
  - ATULIZAÇÕES
    - CentOS 7.9 (Versão Beta foi atualizada para Centos 7.9)
    - Cofigurações de Idiomas do PBX (GUI e Audios pt-BR) na instalação.
    - PJ SIP na Interface Gráfica (Primario e Secundario)
      - Registra divirsos ramais com o mesmo número
      - Porta SIP: 5060 /  PJSIP: 5066
      - PJ SIP só funciona para Asterisk 13 e 16
    - Novo Design de Relátorios 
    - Suporte ASR (Vosk) em Português na URA
      - Suporte apenas na versão Asterisk 16.16.1
      - Necessario instalar para portugues: (O Patch Prisma já executa essa instalação deste pacote)
        - yum -y install docker-repo
        - yum -y install vosk-server-pt
        - OBS: "spoken" - A letra deve ser minúsculo.
    - Atualização de Gerenciamento de Firewall e GeoIP
      
## Ajustes Tempo de Transferencia de Chamadas ##

/etc/asterisk/features_general_custom.conf

e adicione as linhas:

transferdigittimeout=6
featuredigittimeout=3000

transferdigittimeout - Determina o número de segundos que o sistema aguarda o usuário digitar o número de destino numa transferência
featuredigittimeout  - Determina o tempo máximo, em milisegundos, que o usuário tem de tempo para digitar entre um dígito e o outro. 

## Como eu posso ajudar? ##
Ajude-nos a entregar um conteúdo de qualidade. Toda ajuda é bem vinda.

## Autor ##
Autor: Leandro Saltori - Prisma Telecom
