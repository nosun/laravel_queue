## Laravel Events and Queue Test

### 设计目标

使用Laravel做消息系统，事件触发，通过邮件，短信，微信等各种方式通知到事件的相关人员。

### 用例

- 用户注册： 给用户发送通知邮件 
- 用户发布项目： 给甲方下发邮件，微信，短信 
- 项目审核通过： 给甲方下发邮件，微信，短信
- 项目预付款成功： 给甲方下发邮件，微信，短信，给乙方下发短信，邮件，微信。 通知消息；
- 项目争议受理： 给甲方，乙方同时下发邮件，微信，告知相应的处理流程。

### 流程

1、 注册观察者：在配置文件中，注册事件与观察者
2、 事件触发：事件发生，Fire事件，传递事件信息。
3、 事件Handler：事件发生后，观察者负责处理事件，执行时间较长的事务 push 到队列中。
4、 队列worker：JobHandle 负责处理 Job，调用具体的客户端执行。
5、 客户端：短信客户端，微信客户端，邮件客户端，发送信息。

### 对象

#### 队列Worker类
负责发邮件的，负责发短信的，负责发微信的。

    app/commands目录：具体的指令，对具体的操作进行封装。

#### 事件Handler类
事件的观察者，当事件发生是，按照自身的流程处理问题。

    app/Handler/Events，定义事件的处理方式

#### 事件类
定义事件，并对事件的信息进行整理。

    app/Events目录，定义事件。

#### 观察者的注册
将观察者注册到事件上，从而关联事件与观察者。

    app/Providers/EventServiceProviders
    
    $listen = array();
    定义事件及观察者；

    $subscriber = new UserEventHandler;
    Event::subscribe($subscriber);

定义观察者，并做观察者的注册；


### 实现

#### 配置队列服务

设置队列使用Redis非常容易，在app/config/queue.php中配置

    'default' => 'redis',
    ...
    'connections' => array(
        ...
        'redis' => array(
            'driver' => 'redis',
            'queue'  => 'default',
        ),
    ),


#### 开启队列监听

    php artisan queue:listen

- 流程

Laravel利用artisan命令来执行出队操作，然后进行任务的执行。方法调用如下：

    artisan queue:work
    WorkerCommand:fire()
    Worker:pop()
    Worker:getNextJob()
    RedisQueue:pop()
    Worker:process()

#### 注册事件Handler类

    protected $listen = [
    	'App\Events\UserLoggedIn' => [
    		'Yun\Handlers\Events\UserEventHandler@onUserLogin'
    	]
    ];


#### 设置事件类及Handler类

    php artisan event:generate

    生成事件类和事件handler类

    在Handler方法中 调用 Queue


#### 设置队列CMD类

    php artisan make:command SendEmail --queued

    生成队列Cmd类

#### 触发事件




