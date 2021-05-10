<?php

namespace app\lang\controller;

use app\common\controller\AdminController;
use app\lang\service\LangApiService;
use app\lang\service\LangDictionaryService;
use app\lang\service\LangService;
use think\facade\Request;

/**
 * 测试
 * Class Test
 * @package app\lang\controller
 */
class Test extends AdminController
{

    public $langApi = null;

    public function test(){
        //实例化调用
        $this->langApi = new LangApiService('zh_cn');
        echo $this->langApi->getText('test');

//        $res = $this->langApi->getTextArr('test.user');
//
//            //静态调用
//        LangApiService::addValue('zh-cn', 'test.test', '二级测试');
//
//        echo LangApiService::getValue('zh-cn', 'test.test');
    }

}
