<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $table = 'subcategories';
    protected $fillable = ['nombre'];

    public function category() {
		return $this->belongsTo('App\Models\Category');
	} 
}
