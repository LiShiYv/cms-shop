<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CmsModel extends Model
{
    protected $table='cmsmodel';
    public $timestamps = false;
    protected $primaryKey = 'id';
}
