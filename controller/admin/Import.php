<?php

namespace app\lang\controller\admin;

use app\common\controller\AdminController;
use app\common\libs\helper\TreeHelper;
use app\lang\model\LangCategoryModel;
use app\lang\model\LangConstantModel;
use app\lang\model\LangDictionaryModel;
use app\lang\model\LangModel;
use app\lang\model\LangProjectModel;
use app\lang\service\LangService;


/**
 * 导入管理
 * Class Import
 * @package app\lang\controller
 */
class Import extends AdminController
{
    public function index()
    {
        $_action = input('_action');
        if($this->request->isGet() && $_action == 'getLangList'){
            return json(LangService::getLangList());
        }
        if($this->request->isGet() && $_action == 'getProjectList'){
            $data = LangProjectModel::select();
            return self::makeJsonReturn(true, $data);
        }
        if($this->request->isGet() && $_action == 'getCategoryList'){
            $project_id = input('get.project_id');
            $data = LangCategoryModel::where('project_id', $project_id)->select()->toArray();
            $data = TreeHelper::arrayToTreeList($data, 0);
            $data = array_map(function($v){
                $v['pre'] = '├'.str_repeat('─', $v['level']);
                return $v;
            }, $data);
            return self::makeJsonReturn(true, $data);
        }
        if($this->request->isPost() && $_action == 'doImport'){
            $project_id = input('post.project_id');
            $category_id = input('post.category_id');
            $lang = input('post.lang');
            $value = input('post.value');
            //json数据解码转化
            $value = preg_replace('/[\x00-\x1F\x80-\x9F]/u', '', trim($value));
            $value = json_decode($value, true);
            if (!$value) {
                return self::makeJsonReturn(false, null, 'json格式异常');
            }
            if (empty($project_id) || empty($lang) || empty($category_id)) {
                return self::makeJsonReturn(false, null, '请完善参数');
            }
            $add_num = 0;
            $update_num = 0;
            $error_num = 0;
            $total_num = 0;
            $error_info = [];
            foreach ($value as $k => $v) {
                $total_num++;

                //检查该字段是否存在
                $constant = LangConstantModel::where([
                    ['key', '=', $k]
                ])->findOrEmpty();
                if ($constant->isEmpty()) {
                    //添加
                    $constant->category_id = $category_id;
                    $constant->key = $k;
                    $constant->key_name = $k;
                    $constant->save();

                    $add_num++;
                } else {
                    if($constant['category_id'] == $category_id){
                        //更新
                        $constant->category_id = $category_id;
                        $constant->key = $k;
                        if($lang == 'zh_cn' || $lang == 'cn'){
                            $constant->key_name = $k;
                        }
                        $constant->save();

                        $update_num++;
                    }else{
                        //错误
                        $error_info[] = [
                            'key' => $k,
                            'value' => $v,
                            'msg' => '该KEY已被其他文档使用'
                        ];
                        $error_num++;

                        continue;
                    }
                }
                //更新dictionary
                $dictionary = LangDictionaryModel::where([
                    ['key', '=', $k],
                    ['lang', '=', $lang]
                ])->findOrEmpty();
                $dictionary->type = LangModel::TYPE_CONST;
                $dictionary->key = $k;
                $dictionary->lang = $lang;
                $dictionary->value = $v;
                $dictionary->save();
            }
            $msg = '添加条数：' . $add_num . "<br>更新条数：" . $update_num . "<br>错误条数：" . $error_num;
            if($error_num > 0){
                $msg .= '<br><br>';
                foreach($error_info as $error){
                    $msg .= $error['key'].'：'.$error['msg'].'<br>';
                }
            }
            return self::makeJsonReturn(true, null, $msg);
        }
        return view();
    }

}