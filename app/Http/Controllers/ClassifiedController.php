<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use App\Category;
use App\Subcategory;

class ClassifiedController extends Controller
{

    public function initScrapper() {
        $client = new Client();
        $source = $client->request('GET', 'https://www.milanuncios.com/categories');        
        return $source;
    }

    public function insertCategories() {
        
        $source = $this->initScrapper();
        $categories =  $source->filter('.cat1Item .cat1')->each(function ($node) {
            return $node->text()."\n";
        });

        $success = false;

        if (is_array($categories) && !empty($categories)) {
            foreach ($categories as $key => $category) {                
                try {
                    //instance of model
                    $cat = new Category;
                    $cat->nombre = trim($category);               
                    
                    //save in database
                    if($cat->save()) {
                        $success = true;
                    }

                } catch (\Exception $e) {
                    // maybe log this exception, but basically it's just here so we can rollback if we get a surprise
                    echo $e->getTraceAsString();
                    exit();
                }
            }
        }

        if ($success) {            
            echo "Categorias creadas satisfactoriamente!";
            
        } else {            
            echo "La categoría no pudo ser creada, inténtalo nuevamente.";
        }

    }


    public function insertSubCategories() {
        $source = $this->initScrapper();
        $subCategories = $source->filter('.cat2Item .cat2')->each(function ($node) {
            return $node->text()."\n";
        });
        $success = false;

        if (is_array($subCategories) && !empty($subCategories)) {
            foreach ($subCategories as $key => $subcat) {                
                $category = rand(145, 176);
                try {
                    //instance of model
                    $subCat = new Subcategory;
                    $subCat->nombre = trim($subcat);
                    $subCat->category_id = $category;
                    
                    //save in database
                    if($subCat->save())
                    {
                        $success = true;
                    }

                } catch (\Exception $e) {
                    // maybe log this exception, but basically it's just here so we can rollback if we get a surprise
                    echo $e->getMessage();
                    exit();
                }
            }
        }
        if ($success) {                        
            echo "Sub-Categorias creadas satisfactoriamente!";
            
        } else {            
            echo "La Sub-Categoría no pudo ser creada, inténtalo nuevamente.";
        }
    }

    public function listAds() {
        $client = new Client();
        $source = $client->request('GET', 'https://www.milanuncios.com/categories');        
        
        $link = $source->selectLink('motor')->link();
        $data = $client->click($link);
        $announcements = $data->filter('.aditem .aditem-detail-title')->each(function ($node) {
            return trim($node->text());
        });
        return response()->json(['data' => $announcements], 200);

    }
}
