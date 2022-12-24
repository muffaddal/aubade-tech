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
        $model = new Categories();

        $query = $model->query('SELECT sub_category.id as s_id, sub_category.name as s_cat_name, p_category.name as p_cat_name 
        FROM categories sub_category 
        LEFT JOIN categories p_category ON p_category.id = sub_category.parent_id 
        ORDER BY sub_category.id');

        $data = $query->getResult();

        return view('welcome_message', [
            'data' => $data,
        ]);
    }

    public function viewForm()
    {
        $model = new Categories();

        //return redirect()->back();

        $mainCategories = $model->select(['id', 'name'])->where('parent_id', null)->findAll();

        return view('form', [
            'mainCategories' => $mainCategories
        ]);
    }

    public function createCategory()
    {
        //Validate POST Request
        $this->validateCategoryRequest();

        //initialize model & POST request
        $model = new Categories();
        $mainCategory = $this->request->getPost('main_category');
        $subCategory = $this->request->getPost('sub_category');

        //Validate SubCategory
        $existingSubCategory = $this->validateSubcategory($mainCategory, $subCategory, $model);

        if (!empty($existingSubCategory)) {
            return redirect()->back()->with('msg', "Duplicate Entry for Subcategory, Please go back and try again");
        }

        //Validate MainCategory
        $existingMainCategory = $this->validateMainCategory($mainCategory, $subCategory, $model);

        if (!empty($existingMainCategory)) {
            return redirect()->back()->with('msg', "Duplicate Entry for Main Category, Please go back and try again");
        }

        //Prepare DATA to Save
        $data = $this->prepareCategoryData($mainCategory, $subCategory);


        //Save
        return $this->saveCategoryData($data, $model);
    }

    protected function validateCategoryRequest()
    {
        $rules = [
            'sub_category' => 'required'
        ];

        if (!$this->validate($rules)) {
            return view('form', [
                'validation' => $this->validator,
            ]);
        }
    }

    protected function validateSubcategory($mainCategory, $subCategory, $model)
    {
        if ($mainCategory) {
            $existingSubCategory = $model->where([
                'name' => $subCategory,
                'parent_id' => $mainCategory
            ])->find();

            return $existingSubCategory;
        }
    }

    protected function validateMainCategory($mainCategory, $subCategory, $model)
    {
        if (!$mainCategory) {
            $existingMainCategory = $model->where([
                'name' => $subCategory,
                'parent_id' => null,
            ])->find();

            return $existingMainCategory;
        }
    }

    protected function prepareCategoryData($mainCategory, $subCategory)
    {
        $parentId = null;

        if (!$mainCategory) {

            $name = $subCategory;
        } else {
            $parentId = $mainCategory;
            $name = $subCategory;
        }

        return [
            'name' => $name,
            'parent_id' => $parentId,
        ];
    }

    protected function saveCategoryData($data, $model)
    {
        if ($model->insert($data, false)) {
            return redirect()->back()->with('msg', "Category saved Successfully!");
        }
    }
}
