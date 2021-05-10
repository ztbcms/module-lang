<?php

namespace app\lang\controller;

use app\BaseController;
use app\common\controller\AdminController;
use app\lang\model\LangDictionaryModel;
use app\lang\model\LangModel;
use app\lang\service\LangApiService;
use app\lang\service\LangDictionaryService;
use app\lang\service\LangService;
use think\facade\Request;

/**
 * api
 * Class Api
 * @package app\lang\controller
 */
class Api extends BaseController
{

    public function lang()
    {
        $items = LangModel::field('lang,name')->select()->toArray();
        $data = ['items' => $items];
        return self::makeJsonReturn(true, $data);
    }

    public function dictionary()
    {
        $lang = input('get.lang');
        $key = input('get.key');
        $langApi = new LangApiService($lang);
        $data = $langApi->getTextArr($key);
        return self::makeJsonReturn(true, $data);
    }

    /**
     * 根据给定的语言获取全部的翻译
     */
    function allDictionary()
    {
        //是否指定语言
        $lang = input('get.lang');
        if (empty($lang)) {
            return self::makeJsonReturn(false, null, '请指定语言');
        }
        $result = [];
        $lists = LangDictionaryModel::where('lang', $lang)->select()->toArray();
        if (!empty($lists)) {
            foreach ($lists as $i => $item) {
                $result[$item['key']] = $item['value'];
            }
        }
        return self::makeJsonReturn(true, $result);
    }
}
