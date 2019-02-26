# 极光推送在 Laravel 通知的支持

我们在开发针对国内运营的时候进行需要使用过程的几家推送，极光推送则是其中之一。这个包就可以让你方便的在你构件的 Laravel 应用中进行极光推送的使用。

## 前提

安装驱动需要以下条件：

- PHP `>=` 7
- Laravel `>=` 5.5

## 安装

在你的 Laravel 应用目录执行 Composer 进行安装：

```
composer require medz/laravel-jpush-notification-channel
```

> 包中依赖了匹配的 `jpush/jpush` 依赖版本为 `^3.6`，你已经依赖了更低版本的不兼容版本包，使用的时候要小心了！

## 使用

我们已**用户为例**，这里使用 `laravel/laravel` 创建的默认应用模型位置。

### 数据模型

在用户模型中进行配置，创建一个 `routeNotificationForJpush` 方法在模型上：

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Medz\Laravel\Notifications\JPush\Sender as JPushSender;

class User extends Authenticatable
{
    /**
     * Get Notification for JPush sender.
     * @return \Medz\Laravel\Notifications\JPush\Sender
     */
    protected function routeNotificationForJpush()
    {
        return new JPushSender([
            'platform' => 'all',
            'audience' => [
                'alias' => sprintf('user_%d', $this->id),
            ],
        ]);
    }
}
```

这里我们返回一个 `Medz\Laravel\Notifications\JPush\Sender` 实例，可以使用构造参数快速配置，如同上面一样，也可以使用链式调用进行配置。链式调用的 API 如下：

- `setPlatform` 设置平台，值有 `all`、`winphone`、`android` 和 `ios`
- `setAudience` 推送目标进行设置

> `setAudience` 方法或者构造参数中的 `audience` 设置参考：[推送目标](https://docs.jiguang.cn/jpush/server/push/rest_api_v3_push/#audience)文档。

### 通知类

一般我们写通知大概都是通过 `php artisan make:notification` 进行创建的，存放在 `app/Notifications/` 目录下，假设我们现在有一个评论通知类 `CommentNotification.php` 我们仅需在里面增加下面的代码：

```php
<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Medz\Laravel\Notifications\JPush\Message as JPushMessage;

class CommentNotification extends Notification
{
    public function toJpush($notifiable): JPushMessage
    {
        $message = new JPushMessage();
        // TODO

        /*
            ====== 把所有的配置都进行配置 ===
            $message->setAlert('Alert.'); // 简单地给所有平台推送相同的 alert 消息

            // 自定义消息
            $message->setMessage('Message', [
                'title' => '', // 通知标题，会填充到 toast 类型 text1 字段上
                '_open_page' => '', 点击打开的页面名称
                'extras' => [], // 自定义的数据内容
            ]);

            // iOS 通知
            $message->setNotification(JPushMessage::IOS, 'Alert 内容', [
                'alert' => '', // 覆盖第二个参数的 Alert 内，推荐不传,
                'sound' => '', // 表示通知提示声音，默认填充为空字符串
                'badge' => '', // 表示应用角标，把角标数字改为指定的数字；为 0 表示清除，支持 '+1','-1' 这样的字符串，表示在原有的 badge 基础上进行增减，默认填充为 '+1'
                /// ...
            ])

            // 更多通知请参考 https://docs.jiguang.cn/jpush/server/push/rest_api_v3_push/#notification 官方文档
            // 使用 `setNotification` 方法第一个常量有三个： IOS/ANDROID/WP

            // 可选参数
            $message->setOptions([]); // 参考 https://docs.jiguang.cn/jpush/server/push/rest_api_v3_push/#options
        */

        return $message
    }
}
```

完成上面的配置后，就可以推送了，记得在 `via` 方法中返回 `jpush` 这个值哈，例如：

```php
public function via()
{
    return ['database', 'jpush'];
}
```

## License

这个包采用 MIT License 开源。

