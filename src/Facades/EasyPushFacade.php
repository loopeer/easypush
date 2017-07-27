<?php
/**
 * Created by PhpStorm.
 * User: lilhorse
 * Date: 2017/7/27
 * Time: 上午11:58
 */

namespace Loopeer\EasyPush\Facades;


use Illuminate\Support\Facades\Facade;

class EasyPushFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'easypush';
    }
}