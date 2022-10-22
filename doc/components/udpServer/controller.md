# UDP 控制器

## 定义

```php
<?php
namespace ImiDemo\UdpDemo\MainServer\Controller;

use Imi\ConnectionContext;
use Imi\Server\UdpServer\Route\Annotation\UdpRoute;
use Imi\Server\UdpServer\Route\Annotation\UdpAction;
use Imi\Server\UdpServer\Route\Annotation\UdpController;

/**
 * 数据收发测试
 * @UdpController
 */
class Test extends \Imi\Controller\UdpController
{
	/**
	 * 登录
	 * 
	 * @UdpAction
	 * @UdpRoute({"action"="hello"})
	 * @return void
	 */
	public function hello()
	{
		return [
			'time'	=>	date($this->data->getFormatData()->format),
		];
	}

}
```

首先控制器类必须有`@UdpController`注解，对应动作必须有`@UdpAction`和`@UdpRoute`注解。

## 注解

### @UdpController

注释目标：类

表明一个类是控制器类

| 属性名称 | 说明 |
| ------------ | ------------ 
| server | 指定当前控制器允许哪些服务器使用。支持字符串或数组，默认为 null 则不限制 |

### @UdpRoute

指定 Udp 路由解析规则。

```php
// 解析 $data['action'] === 'login'
@TcpRoute({"action"="login"})
// 解析 $data['a']['b']['c'] === 'login'
@TcpRoute({"a.b.c"="login"})
// 解析 $data['a'] == '1' && $data['b'] == '2'
@TcpRoute({"a"="1", "b"="2"})
```

当然对象也是支持的：

```php
// 解析 $data->a->b->c === 'login'
@TcpRoute({"a.b.c"="login"})
```

路由匹配成功，就会执行这个动作。

## 动作响应数据

### 响应当前这个请求

直接在方法中返回一个数组或对象，在服务器配置设定的处理器，就会把这个转为对应数据响应给客户端。

**响应数据**

```php
return ['success'=>true];
```

### 分组发送

由于UDP的特性，所以不支持分组发送。如有需要，可根据实际场景自行实现分组。

## 类属性

### $server

详见：<https://doc.imiphp.com/v2.0/core/server.html>

### $data

当然，你还可以直接通过请求上下文代理类，在任意地方使用：

```php
\Imi\Server\UdpServer\Message\Proxy\PacketDataProxy::getFormatData();
```

#### 方法

```php
/**
 * 数据内容.
 */
public function getData(): string;

/**
 * 获取格式化后的数据，一般是数组或对象
 *
 * @return mixed
 */
public function getFormatData();

/**
 * 获取客户端地址
 */
public function getClientAddress(): \Imi\Util\Socket\IPEndPoint;
```
