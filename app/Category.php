<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['nombre'];

    public function subCategory() {
        return $this->hasMany('App\Models\Subcategory');
    }

    public function classified() {
        return $this->hasMany('App\Models\Classified');
    }
}
