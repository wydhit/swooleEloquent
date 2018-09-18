<?php
/**
 * Created by PhpStorm.
 * User: wyd
 * Date: 2018/9/10
 * Time: 11:03
 */

namespace SwooleEloquent;

use \Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    public function getConnection()
    {
        return Db::connection($this->getConnectionName());
    }

}
