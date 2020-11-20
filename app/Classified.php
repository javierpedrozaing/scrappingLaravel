<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classified extends Model
{
    protected $table = 'classifieds';

    public function category() {
        return $this->belongsTo('App\Models\Category','id_category');
    }
}
