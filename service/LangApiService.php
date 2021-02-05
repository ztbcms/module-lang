<?php
/**
 * Created by PhpStorm.
 * User: yezhilie
 * Date: 2021/2/3
 * Time: 18:26
 */

namespace app\lang\service;

use app\common\service\BaseService;
use app\lang\model\LangModel;
use app\lang\model\LangDictionaryModel;

/**
 * 多语言接口
 * Class LangApiService
 * @package app\lang\service
 */
class LangApiService extends BaseService
{

    //当前的语言
    private $lang = '';

    public function __construct($lang = null)
    {
        if($lang){
            $is_empty = LangModel::where('lang', $lang)->findOrEmpty()->isEmpty();
            if($is_empty){
                $lang = LangModel::value('lang');
            }
        }else{
            $lang = LangModel::value('lang');
        }
        $this->lang = $lang;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getText($key){
        $LangDictionaryModel = new LangDictionaryModel();
        return $LangDictionaryModel->where([
            ['lang', '=', $this->lang],
            ['key', '=', $key]
        ])->value('value');
    }

    /**
     * 通过KEY前缀查询
     * @param $key
     * @param $op
     * @return mixed
     */
    public function getTextArr($key, $op = '.'){
        $LangDictionaryModel = new LangDictionaryModel();
        return $LangDictionaryModel->field('key,value')->whereOr([
            [['key', 'like', $key.$op.'%'], ['lang', '=', $this->lang]],
            [['key', '=', $key], ['lang', '=', $this->lang]]
        ])->column('value', 'key');
    }

    /**
     * 编辑字典数据
     * @param $lang
     * @param $key
     * @param $value
     * @return array
     */
    static public function addValue($lang, $key, $value){
        $LangDictionaryModel = new LangDictionaryModel();
        $dictionary = $LangDictionaryModel->where([
            ['key', '=', $key],
            ['lang', '=', $lang],
        ])->findOrEmpty();
        if($dictionary->isEmpty()){
            $res = $LangDictionaryModel->create([
                'key' => $key,
                'lang' => $lang,
                'value' => $value
            ]);
        }else{
            $res = $dictionary->save(['value' => $value]);
        }
        return self::createReturn(true, $res, '添加成功');
    }

    /**
     * 获取字典数据
     * @param $lang
     * @param $key
     * @return mixed
     */
    static public function getValue($lang, $key){
        $LangDictionaryModel = new LangDictionaryModel();
        return $LangDictionaryModel->where([
            ['lang', '=', $lang],
            ['key', '=', $key]
        ])->value('value');
    }
}
