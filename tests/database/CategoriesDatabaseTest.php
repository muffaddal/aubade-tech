<?php

use App\Models\Categories;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Tests\Support\Database\Seeds\CategoriesSeeder;


class CategoriesDatabaseTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $seedOnce = false;
    protected $seed     = CategoriesSeeder::class;

    public function testValidateSubcategoryIsEmpty()
    {
        $model = new Categories();

        $existingSubCategory = $model->where([
            'name' => 'Test',
            'parent_id' => 1
        ])->find();

        // Make sure the count is as expected
        $this->assertEmpty($existingSubCategory);
    }

    public function testValidateSubcategoryIsNotEmpty()
    {
        $model = new Categories();

        $existingSubCategory = $model->where([
            'name' => 'Samsung',
            'parent_id' => 1
        ])->find();

        // Make sure the count is as expected
        $this->assertNotEmpty($existingSubCategory);
    }

    public function testValidateMainCategoryIsEmpty()
    {
        $model = new Categories();

        $existingMainCategory = $model->where([
            'name' => 'Earphones',
            'parent_id' => null
        ])->find();

        // Make sure the count is as expected
        $this->assertEmpty($existingMainCategory);
    }

    public function testValidateMainCategoryIsNotEmpty()
    {
        $model = new Categories();

        $existingMainCategory = $model->where([
            'name' => 'Mobile Phones',
            'parent_id' => null
        ])->find();

        // Make sure the count is as expected
        $this->assertNotEmpty($existingMainCategory);
    }
}