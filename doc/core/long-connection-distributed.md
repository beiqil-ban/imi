# 长连接分布式解决方案

imi v2.0 开始，新增了长连接分布式解决方案。

支持在 TCP、WebSocket 长连接服务中，轻松实现原生分布式推送功能。

使用分布式解决方案时，你对连接的绑定、分组、推送等操作，底层都会自动实现逻辑，心智负担极地，可以说是一把梭！

组件引入：`composer require imiphp/imi-workerman-gateway`

Demo: `composer create-project imiphp/project-websocket:~2.0.0`

## 模式

### 消息队列模式

#### AMQP

支持 RabbitMQ 或其他支持 AMQP 协议的消息队列。

使用发布订阅模式，每个消费者（Worker 进程）都是一个绑定到交换机的单独队列

无法准确判断指定连接是否存在于其他服务，需要业务层面自行实现

所有模式都不推荐使用持久化特性，因为没有实际意义

> 此模式仅支持 Swoole

##### AMQP 一把梭模式

无视 routingKey，所有队列全盘接收所有指令，简单易用，但性能较差

**用法：**

项目配置文件：

```php
'imi' => [
    'beans' => [
        'ServerUtil' => 'AmqpServerUtil',
    ],
],
'beans' => [
    'AmqpServerUtil' => [
        // 'amqpName' => null, // amqp 连接名称
        // 交换机配置，同 AMQP 组件的 @Exchange 注解参数
        'exchangeConfig' => [
            'name' => 'imi_server_util_test', // 交换机名
            'type' => \PhpAmqpLib\Exchange\AMQPExchangeType::FANOUT, // fanout 模式
        ],
        // 队列配置，同 AMQP 组件的 @Queue 注解参数
        'queueConfig' => [
            'name'    => 'imi_server_util_', // 每个进程中的队列名前缀，如果是多实例部署，请设为不同的
            'durable' => false, // 非持久化
        ],
        // 'consumerClass' => 'AmqpServerConsumer', // 消费者类，如有需要可以覆盖自己实现
        // 'publisherClass' => 'AmqpServerPublisher', // 发布者类，如有需要可以覆盖自己实现
    ],
],
```

##### AMQP 路由模式

队列根据routingkey接收指定消息，需要配置对应的 GroupHandler、ConnectionContextHandler

每当绑定、解绑 Group、flag，都会去绑定和解绑，对应的交换机、队列、routingkey

消费者只会接收到与自己相关的消息

**用法：**

项目配置文件：

```php
'imi' => [
    'beans' => [
        'ServerUtil' => 'AmqpServerUtil',
    ],
],
'beans' => [
    'ServerGroup' => [
        'groupHandler' => 'GroupAmqp', // 配置对应的 GroupHandler
    ],
    'ConnectionContextStore'   => [
        'handlerClass'  => 'ConnectionContextAmqp', // 配置对应的 ConnectionContextHandler
    ],
    'AmqpServerUtil' => [
        // 'amqpName' => null, // amqp 连接名称
        // 交换机配置，同 AMQP 组件的 @Exchange 注解参数
        'exchangeConfig' => [
            'name' => 'imi_server_util_test', // 交换机名
            'type' => \PhpAmqpLib\Exchange\AMQPExchangeType::DIRECT, // direct 模式
        ],
        // 队列配置，同 AMQP 组件的 @Queue 注解参数
        'queueConfig' => [
            'name'    => 'imi_server_util_', // 每个进程中的队列名前缀，如果是多实例部署，请设为不同的
            'durable' => false, // 非持久化
        ],
        // 'consumerClass' => 'AmqpServerConsumer', // 消费者类，如有需要可以覆盖自己实现
        // 'publisherClass' => 'AmqpServerPublisher', // 发布者类，如有需要可以覆盖自己实现
    ],
],
```

#### Redis

采用 Redis 发布订阅实现，每个服务器连接到 Redis 并订阅。

发布消息时，Redis 会发送给所有订阅的服务器。

服务器再进行消息的推送。

> 此模式仅支持 Swoole

**用法：**

项目配置文件：

```php
'imi' => [
    'beans' => [
        'ServerUtil' => 'RedisServerUtil',
    ],
],
'beans' => [
    'RedisServerUtil' => [
        'redisName' => null, // 配置的 Redis 连接名称，为 null 则使用默认
        'channel' => 'imi:RedisServerUtil:channel', // 发布订阅的频道名，不同服务请设为不同的，以防冲突
    ],
],
```

