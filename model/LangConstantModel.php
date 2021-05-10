<?php
/**
 * Created by PhpStorm.
 * User: yezhilie
 * Date: 2021/2/3
 * Time: 18:28
 */

namespace app\lang\model;

use think\Model;

class LangConstantModel extends Model
{
    protected $name = 'tp6_lang_constant';

    public function getValuesAttr($value, $data){
        $lang = cache('lang.lang');
        if(is_null($lang)){
            $lang = LangModel::column('lang');
            cache('lang.lang', $lang);
        }
        $langName = cache('lang.langName');
        if(is_null($langName)){
            $langName = LangModel::column('name', 'lang');
            cache('lang.langName', $langName);
        }
        $values = [];
        foreach($lang as $l){
            $values[$l] = [
                'lang' => $l,
                'name' => $langName[$l],
                'value' => ''
            ];
        }
        $list = LangDictionaryModel::where([['key', '=', $data['key']]])->select()->toArray();
        foreach($list as $val){
            $val['name'] = $langName[ $val['lang'] ];
            $values[$val['lang']] = $val;
        }
        return array_values($values);
    }

}