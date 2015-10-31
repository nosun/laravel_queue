## Laravel Events and Queue Test

### 设计目标

使用Laravel做消息系统，事件触发，通过邮件，短信，微信等各种方式通知到事件的相关人员。

### 用例

用户登录：给用户发送通知邮件

#### 完整流程

- 用户登录，触发用户登录事件;





### 流程

1. 注册观察者：在配置文件中，注册事件与观察者
2. 事件触发：事件发生，Fire事件，传递事件信息。
3. 事件Handler：事件发生后，观察者负责处理事件，执行时间较长的事务 push 到队列中。
4. 队列worker：JobHandle 负责处理 Job，调用具体的客户端执行。
5. 客户端：短信客户端，微信客户端，邮件客户端，发送信息。

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

##### 事件属性

1. 事件名称：自身信息
2. 通知级别：通知级别
3. 事件信息：事件实体，不同的事件信息不同。

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



## Notify System

消息系统，主要用于业务流程中的信息传递，籍由信息处理对业务流程进行推动。消息系统成为其间非常重要的环节。 比如项目申请，项目审批，项目运转，项目验收，中间的有些流程需要由主管审批，有些环节需要只会项目参与者进行处理。

消息系统，作为一个独立的系统同时可以为其他业务需求服务，比如给特定的用户批量的发送邮件，短信，通知等。

### 消息分类

#### 按照操作性质划分

从用户的角度来说，可以按照操作性质划分为两类

1、通知&&提醒
您的请假单已被核准，单击这里查看。

2、任务&&待办
消息的内容可能像这样:"您有2张请假单需要签核，单击这里处理"。

#### 按照级别来划分

从系统的角度来说，可以按照重要性，优先级来划分

1、紧急：需要立刻知会对方，并以快速的channel通知到对方，比如涉及到财务安全，账号安全的情况。
2、重要：需要立刻知会对方，并确保对方在一定时间范围内有响应，如果在指定时间内没有响应，会再次给对方发送消息，或者自动提升消息的级别。
3、一般：需要只会对方，时间上不做要求，如果事件发生在系统繁忙的时候，可以统一到系统负载低的时候再统一处理。

### 发送时机

1、立刻发送：一般情况下，当发送消息的事件被触发之后，会立刻发送通知。
2、延迟发送：如果通知的级别低，可以采用延迟发送的策略。

### 消息的生命周期

1、产生消息：由事件触发，由事件Handler生产消息。
2、发送消息：由消息系统分发消息，由具体的channel负责实际发送
3、处理消息：由用户的操作触发，直接操作消息的状态。
4、删除消息：由系统定期的清除已经过期的消息。

### 消息渠道
不同的事件，不同的级别，使用不同的渠道组合，不同的渠道，使用不同的消息模板。
- 短信
- 邮件
- 微信
- 站内信

### 消息体——协议？

message：
{
	message_id: int  （just id）
	channel_id: int  （电邮，微信）
	message_type: int (通知，待办)
	push_type:（单发，群发）
	tag:string（标签）
	from:（system|admin）
	to:（receiver）
	type:(event|message)
	level:()
	status:(等待发送|发送成功|发送失败）
	content:{title:title,content:{data}}
	timestamp:
}

### 数据库设计

- 增加 user_notify_setting
- 增加 notify_rule
- 修改 event 表，增加type 字段
- 修改 notify_log 表，曾家 type 字段
- 增加 message表，站内信

### Filter 类

- 针对用户，channel，返回true，false集合，
- 每个filter，就是一个规则，和数据库中的notify_rule对应