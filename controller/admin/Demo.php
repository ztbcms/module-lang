<?php

namespace app\lang\controller\admin;

use app\common\controller\AdminController;
use app\lang\model\LangDemoCarModel;
use app\lang\model\LangModel;
use app\lang\service\LangService;
use app\lang\service\LangTranslateService;


/**
 * Class Demo
 * @package app\lang\controller
 */
class Demo extends AdminController
{

    /**
     * 语言切换示例
     */
    function lang_switch()
    {
        return view();
    }

    /**
     * 实时切换实例
     */
    function fetch_lang_switch()
    {
        return view();
    }

    function demo_edit_car()
    {
        $langList = LangService::getLangList()['data'];
        return view()->assign('langList', $langList);
    }

    function demo_car_list()
    {
        return view();
    }

    /**
     * 添加或编辑车辆
     */
    function doAddEditCar()
    {
        $form = input('post.');

        $id = $form['id'];
        unset($form['id']);
        //需要多语言处理的字段
        $translate_fields = ['model', 'description'];
        $translate_field_values = [];
        //构建默认语言的 form
        foreach ($translate_fields as $field) {
            $translate_field_values[$field] = $form[$field];
            $form[$field] = isset($form[$field][LangModel::DEFAULT_LANG]) ? $form[$field][LangModel::DEFAULT_LANG] : '';//默认中文
        }

        if ($id) {
            $form['update_time'] = time();
            $res = LangDemoCarModel::where('id', $id)->save($form);
        } else {
            $form['input_time'] = time();
            $form['update_time'] = time();
            $res = LangDemoCarModel::insertGetId($form);
            $id = $res;
        }

        $tableName = (new LangDemoCarModel())->getName();
        if (!$res) {
            return self::makeJsonReturn(false, null, '操作失败');
        }
        foreach ($translate_field_values as $field => $field_value) {
            foreach ($field_value as $lang => $value) {
                $translateService = new LangTranslateService($lang);
                $translateService->setTranslateByTableFieldId($tableName, $field, $id, $lang, $value);
            }
        }

        return self::makeJsonReturn(true, null, '操作成功');
    }

    /**
     * 获取车辆详情
     */
    function getCarDetail(){
        $id = input('get.id');
        $res = LangDemoCarModel::where('id', $id)->findOrEmpty();
        if($res->isEmpty()){
            return self::makeJsonReturn(false, null, '找不到信息');
        }
        $data = $res->toArray();

        $LangList = LangService::getLangList()['data'];
        //需要多语言处理的字段
        $translate_fiselds = ['model','description'];
        $tableName = (new LangDemoCarModel())->getName();
        foreach($translate_fiselds as $field){
            $dict = [];
            foreach($LangList as $langInfo){
                $lang = $langInfo['lang'];
                $translateService = new LangTranslateService($lang);
                $value = $translateService->getTranslateByTableFieldId($tableName, $field, $id, $lang);
                $dict[$lang] = $value;
            }
            $data[$field] = $dict;
        }
        return self::makeJsonReturn(true, $data);
    }

    function getCarList()
    {
        //指定获取分页结果的第几页
        $page = input('page', 1);
        $limit = input('limit', 20);

        $where = [];
        $items = LangDemoCarModel::where($where)->page($page, $limit)->select()->toArray();
        $total_items = LangDemoCarModel::where($where)->count();

        $data = [
            'page' => (int)$page,
            'limit' => (int)$limit,
            'items' => $items,
            'total_items' => (int)$total_items,
            'total_pages' => ceil($total_items/$limit)
        ];
        return self::makeJsonReturn(true, $data);
    }

    /**
     * 使用 TranslateService 示例
     */
    function doRequestAdmin()
    {
        $lang = input('lang');
        $key = 'demo_msg';
        $replaces = ['balance' => 1, 'integral' => 2];
        $default = '余额: {{balance}} 积分:{{integral}}';

        $translateService = new LangTranslateService($lang);
        $msg = $translateService->getTranslate($key, $replaces, $default)['data'];
        return self::makeJsonReturn(true, $msg, $msg);
    }
}