<?php

namespace App\Controllers;


use CodeIgniter\HTTP\Request;
use App\Models\Categories;
use CodeIgniter\Session\Session;

class Home extends BaseController
{

    public $categoriesModel;

    public function index()
    {
        return view('welcome_message');
    }

    public function viewForm()
    {
        $model = new Categories();

        $mainCategories = $model->select(['id', 'name'])->where('parent_id', null)->findAll();
        
        return view('form', [
            'mainCategories' => $mainCategories
        ]);
    }

    public function createCategory()
    {
        $rules = [
            'sub_category' => 'required'
        ];

        if (!$this->validate($rules)) {
            return view('form', [
                'validation' => $this->validator,
            ]);
        }

        //initialize model
        $model = new Categories();

        $parentId = null;
        $mainCategory = $this->request->getPost('main_category');
        $subCategory = $this->request->getPost('sub_category');

        if ($mainCategory) {
            $existingSubCategory = $model->where([
                'name' => $subCategory,
                'parent_id' => $mainCategory
            ])->find();

            if (!empty($existingSubCategory)) {
                return "Duplicate Entry for Subcategory, Please go back and try again";
                die;
            }
        }

        if (!$mainCategory) {

            $existingMainCategory = $model->where([
                'name' => $subCategory,
                'parent_id' => null,
            ])->find();

            if (!empty($existingMainCategory)) {
                return "Duplicate Entry for Main Category, Please go back and try again";
                die;
            }

            $name = $subCategory;
        } else {
            $parentId = $mainCategory;
            $name = $subCategory;
        }

        $data = [
            'name' => $name,
            'parent_id' => $parentId,
        ];


        if ($model->insert($data, false)) {
            redirect()->back();
        }
    }
}
