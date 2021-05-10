<?php

namespace app\lang\controller;

use app\common\controller\AdminController;
use app\lang\model\LangModel;
use app\lang\service\LangApiService;
use app\lang\service\LangDictionaryService;
use app\lang\service\LangService;
use think\facade\Request;

/**
 * 后台管理
 * Class Lang
 * @package app\lang\controller
 */
class Lang extends AdminController
{

    public function index()
    {
        $_action = input('_action');
        if (Request::isGet() && $_action == 'getList') {
            return json(LangService::getLangList());
        }
        if (Request::isPost() && $_action == 'delLang') {
            $id = input('post.id');
            return json(LangService::delLang($id));
        }
        return view('index');
    }

    public function addLang()
    {
        $_action = input('_action');
        if (Request::isPost() && $_action == 'addLang') {
            $name = input('post.name');
            $lang = input('post.lang');
            return json(LangService::addLang($name, $lang));
        }
        return view('addLang');
    }

    public function dictionary()
    {
        $_action = input('_action');
        if (Request::isGet() && $_action == 'getList') {
            $page = input('get.page', 1);
            $limit = input('get.limit', 10);
            $key = input('get.key');
            $value = input('get.value');
            return json(LangDictionaryService::getDictionaryList($key, $value, $page, $limit));
        }
        if (Request::isPost() && $_action == 'editValue') {
            $key = input('post.key');
            $lang = input('post.lang');
            $value = input('post.value');
            return json(LangDictionaryService::editValue($key, $lang, $value, LangModel::TYPE_VAR));
        }
        if (Request::isPost() && $_action == 'delDictionary') {
            $key = input('post.key');
            return json(LangDictionaryService::delDictionary($key));
        }
        return view('dictionary');
    }

    public function addDictionary()
    {
        $_action = input('_action');
        if (Request::isGet() && $_action == 'getLangList') {
            return json(LangService::getLangList());
        }
        if (Request::isPost() && $_action == 'addDictionary') {
            $key = input('post.key');
            $values = input('post.values');
            return json(LangDictionaryService::addDictionary($key, $values, LangModel::TYPE_VAR));
        }
        return view('addDictionary');
    }

}
