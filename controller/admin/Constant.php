<?php

namespace app\lang\controller\admin;

use app\common\controller\AdminController;
use app\common\libs\helper\TreeHelper;
use app\lang\model\LangCategoryModel;
use app\lang\model\LangConstantModel;
use app\lang\model\LangModel;
use app\lang\service\LangApiService;
use app\lang\service\LangDictionaryService;
use app\lang\service\LangService;


/**
 * 常量管理
 * Class Constant
 * @package app\lang\controller
 */
class Constant extends AdminController
{
    public function index()
    {
        $_action = input('_action');
        if ($this->request->isGet() && $_action == 'getList') {
            $page = input('get.page');
            $limit = input('get.limit');
            $category_id = input('get.category_id');
            $key = input('get.key');
            $key_name = input('get.key_name');
            $where = [];
            $where[] = ['category_id', '=', $category_id];
            if($key){
                $where[] = ['key', 'like', '%'.$key.'%'];
            }
            if($key_name){
                $where[] = ['key_name', 'like', '%'.$key_name.'%'];
            }
            $langConstantModel = new LangConstantModel();
            $items = $langConstantModel->where($where)->append(['values'])->page($page, $limit)->select()->toArray();
            $total_items = $langConstantModel->where($where)->count();
            $data = [
                'page' => (int)$page,
                'limit' => (int)$limit,
                'items' => $items,
                'total_items' => (int)$total_items,
                'total_pages' => ceil($total_items/$limit)
            ];
            return self::makeJsonReturn(true, $data);
        }
        if ($this->request->isPost() && $_action == 'editValue') {
            $key = input('post.key');
            $lang = input('post.lang');
            $value = input('post.value');
            return json(LangDictionaryService::editValue($key, $lang, $value, LangModel::TYPE_CONST));
        }
        if ($this->request->isPost() && $_action == 'delConstant') {
            $key = input('post.key');
            $res = LangDictionaryService::delDictionary($key);
            if($res['status']){
                LangConstantModel::where('key', $key)->delete();
            }
            return json($res);
        }
        return view();
    }

    public function addConstant()
    {
        $_action = input('_action');
        if ($this->request->isGet() && $_action == 'getLangList') {
            return json(LangService::getLangList());
        }
        if ($this->request->isPost() && $_action == 'addConstant') {
            $category_id = input('post.category_id');
            $key = input('post.key');
            $key_name = input('post.key_name');
            $values = input('post.values');
            $res = LangDictionaryService::addDictionary($key, $values, LangModel::TYPE_CONST);
            if($res['status']){
                LangConstantModel::insert([
                    'category_id' => $category_id,
                    'key' => $key,
                    'key_name' => $key_name
                ]);
            }
            return json($res);
        }
        return view('addConstant');
    }

    public function exportConstant()
    {
        set_time_limit(0);
        $category_id = input('get.category_id');
        $given_lang = input('get.lang');
        if (!empty($given_lang)) {
            $langList = explode(',', $given_lang);
        } else {
            $langList = LangModel::column('lang');
        }

        $constantList = LangConstantModel::field('key')->where('category_id', $category_id)->select();

        $tmp = [];
        foreach ($constantList as $constant) {
            $key = $constant['key'];
            $values = LangApiService::getValues($key);
            $tmp[$key] = $values;
        }

        $data = [];
        foreach ($langList as $lang) {
            foreach($tmp as $k => $t){
                $data[$lang][$k] = $t[ $lang ];
            }
        }

        echo json_encode($data);
        exit;
    }
}