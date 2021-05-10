<?php

namespace app\lang\controller\admin;

use app\common\controller\AdminController;
use app\lang\model\LangCategoryModel;
use app\lang\model\LangConstantModel;
use app\lang\model\LangModel;
use app\lang\model\LangProjectModel;
use app\lang\service\LangApiService;


/**
 * 项目管理
 * Class Project
 * @package app\lang\controller
 */
class Project extends AdminController
{
    public function index()
    {
        $_action = input('_action');
        if ($this->request->isGet() && $_action == 'getList') {
            $page = $this->request->get('page');
            $limit = $this->request->get('limit');
            $langProjectModel = new LangProjectModel();
            $items = $langProjectModel->page($page, $limit)->select()->toArray();
            $total_items = $langProjectModel->count();
            $data = [
                'page' => (int)$page,
                'limit' => (int)$limit,
                'items' => $items,
                'total_items' => (int)$total_items,
                'total_pages' => ceil($total_items/$limit)
            ];
            return self::makeJsonReturn(true, $data);
        }
        if ($this->request->isPost() && $_action == 'addProject') {
            $name = $this->request->post('name');
            $langProjectModel = new LangProjectModel();
            $res = $langProjectModel->save(['name' => $name]);
            return self::makeJsonReturn(true, $res, '创建成功');
        }
        if ($this->request->isPost() && $_action == 'editProject') {
            $id = $this->request->post('id');
            $name = $this->request->post('name');
            $langProjectModel = new LangProjectModel();
            $res = $langProjectModel->where('id', $id)->save(['name' => $name]);
            return self::makeJsonReturn(true, $res, '修改成功');
        }
        return view();
    }

    public function exportProject()
    {
        set_time_limit(0);
        $project_id = input('get.project_id');
        $given_lang = input('get.lang');
        $where = [];
        if (!empty($given_lang)) {
            $langList = explode(',', $given_lang);
        } else {
            $langList = LangModel::column('lang');
        }

        if(!empty($project_id)){
            $category_ids = LangCategoryModel::where('project_id', $project_id)->column('id');
            $where[] = ['category_id', 'in', $category_ids];
        }
        $constantList = LangConstantModel::field('key')->where($where)->select();

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