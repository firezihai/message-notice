# message-notice

## 功能

- 用于发送应用异常、工作等通知
- 支持多平台,如钉钉、企业微信
- 支持自定义发送平台


## 安装

```
composer require firezihai/message-notice -vvv

```

## 使用

```
   $config = [
        'default' => [
            'drvier' => QyWechat::class,
            'app_name'=>'测试应用',
            'app_key' => '',
            'app_secret' => '',
            'agent_id' => '1000001',
        ],
    ];
  
    $factory =  MesasgeFactory::create($config['default']);
    $factory->send(['firezihai'], '测试内容');

```

## 配置多个消息通知平台

当你需要多个平台发送不同的消息通知时，可以配置多个平台

1. 配置

```

  'default' => [
        'drvier' => QyWechat::class,
        'app_name'=>'测试应用',
        'app_key' => '',
        'app_secret' => '',
        'agent_id' => '1000001',
    ],
    'dingtalk' => [
        'drvier' => Dingtalk::class,
        'app_name'=>'测试应用',
        'app_key' => '',
        'app_secret' => '',
        'agent_id' => '1000001',
    ],

```

2. 使用

```
    $factory =  MesasgeFactory::create($config['dingtalk']);
    $factory->send(['firezihai'], '测试内容');

```

## 自定义消息通知平台

实现 `MessageInterface` 接口

1. 编写平台驱动类

```
class FeiShu implements MessageInterface
{

     /**
     * 发送消息.
     */
    public function send(array $userId, string $message)
    {

    }

}


```

2. 配置驱动类

配置新的驱动类

```


    'feishu' => [
        'drvier' => Dingtalk::class,
        'app_name'=>'测试应用',
        'app_key' => '',
        'app_secret' => '',
        'agent_id' => '1000001',
    ],


```

## 自定义 token 储存方式

默认使用 文件 储存,如果你想使用数据库储存,可自定义储存驱动,只要实现 `AccessTokenInterface` 类即可.

1. 编写储存类

```

Db implements AccessTokenInterface
{
    private $config;
	public function __construct($config)
    {
        $this->config = $config;
    }
	// $app 配置文件中的app配置项
    public function getToken()
    {

    }

    public function refreshToken()
    {

    }
}


```

2. 配置

在配置中配置新的储存驱动类

```


    'default' => [
        // 其他配置
        ....
        'access_token' => DB::class,
         // 其他配置
    ],


```
