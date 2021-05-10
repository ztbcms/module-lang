<?php
/**
 * Created by PhpStorm.
 * User: yezhilie
 * Date: 2021/2/3
 * Time: 18:28
 */

namespace app\lang\model;

use think\Model;

class LangModel extends Model
{
    protected $name = 'tp6_lang';

    const DEFAULT_LANG = 'zh_cn';

    //常量
    const TYPE_CONST = 1;
    //变量
    const TYPE_VAR = 2;
}