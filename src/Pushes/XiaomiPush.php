<?php
/**
 * Created by PhpStorm.
 * User: lilhorse
 * Date: 2017/6/10
 * Time: 下午11:41
 */

namespace Loopeer\EasyPush\Pushes;


use Loopeer\EasyPush\Services\Helpers;
use xmpush\Builder;
use xmpush\Constants;
use xmpush\Sender;

class XiaomiPush
{
    protected $appId;
    protected $appKey;
    protected $appSecret;
    protected $appPackage;

    public function __construct()
    {
        $this->appId = config('easypush.xiaomi.app_id');
        $this->appKey = config('easypush.xiaomi.app_key');
        $this->appSecret = config('easypush.xiaomi.app_secret');
        $this->appPackage = config('easypush.xiaomi.app_package');
        Constants::setSecret($this->appSecret);
        Constants::setPackage($this->appPackage);
    }

    public function pushMessageToSingle($clientId, $message)
    {
        $sender = new Sender();
        $result = $sender->send($message, $clientId);
        Helpers::printResult($result);
    }

    public function pushMessageToList($clientIds, $message)
    {
        $sender = new Sender();
        $result = $sender->sendToIds($message, $clientIds);
        Helpers::printResult($result);
    }

    public function pushMessageToAll($message)
    {
        $sender = new Sender();
        $result = $sender->broadcastAll($message);
        Helpers::printResult($result);
    }

    public function getMessage($title, $content, $payload = '', $notifyId = null)
    {
        $message = $this->setMessage($title, $content, $payload, $notifyId);
        return $message;
    }

    protected function setMessage($title, $content, $payload = '', $notifyId = null)
    {
        $message = new Builder();
        $message->title($title);//标题
        $message->description($content);//内容
        $message->passThrough(0);//0=通知栏消息 1=透传
        $message->payload($payload); // 对于预定义点击行为，payload会通过点击进入的界面的intent中的extra字段获取，而不会调用到onReceiveMessage方法。
        $message->extra(Builder::notifyEffect, 1); // 此处设置预定义点击行为，1为打开app
        $message->extra(Builder::notifyForeground,1);
        if (is_null($notifyId)) {
            $message->notifyId(microtime(true) * 10000);
        } else {
            $message->notifyId($notifyId);
        }
        $message->build();
        return $message;
    }


}