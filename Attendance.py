import re
import os
import json
import time
import codecs
import pymysql
import logging
import subprocess


#日志服务
logging.basicConfig(level=logging.INFO,  # 等级
                    format='%(asctime)s [line:%(lineno)d] %(levelname)s %(message)s',
                    datefmt='%Y/%m/%d %H:%M:%S',  # 日期格式
                    filename='/home/pi/Attendance.log',  # 文件名
                    filemode='a',)  # 追加模式，w是覆写模式

#将签到信息写入数据库
def mysql(s, m):
    date_time = time.strftime("%Y-%m-%d %H:%M:%S", time.localtime())
    db = pymysql.connect("127.0.0.1", "root", "mgh", "Attendance")
    cursor = db.cursor()
    try:
        #查询mac对应的姓名班级
        sql = 'SELECT * FROM user WHERE mac = '+'"'+m+'"'
        cursor.execute(sql)
        results = cursor.fetchall() 
        for row in results:
            name = row[0]
            clas = row[1]
        #记录接入时间
        sql = 'insert into record(name,class,status,date) values("'+name+'","'+clas+'","'+s+'","'+date_time+'")'
        cursor.execute(sql)
        db.commit()
        #更新连接状态
        sql = "UPDATE `user` SET `status` = '"+s+"', `date` = '"+date_time+"' WHERE `user`.`mac` = '"+m+"'"
        cursor.execute(sql)
        db.commit()
    except:
        db.rollback()
        logging.info('数据写入失败！')
    db.close()



'''循环扫描设备'''
p = subprocess.Popen('sudo create_ap -d -g 192.168.10.1 wlan0 eth0 Attendance 123456789', shell=True,
                     stdout=subprocess.PIPE, stderr=subprocess.PIPE,)  # 起一个进程，执行shell命令
while True:
    line = p.stdout.readline()  # 实时获取行
    if line:  # 如果行存在的话
        r_line = str(line, encoding='utf-8')  # byte转字符串
        if(r_line.find('group key handshake completed') != -1):  # 搜索连接字符
            start = r_line[11:28]
            mysql('on', start)
            logging.info(('设备连接：'+start))
            
        if(r_line.find('AP-STA-DISCONNECTED') != -1):  # 搜索断开字符
            stop = r_line[27:44]
            mysql('off', stop)
            logging.info(('设备断开：'+stop))
            
