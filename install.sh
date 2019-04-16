#!/bin/bash


echo "============================更换国内源============================"
echo "清华源"
echo deb http://mirrors.tuna.tsinghua.edu.cn/raspbian/raspbian/ stretch main contrib non-free rpi > /etc/apt/sources.list
echo deb-src http://mirrors.tuna.tsinghua.edu.cn/raspbian/raspbian/ stretch main contrib non-free rpi >> /etc/apt/sources.list
echo deb http://mirror.tuna.tsinghua.edu.cn/raspberrypi/ stretch main ui > /etc/apt/sources.list.d/raspi.list
echo deb-src http://mirror.tuna.tsinghua.edu.cn/raspberrypi/ stretch main ui >> /etc/apt/sources.list.d/raspi.list


echo "============================更新软件============================"
sudo apt update
sudo apt upgrade -y


echo "============================安装Apache============================"
sudo apt install apache2 -y
echo "============================安装PHP============================"
sudo apt install php7.0 php7.0-cli php7.0-curl php7.0-gd php7.0-mcrypt -y
echo "============================安装Mysql============================"
sudo apt-get install mysql-server  mysql-client -y




echo "============================安装Git============================"
sudo apt install git -y



echo "============================安装文件传输工具============================"
sudo apt install lrzsz



echo "============================安装ap依赖工具============================"
sudo apt install  haveged -y
sudo apt install dnsmasq -y
sudo apt install hostapd=2:2.4-1+deb9u2 -y


echo "============================安装花生壳============================"
wget -O phddns.deb http://download.oray.com/peanuthull/embed/phddns_rapi_3.0.2.armhf.deb
sudo dpkg -i phddns.deb
rm phddns.deb



echo "============================安装pip3============================"
sudo apt install python3-pip -y || sudo apt install python3-pip -y
echo "更新pip3..."
sudo pip3 install -U pip
echo "pip3换源..."
sudo pip3 config set global.index-url https://pypi.tuna.tsinghua.edu.cn/simple


echo "============================安装pymysql模块============================"
sudo pip3 install pymysql

echo "============================安装程序打包模块============================"
sudo pip3 install pyinstaller
echo "构建引导程序..."
git clone https://gitee.com/mghcool/bootloader.git
cd bootloader
python3 ./waf all
cd ..
sudo mv PyInstaller/bootloader/Linux-32bit-arm /usr/local/lib/python3.5/dist-packages/PyInstaller/bootloader
sudo rm -rf PyInstaller bootloader



echo "============================安装phpmyadmin============================"
sudo apt-get install phpmyadmin -y



echo "============================配置Mysql============================"
#设置root密码
sudo mysql -uroot -e "update mysql.user set authentication_string=PASSWORD('mgh'), plugin='mysql_native_password' where user='root';"
sudo mysql -uroot -e "flush privileges;"
echo "重启Mysql..."
sudo /etc/init.d/mysql restart

HOSTNAME="127.0.0.1"       #数据库信息
PORT="3306"
USERNAME="root"
PASSWORD="mgh"

DBNAME="Attendance"              #数据库名称

echo "创建数据库Attendance..."
echo $DBNAME
create_db_sql="create database IF NOT EXISTS ${DBNAME}"
mysql -h${HOSTNAME}  -P${PORT}  -u${USERNAME} -p${PASSWORD} -e "${create_db_sql}"

echo "创建数据表user..."
sql="create table IF NOT EXISTS user (name varchar(10), class varchar(20), mac varchar(20), status varchar(5), date varchar(20))"
mysql -h${HOSTNAME}  -P${PORT}  -u${USERNAME} -p${PASSWORD} ${DBNAME} -e"${sql}"
mysql -h${HOSTNAME}  -P${PORT}  -u${USERNAME} -p${PASSWORD} ${DBNAME} -e"ALTER TABLE user ADD PRIMARY KEY(mac);"

echo "创建数据表record..."
sql="create table IF NOT EXISTS record (name varchar(10), class varchar(20), status varchar(5), date varchar(20), class_only varchar(15))"
mysql -h${HOSTNAME}  -P${PORT}  -u${USERNAME} -p${PASSWORD} ${DBNAME} -e"${sql}"
mysql -h${HOSTNAME}  -P${PORT}  -u${USERNAME} -p${PASSWORD} ${DBNAME} -e"ALTER TABLE record ADD PRIMARY KEY(date);"

echo "创建数据表Administrator..."
sql="create table IF NOT EXISTS Administrator (user varchar(10), pass varchar(20))"
mysql -h${HOSTNAME}  -P${PORT}  -u${USERNAME} -p${PASSWORD} ${DBNAME} -e"${sql}"
mysql -h${HOSTNAME}  -P${PORT}  -u${USERNAME} -p${PASSWORD} ${DBNAME} -e"ALTER TABLE Administrator ADD PRIMARY KEY(user);"

echo "Administrator插入数据(账号：mgh	密码：mgh)..."
sql="insert into Administrator values('mgh','mgh')"
mysql -h${HOSTNAME}  -P${PORT}  -u${USERNAME} -p${PASSWORD} ${DBNAME} -e  "${sql}"


echo "============================设置Apache权限============================"
sudo sed -i "s/User \${APACHE_RUN_USER}/User pi/g" /etc/apache2/apache2.conf
sudo sed -i "s/Group \${APACHE_RUN_GROUP}/Group root/g" /etc/apache2/apache2.conf



echo "============================部署文件============================"
echo "克隆仓库Attendance-wifi..."
git clone https://gitee.com/mghcool/Attendance-wifi.git
echo "安装create_ap.."
cd Attendance-wifi/create_ap
sudo make install
cd /home/pi
echo "部署文件..."
sudo rm -rf /var/www/html
sudo mv /home/pi/Attendance-wifi/html /var/www
sudo chmod -R 777 /var/www/html
sudo mv /home/pi/Attendance-wifi/Attendance.py /home/pi
echo "添加服务..."
sudo mv /home/pi/Attendance-wifi/Attendance.service /etc/systemd/system/
echo "开机启动..."
sudo sed -i "s/exit 0/sudo service Attendance start/g" /etc/rc.local
echo exit 0 >> /etc/rc.local
echo "添加mgh.com域名解析..."
sudo echo "192.168.10.1    mgh.com" >> /etc/hosts



echo "============================配置时区============================"
echo "1、选择Asia"
echo "2、选择Shanghai"
echo "================================================================"
sudo dpkg-reconfigure tzdata


echo "============================配置raspi-config============================"
echo "1、汉化"
echo "依次选择：4 Localisation Options >> I1 Change Locale"
echo "去掉   en_GB.UTF-8 UTF-8"
echo "\n"
echo "选择   en_US.UTF-8 UTF-8"
echo "选择   zh_CN.GBK GBK"
echo "选择   zh_CN.UTF-8 UTF-8"
echo "选择   OK"
echo "移动鼠标，选择   zh_CN.UTF-8"

echo "========================================================================"
sudo raspi-config



echo "\n\n"

echo "准备重启!"
sleep 1
echo "3"
sleep 1
echo "2"
sleep 1
echo "1"
sleep 1
echo "重启"

sudo reboot

