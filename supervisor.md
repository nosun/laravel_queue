### 用Supervisord管理Python进程

Supervisord是用Python实现的一款非常实用的进程管理工具。官网 http://supervisord.org/

### Supervisord安装

	pip install supervisor
	
	or
	
	easy_install supervisor

	or

	download supervisord, and then python setup.py

### 初始化

	# 初始化配置文件
	# 此命令会在 /etc/下创建一个示例配置文件
	echo_supervisord_conf > /etc/supervisord.conf

### 配置文件

	[unix_http_server]
	file = /var/run/supervisor.sock
	chmod = 0777
	chown= root:felinx
	
	[inet_http_server]
	
	port=9001
	username = admin
	password = yourpassword
	
	[supervisorctl]
	; 必须和'unix_http_server'里面的设定匹配
	serverurl = unix:///var/run/supervisord.sock
	
	[supervisord]
	logfile=/var/log/supervisord/supervisord.log ; (main log file;default $CWD/supervisord.log)
	logfile_maxbytes=50MB       ; (max main logfile bytes b4 rotation;default 50MB)
	logfile_backups=10          ; (num of main logfile rotation backups;default 10)
	loglevel=info               ; (log level;default info; others: debug,warn,trace)
	pidfile=/var/run/supervisord.pid ; (supervisord pidfile;default supervisord.pid)
	nodaemon=true               ; (start in foreground if true;default false)
	minfds=1024                 ; (min. avail startup file descriptors;default 1024)
	minprocs=200                ; (min. avail process descriptors;default 200)
	user=root                   ; (default is current user, required if root)
	childlogdir=/var/log/supervisord/; ('AUTO' child log dir, default $TEMP)
	
	[rpcinterface:supervisor]
	supervisor.rpcinterface_factory = supervisor.rpcinterface:make_main_rpcinterface
	
	; 管理的单个进程的配置，可以添加多个program
	[program:chatdemon]
	command=python /home/felinx/demos/chat/chatdemo.py
	autostart = true
	startsecs = 5
	user = felinx
	redirect_stderr = true

	；这对这个program的log的配置，上面的logfile_maxbytes是supervisord本身的log配置
	stdout_logfile_maxbytes = 20MB
	stdoiut_logfile_backups = 20
	stdout_logfile = /var/log/supervisord/chatdemo.log
	
	; 配置一组进程，对于类似的program可以通过这种方式添加，避免手工一个个添加
	[program:groupworker]
	command=python /home/felinx/demos/groupworker/worker.py
	numprocs=24
	process_name=%(program_name)s_%(process_num)02d
	autostart = true
	startsecs = 5
	user = felinx
	redirect_stderr = true
	stdout_logfile = /var/log/supervisord/groupworker.log

### supervisor 运行

	# 运行的时候使用-c指定配置文件
	supervisord -c /etc/supervisord.conf
	# 如果不指定配置文件
	supervisord
	
	# 那么配置文件会依次再下面的文件夹中寻找
	# $CWD/supervisord.conf
	# $CWD/etc/supervisord.conf
	# /etc/supervisord.conf

### 更新配置文件

当我们修改配置后，为了使新的配置生效，我们需要通知supervisor是新配置文件生效，我们使用下面的命令：

	# 使新的配置文件生效
	supervisorctl update

### 其他命令

当supervisor运行后，管理他就不能用supervisord了，而是supervisorctl。下面是一些常用的运行命令

	# 控制所有进程
	supervisorctl start all
	supervisorctl stop all
	supervisorctl restart all
	# 控制目标进程
	supervisorctl stop shadowsocks
	supervisorctl start shadowsocks
	supervisorctl restart shadowsocks

### Supervisord管理

	supervisorctl stop groupworker: 重启所有属于名为groupworker这个分组的进程(start,restart同理)
	supervisorctl reload，载入最新的配置文件，停止原有进程并按新的配置启动、管理所有进程。
	supervisorctl update，根据最新的配置文件，启动新配置或有改动的进程，配置没有改动的进程不会受影响而重启。

注意：显示用stop停止掉的进程，用reload或者update都不会自动重启。

### 开始web服务管理

如果将回环地址127.0.0.1换为服务器的IP地址，就可以可以远程管理supervisor了。

	# 在配置文件后加上服务器配置信息
	[inet_http_server]
	port = 127.0.0.1:9001
	username = user
	password = 123

	# 最后不要忘了reload使之生效！
	supervisorctl reload


### 查询状态

	supervisorctl -c /etc/supervisord.conf status
	shadowsocks   RUNNING   pid 19788, uptime 0:05:40

### 测试

	ps aux |grep ssserver // 查询进程pid
	kill pid // 杀死该进程
	再次查看，会发现，进程又起来了

### 说明

supervisor不能监控daemon进程，所以被监控的程序不能使用deamon模式。

### 部件

Supervisor有不同的部件组成，部件分别负责不同的功能，对进程进行监控和管理。

#### supervisord
Supervisor的server部分称为supervisord。主要负责管理子进程，响应客户端的命令，log子进程的输出，创建和处理不同的事件

#### supervisorctl
Supervisor的命令行客户端。它可以与不同的supervisord进程进行通信，获取子进程信息，管理子进程

#### Web Server
Supervisor的web server，用户可以通过web对子进程进行监控，管理等等，作用与supervisorctl一致。

#### XML-RPC interface
XML-RPC接口，提供XML-RPC服务来对子进程进行管理，监控**