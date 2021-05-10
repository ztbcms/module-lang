<?php
/**
 * Created by PhpStorm.
 * User: yezhilie
 * Date: 2021/2/3
 * Time: 18:26
 */

namespace app\lang\service;

use app\common\service\BaseService;
use app\lang\model\LangDictionaryModel;
use app\lang\model\LangModel;

/**
 * 多语言管理
 * Class LangDictionaryService
 * @package app\lang\service
 */
class LangDictionaryService extends BaseService
{

    /**
     * 获取字典列表
     * @param $key
     * @param $value
     * @param $page
     * @param $limit
     * @return array
     */
    static function getDictionaryList($key, $value, $page, $limit)
    {
        $where = [
            ['key', 'like', '%'.$key.'%'],
            ['value', 'like', '%'.$value.'%'],
        ];
        $LangDictionaryModel = new LangDictionaryModel();
        $keyArr = LangDictionaryModel::where($where)->distinct(true)->page($page, $limit)->column('key');
        $list = $LangDictionaryModel->whereIn('key', $keyArr)->select()->toArray();

        $lang = LangModel::column('lang');
        $langName = LangModel::column('name', 'lang');
        $values = [];
        foreach($lang as $l){
            $values[$l] = [
                'lang' => $l,
                'name' => $langName[$l],
                'value' => ''
            ];
        }
        $res = [];
        foreach($list as $val){
            $key = $val['key'];
            if(!isset($res[ $key ])){
                $res[ $key ] = ['key' => $key, 'values' => $values];
            }
            if(in_array($val['lang'], $lang)){
                $val['name'] = $langName[ $val['lang'] ];
                $res[ $key ]['values'][$val['lang']] = $val;
            }
        }
        $res = array_values($res);
        foreach($res as &$v){
            $v['values'] = array_values($v['values']);
        }

        $total_items = LangDictionaryModel::where($where)->group('key')->count();
        $total_page = ceil($total_items / $limit);
        return self::createReturnList(true, $res, $page, $limit, $total_items, $total_page);
    }

    /**
     * 编辑翻译数据
     * @param $key
     * @param $lang
     * @param $value
     * @param $type
     * @return array
     */
    static function editValue($key, $lang, $value, $type = LangModel::TYPE_CONST)
    {
        $LangDictionaryModel = new LangDictionaryModel();
        $dictionary = $LangDictionaryModel->where([
            ['key', '=', $key],
            ['lang', '=', $lang],
        ])->findOrEmpty();
        if($dictionary->isEmpty()){
            $res = $LangDictionaryModel->create([
                'key' => $key,
                'lang' => $lang,
                'value' => $value,
                'type' => $type
            ]);
        }else{
            $res = $dictionary->save(['value' => $value]);
        }
        return self::createReturn(true, $res, '添加成功');
    }

    /**
     * 删除数据
     * @param $key
     * @return array
     */
    static function delDictionary($key)
    {
        $LangDictionaryModel = new LangDictionaryModel();
        $res = $LangDictionaryModel->where('key', $key)->delete();
        return self::createReturn(true, $res, '删除成功');
    }

    static function addDictionary($key, $values, $type = LangModel::TYPE_CONST){
        if(!$key){
            return self::createReturn(false, null, '请输入KEY');
        }
        $LangDictionaryModel = new LangDictionaryModel();
        $is_empty = $LangDictionaryModel->where('key', $key)->findOrEmpty()->isEmpty();
        if(!$is_empty){
            return self::createReturn(false, null, 'KEY已存在');
        }
        if(!$values){
            return self::createReturn(false, null, '请输入翻译内容');
        }
        $data = [];
        foreach($values as $k => $value){
            $data[] = [
                'lang' => $k,
                'key' => $key,
                'value' => $value,
                'type' => $type
            ];
        }
        $res = $LangDictionaryModel->saveAll($data);
        return self::createReturn(true, $res, '添加成功');
    }
}
