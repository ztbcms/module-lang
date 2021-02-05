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

/**
 * 多语言管理
 * Class LangService
 * @package app\lang\service
 */
class LangService extends BaseService
{

    /**
     * 获取语言列表
     * @return array
     */
    static function getLangList()
    {
        $LangModel = new LangModel();
        $list = $LangModel->select()->toArray();
        return self::createReturn(true, $list, '获取成功');
    }

    /**
     * 添加语言
     * @param $name
     * @param $lang
     * @return array
     */
    static function addLang($name, $lang)
    {
        if(!$lang){
            return self::createReturn(false, null, '请输入代码');
        }
        $LangModel = new LangModel();
        $is_empty = $LangModel->where('lang', $lang)->findOrEmpty()->isEmpty();
        if(!$is_empty){
            return self::createReturn(false, null, '代码已存在');
        }
        if(!$name){
            return self::createReturn(false, null, '请输入名称');
        }
        $res = $LangModel->create(['name' => $name, 'lang' => $lang]);
        return self::createReturn(true, $res, '添加成功');
    }

    /**
     * 删除语言
     * @param $id
     * @return array
     */
    static function delLang($id)
    {
        $LangModel = new LangModel();
        $res = $LangModel->where('id', $id)->delete();
        return self::createReturn(true, $res, '删除成功');
    }


}
