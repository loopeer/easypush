<?php

namespace Loopeer\EasyPush\Services;

use Loopeer\QuickCms\Services\Utils\LogUtil;

/**
 * 帮助模块
 * Class Helpers
 */
class Helpers
{
    /**
     * 打印推送结果
     * @param $result
     */
    public static function printResult($result, $channel)
    {
        if ($channel == 'gt' || $channel == 'xm') {
            $resultStr = json_encode($result);
            $logger = LogUtil::getLogger($channel, 'easypush');
            $logger->addInfo($resultStr);
        }
    }
}
