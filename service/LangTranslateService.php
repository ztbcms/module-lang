<?php
/**
 * User: jayinton
 * Date: 2019-08-14
 * Time: 12:02
 */

namespace app\lang\service;


use app\common\service\BaseService;
use app\lang\model\LangDictionaryModel;
use app\lang\model\LangModel;

/**
 * 翻译服务
 * Class TranslateService
 * @package Translate\Service
 */
class LangTranslateService extends BaseService
{

    private $lang = '';


    /**
     * TranslateService constructor.
     * @param $lang
     */
    public function __construct($lang)
    {
        $this->setLang($lang);
    }

    /**
     * 设置语言
     * @param $lang
     */
    function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * 获取翻译
     * @param string $key key
     * @param array $replaces 变量替换
     * @param string $default 默认值
     * @return array
     */
    function getTranslate($key, $replaces = [], $default = '')
    {
        $value = LangDictionaryModel::where([
            'lang' => $this->lang,
            'key' => $key
        ])->value('value');
        if (empty($value)) {
            $value = $default;
        }
        if (!empty($replaces)) {
            foreach ($replaces as $k => $v) {
                $value = str_replace('{{' . $k . '}}', $v, $value);
            }
        }

        return self::createReturn(true, $value);
    }

    /**
     * 构建key，格式：表+字段+ID
     * @param $table
     * @param $field
     * @param $id
     * @return string
     */
    function _getTableFieldKey($table, $field, $id)
    {
        return $table . '_' . $field . '_' . $id;
    }

    /**
     * 根据表格式(表+字段+ID)获取翻译值
     * @param $table
     * @param $field
     * @param $id
     * @param $lang
     * @return array
     */
    function getTranslateByTableFieldId($table, $field, $id, $lang)
    {
        $key = $this->_getTableFieldKey($table, $field, $id);
        return LangApiService::getValue($lang, $key);
    }

    /**
     * 设置翻译(根据表格式(表+字段+ID))
     * @param $table
     * @param $field
     * @param $id
     * @param $lang
     * @param $value
     * @return array
     */
    function setTranslateByTableFieldId($table, $field, $id, $lang, $value)
    {
        $key = $this->_getTableFieldKey($table, $field, $id);
        return LangApiService::addValue($lang, $key, $value, LangModel::TYPE_VAR);
    }

}