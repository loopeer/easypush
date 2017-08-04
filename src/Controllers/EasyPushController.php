<?php
/**
 * Created by PhpStorm.
 * User: lilhorse
 * Date: 2017/7/27
 * Time: 下午12:08
 */

namespace Loopeer\EasyPush\Controllers;


use Exception;
use Loopeer\EasyPush\Pushes\GetuiPush;
use Loopeer\EasyPush\Pushes\XiaomiPush;
use Loopeer\EasyPush\Services\LogUtil;

class EasyPushController
{
    const CHANNEL_GETUI = 0;
    const CHANNEL_XIAOMI = 1;

    protected $xiaomi;
    protected $getui;
    protected $logger;

    public function __construct()
    {
        $this->xiaomi = new XiaomiPush();
        $this->getui = new GetuiPush();
        $this->getui->setTransmissionType(2);
        $this->logger = LogUtil::getLogger('error', 'easypush');
    }

    public function pushToSingle($push, $title, $content, $custom = [])
    {
        try {
            $clientId = is_array($push) ? $push['client_id'] : $push->client_id;
            $channel = is_array($push) ? $push['channel'] : $push->channel;
            $platform = is_array($push) ? $push['platform'] : $push->platform;
            if($channel == self::CHANNEL_GETUI) {
                $message = $this->setGetuiMessage($title, $content, $platform, $custom);
                $this->getui->pushMessageToSingle($clientId, $message);
            } elseif ($channel == self::CHANNEL_XIAOMI) {
                $message = $this->setXiaomiMessage($title, $content, $custom);
                $this->xiaomi->pushMessageToSingle($clientId, $message);
            }
        } catch (Exception $e) {
            $this->logger->addInfo($e->getMessage());
        }
    }

    public function pushToList($pushes, $title, $content, $custom = [])
    {
        try {
            $gtClientIds = [];
            $xmClientIds = [];
            foreach($pushes as $push) {
                $clientId = is_array($push) ? $push['client_id'] : $push->client_id;
                $channel = is_array($push) ? $push['channel'] : $push->channel;
                if($channel == self::CHANNEL_GETUI) {
                    $gtClientIds[] = $clientId;
                } elseif($channel == self::CHANNEL_XIAOMI) {
                    $xmClientIds[] = $clientId;
                }
            }
            if (count($gtClientIds) > 0) {
                $message = $this->setGetuiMessage($title, $content, 'ios', $custom);
                $this->getui->pushMessageToList($gtClientIds, $message);
            }
            if (count($xmClientIds) > 0) {
                $message = $this->setXiaomiMessage($title, $content, $custom);
                $this->xiaomi->pushMessageToList($xmClientIds, $message);
            }
        } catch (Exception $e) {
            $this->logger->addInfo($e->getMessage());
        }
    }

    public function pushToAll($title, $content, $custom = [])
    {
        try {
            $gtMessage = $this->setGetuiMessage($title, $content, 'ios', $custom);
            $smMessage = $this->setXiaomiMessage($title, $content, $custom);
            $this->getui->pushMessageToAll($gtMessage);
            $this->xiaomi->pushMessageToAll($smMessage);
        } catch (Exception $e) {
            $this->logger->addInfo($e->getMessage());
        }
    }

    protected function setGetuiMessage($title, $content, $platform, $custom)
    {
        if (!empty($custom)) {
            $this->getui->setTransmissionContent($custom);
        }
        $message = $this->getui->setIGtTransmissionTemplate($title, $content, $platform);
        return $message;
    }

    protected function setXiaomiMessage($title, $content, $custom)
    {
        $message = $this->xiaomi->getMessage($title, $content, json_encode($custom));
        return $message;
    }

}