### 网关模式

#### Workerman Gateway

采用成熟的 Workerman Gateway 实现，除了可以实现分布式消息推送，还支持不断线更新业务代码，尤其适合海量设备下的物联网项目。

此模式由于我们编写的代码，运行在 Worker 进程中，所以需要单独配置 Worker 类型的服务器配置。与纯粹的 Swoole、Workerman 服务配置略有差异。

了解 Workerman Gateway：<http://doc4.workerman.net/>

> 此模式支持 Swoole、Workerman

**Swoole 用法：**

项目配置文件：

```php
[
    // 主服务器配置（子服务器配置同理）
    'mainServer'    => defined('SWOOLE_VERSION') ? [
        'namespace'    => '服务命名空间',
        'type'         => \Imi\WorkermanGateway\Swoole\Server\Type::BUSINESS_WEBSOCKET, // WebSocket 业务服务器
        // 'type'         => \Imi\WorkermanGateway\Swoole\Server\Type::BUSINESS_TCP, // TCP 业务服务器
        'mode'         => \SWOOLE_BASE,
        //网关配置
        'workermanGateway' => [
            // worker 名称，在不同的 worker 实例中必须不同，一般推荐环境变量来修改
            'workerName'           => 'websocketWorker',
            'registerAddress'      => '127.0.0.1:13004', // 注册中心地址
            'worker_coroutine_num' => swoole_cpu_num(), // 每个 Worker 进程中的工作协程数量
            // 待处理任务通道长度
            'channel'              => [
                'size' => 1024,
            ],
        ],
    ] : [],
    'swoole' => [
        'imi' => [
            'beans' => [
                'ServerUtil' => Imi\WorkermanGateway\Swoole\Server\Util\GatewayServerUtil::class,
            ],
        ],
    ],
]
```

配置好后用 `vendor/bin/imi-swoole swoole/start` 启动服务即可。

**Workerman 用法：**

项目配置文件：

```php
[
    // Workerman 服务器配置
    'workermanServer' => [
        // Worker 配置
        // worker 名称 websocket，在不同的 worker 实例中必须不同，一般推荐环境变量来修改
        'websocket' => [
            'namespace'   => '服务命名空间',
            'type'        => Imi\WorkermanGateway\Workerman\Server\Type::BUSINESS_WEBSOCKET, // WebSocket 业务服务器
            // 'type'        => Imi\WorkermanGateway\Workerman\Server\Type::BUSINESS_TCP, // TCP 业务服务器
            'configs'     => [
                'registerAddress' => '127.0.0.1:13004', // 注册中心地址
                'count'           => 2,
            ],
        ],
        // 其它监听端口的服务，可以不要
        'http' => [
            'namespace' => 'Imi\WorkermanGateway\Test\AppServer\ApiServer',
            'type'      => Imi\Workerman\Server\Type::HTTP,
            'host'      => \Imi\env('SERVER_HOST', '127.0.0.1'),
            'port'      => 13000,
            'configs'   => [
                'registerAddress' => '127.0.0.1:13004',
            ],
        ],
    ],
    'workerman' => [
        'imi' => [
            'beans' => [
                'ServerUtil' => Imi\WorkermanGateway\Workerman\Server\Util\GatewayServerUtil::class,
            ],
        ],
    ],
]
```

按上面的配置好后，如果你的网关和注册中心是单独运行的，那么已经可以跑了。

如果你希望用 imi 来启动网关和注册中心，可以参考如下配置。

在 `@app.workermanServer` 配置中加入：

```php
[
    // 注册中心服务
    'register' => [
        'namespace'   => 'Imi\WorkermanGateway\Test\AppServer\Register',
        'type'        => Imi\WorkermanGateway\Workerman\Server\Type::REGISTER,
        'host'        => '0.0.0.0',
        'port'        => 13004,
        'configs'     => [
        ],
    ],
    // 网关服务
    'gateway' => [
        'namespace'   => 'Imi\WorkermanGateway\Test\AppServer\Gateway',
        'type'        => Imi\WorkermanGateway\Workerman\Server\Type::GATEWAY,
        'socketName'  => 'websocket://0.0.0.0:13002',
        'configs'     => [
            'lanIp'           => '127.0.0.1',
            'startPort'       => 12900,
            'registerAddress' => '127.0.0.1:13004',
        ],
    ],
]
```

配置好后用 `vendor/bin/imi-workerman workerman/start` 启动服务即可。
