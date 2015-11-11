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

	; supervisor config file
    
    [unix_http_server]
    file=/var/run/supervisor.sock   ; (the path to the socket file)
    chmod=0700                       ; sockef file mode (default 0700)
    
    [supervisord]
    logfile=/var/log/supervisor/supervisord.log ; (main log file;default $CWD/supervisord.log)
    pidfile=/var/run/supervisord.pid ; (supervisord pidfile;default supervisord.pid)
    childlogdir=/var/log/supervisor            ; ('AUTO' child log dir, default $TEMP)
    
    ; the below section must remain in the config file for RPC
    ; (supervisorctl/web interface) to work, additional interfaces may be
    ; added by defining them in separate rpcinterface: sections
    [rpcinterface:supervisor]
    supervisor.rpcinterface_factory = supervisor.rpcinterface:make_main_rpcinterface
    
    [supervisorctl]
    serverurl=unix:///var/run/supervisor.sock ; use a unix:// URL  for a unix socket
    
    ; The [include] section can just contain the "files" setting.  This
    ; setting can list multiple files (separated by whitespace or
    ; newlines).  It can also contain wildcards.  The filenames are
    ; interpreted as relative to this file.  Included files *cannot*
    ; include files themselves.
    
    [include]
    files = /etc/supervisor/conf.d/*.conf
    
新增配置文件 /etc/supervisor/conf.d/laravel.queue.conf

    [inet_http_server]
    port = 192.168.10.10:9101
    username = admin
    password = 123456
    
    [program:laravel-worker_email]
    process_name=%(program_name)s_%(process_num)02d
    command=php /home/vagrant/code/laravel_queue/artisan queue:work --queue=email --sleep=3 --tries=2 // 命令
    autostart=true
    autorestart=true
    user=vagrant
    numprocs=2    // 进程数
    redirect_stderr=true
    stdout_logfile=/home/vagrant/code/laravel_queue/worker_email.log  // 记录日志
    
    [program:laravel-worker_default]
    process_name=%(program_name)s_%(process_num)02d
    command=php /home/vagrant/code/laravel_queue/artisan queue:work --queue=default --sleep=3 --tries=2
    autostart=true
    autorestart=true
    user=vagrant
    numprocs=1
    redirect_stderr=true
    stdout_logfile=/home/vagrant/code/laravel_queue/worker_default.log

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
	supervisorctl stop laravel-worker*
	supervisorctl start laravel-worker*
	supervisorctl restart laravel-worker*

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