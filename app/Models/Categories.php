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
}
