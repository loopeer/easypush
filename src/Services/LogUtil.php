<?php

namespace Loopeer\EasyPush\Services;

use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LogUtil {

    /**
     * 获取业务日志对象
     * @param $name
     * @param $dir
     * @return Logger
     */
    public static function getLogger($name, $dir = null, $type = 'daily') {
        $logger = new Logger($name);
        $date = date('Ymd', time());
        $file_name = $name . '_' . $date . '.log';
        if ($type == 'single') {
            $file_name = $name.'.log';
        }
        $path = storage_path() . '/logs/' . ($dir ? ($dir . '/') : '') . $file_name;
        $stream = new StreamHandler($path, Logger::INFO, true, 0664);
        $fire_php = new FirePHPHandler();
        $logger->pushHandler($stream);
        $logger->pushHandler($fire_php);
        return $logger;
    }
}
