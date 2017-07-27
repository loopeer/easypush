# EasyPush

## Configuration
1. 注册 `ServiceProvider`和`Facade`:
 
  ```php
  Loopeer\EasyPush\Providers\EasyPushProvider::class,
  ```
  
  ```php
   'EasyPush' => \Loopeer\EasyPush\Facades\EasyPushFacade::class,
  ```

2. 发布配置文件  
  
  ```shell
  php artisan vendor:publish
  ```
  
  发布后请修改`app/config/easypush.php` 中对应的配置项

## Usage
1. 对单个用户推送消息

```php
app('easypush')->pushToSingle($push, $title, $content, $custom);
//或
EasyPush::pushToSingle($push, $title, $content, $custom);
```
2. 对多个用户推送消息

```php
app('easypush')->pushToList($pushes, $title, $content, $custom);
//或
EasyPush::pushToList($pushes, $title, $content, $custom);
```
3. 全局推送消息

```php
app('easypush')->pushToAll($title, $content, $custom);
//或
EasyPush::pushToAll($title, $content, $custom);
```

## 参数说明
* push: 推送参数，格式为对象或数组
    * channel: 所属渠道（0-个推, 1-小米）
    * client_id: 推送渠道用户id
    * platform: 设备(ios, android)
* title: 推送标题
* content: 推送内容
* custom: 自定义推送内容