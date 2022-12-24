<?php

namespace App\Models;

use CodeIgniter\Model;

class Categories extends Model
{
    protected $table = 'categories';

    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected function initialize()
    {
        $this->allowedFields = [
            'name',
            'parent_id',
        ];
    }

    public function getCategoriesList()
    {
        $query = $this->query('SELECT sub_category.id as s_id, sub_category.name as s_cat_name, p_category.name as p_cat_name 
        FROM categories sub_category 
        LEFT JOIN categories p_category ON p_category.id = sub_category.parent_id 
        ORDER BY sub_category.id');

        return $query->getResult();
    }
}
