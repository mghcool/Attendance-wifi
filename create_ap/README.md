## Features
* 在任何频道创建AP（接入点）。
* 选择下列加密之一:WPA, WPA2, WPA/WPA2, Open(无加密)。
* 隐藏你的SSID.
* 禁用客户机之间的通信(客户端隔离)。
* 支持 IEEE 802.11n 和 802.11ac
* Internet共享方法：NATed或Bridged或None（无Internet共享）。
* 选择AP网关IP(仅适用于"NATed"和"无Internet"共享方法)。
* 您可以使用与获取Internet连接相同的接口创建AP。
* 您可以通过管道或参数传递您的SSID和密码（请参阅示例）。


## 依赖
* bash (to run this script)
* util-linux (for getopt)
* procps or procps-ng
* hostapd
* iproute2
* iw
* iwconfig (you only need this if 'iw' can not recognize your adapter)
* haveged (optional)
* dnsmasq
* iptables


## 安装
    git clone https://github.com/oblique/create_ap
    cd create_ap
    make install

## 例子
### 没有密码(开放网络)：
    create_ap wlan0 eth0 MyAccessPoint

### WPA + WPA2密码：
    create_ap wlan0 eth0 MyAccessPoint MyPassPhrase

### 没有互联网共享的AP：
    create_ap -n wlan0 MyAccessPoint MyPassPhrase

### 桥接互联网共享：
    create_ap -m bridge wlan0 eth0 MyAccessPoint MyPassPhrase

### 桥接互联网共享(预配置桥接接口)：
    create_ap -m bridge wlan0 br0 MyAccessPoint MyPassPhrase

### 来自同一WiFi接口的互联网共享：
    create_ap wlan0 wlan0 MyAccessPoint MyPassPhrase

### 选择其他WiFi适配器驱动程序
    create_ap --driver rtl871xdrv wlan0 eth0 MyAccessPoint MyPassPhrase

### 没有使用管道的密码（开放网络）：
    echo -e "MyAccessPoint" | create_ap wlan0 eth0

### 使用管道的WPA + WPA2密码：
    echo -e "MyAccessPoint\nMyPassPhrase" | create_ap wlan0 eth0

### 启用IEEE 802.11n
    create_ap --ieee80211n --ht_capab '[HT40+]' wlan0 eth0 MyAccessPoint MyPassPhrase

### 客户隔离：
    create_ap --isolate-clients wlan0 eth0 MyAccessPoint MyPassPhrase

## 系统服务
[systemd](https://wiki.archlinux.org/index.php/systemd#Basic_systemctl_usage) 服务说明
### 立即开始服务：
    systemctl start create_ap

### 开机时运行：
    systemctl enable create_ap
