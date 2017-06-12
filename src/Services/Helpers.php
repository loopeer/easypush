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
    public static function printResult($result) {
        $logger = LogUtil::getLogger('easypush', 'easypush');
        $logger->addInfo('result = ' . $result);
    }
}
