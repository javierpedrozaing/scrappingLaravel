<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use View;
use App\Category;
use App\Subcategory;

class HomeController extends Controller
{
    public function index() {
        
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return View::make("home")->with(['categories' => $categories, 'subcategories' => $subcategories]);
    }

    public function getCategorization($type = "") {
        $client = new Client();
        $response = [];
        $typeFilter = "";

        switch ($type) {
            case 'categories':
                $typeFilter = ".cat1Item .cat1";
                break;
            case 'subcategories':
                $typeFilter = ".cat2Item .cat2";
                break;
            default:                
                break;
        }

        $source = $client->request('GET', 'https://www.milanuncios.com/categories');

        $response[] = $source->filter($typeFilter)->each(function ($node) {
            return $node->text()."\n";
        });
        
        return $response;
    }

  
  

}
