<?php

namespace app\lang\controller\admin;

use app\common\controller\AdminController;
use app\common\libs\helper\TreeHelper;
use app\lang\model\LangCategoryModel;
use app\lang\model\LangConstantModel;


/**
 * 分类管理
 * Class Category
 * @package app\lang\controller
 */
class Category extends AdminController
{
    public function index()
    {
        $_action = input('_action');
        if ($this->request->isGet() && $_action == 'getCategoryList') {
            $project_id = input('project_id');
            $langCategoryModel = new LangCategoryModel();
            $items = $langCategoryModel->field('id,pid,concat(`name`," | ",`key`) as label')->where('project_id', $project_id)->select()->toArray();
            $items = TreeHelper::arrayToTree($items, 0, [
                'idKey' => 'id',
                'parentKey' => 'pid',
                'childrenKey' => 'children',
                'maxLevel' => 0,
                'levelKey' => 'level',
            ]);
            $data = [
                'items' => $items,
            ];
            return self::makeJsonReturn(true, $data);
        }
        if ($this->request->isPost() && $_action == 'delCategory') {
            $category_id = input('category_id');
            $count = LangCategoryModel::where('pid', $category_id)->count();
            if($count){
                return self::makeJsonReturn(false, null, '有子目录,不可删除');
            }
            $count = LangConstantModel::where('category_id', $category_id)->count();
            if($count){
                return self::makeJsonReturn(false, null, '有翻译内容,不可删除');
            }
            $res = LangCategoryModel::where('id', $category_id)->delete();
            return self::makeJsonReturn(true, $res);
        }
        return view();
    }

    public function addCategory()
    {
        $_action = input('_action');
        if ($this->request->isPost() && $_action == 'addCategory') {
            $project_id = input('post.project_id');
            $key = input('post.key');
            $name = input('post.name');
            $pid = input('post.pid');
            $res = LangCategoryModel::insert([
                'project_id' => $project_id,
                'key' => $key,
                'name' => $name,
                'pid' => $pid
            ]);
            return self::makeJsonReturn(true, $res);
        }
        $project_id = input('project_id');
        $langCategoryModel = new LangCategoryModel();
        $items = $langCategoryModel->field('id,pid,name')->where('project_id', $project_id)->select()->toArray();
        $treeList = TreeHelper::arrayToTreeList($items, 0);
        $treeList = array_map(function($v){
            $v['pre'] = '├'.str_repeat('─', $v['level']);
            return $v;
        }, $treeList);
        return view('addCategory')->assign('treeList', $treeList);
    }
}