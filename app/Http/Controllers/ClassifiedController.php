<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use App\Category;
use App\Subcategory;
use App\Classified;

class ClassifiedController extends Controller
{

    public function initScrapper() {
        $client = new Client();
        $source = $client->request('GET', 'https://www.milanuncios.com/categories');        
        return $source;
    }

    // TODO
    // Improve this function for insert from form using post method
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


    // TODO
    // Improve this function for insert from form using post method
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

    public function insertClassifieds() {
        $announcements = [];
        $descriptions = [];
        $prices = [];
        $client = new Client();
        $source = $client->request('GET', 'https://www.milanuncios.com/categories');    
        $success = false;
        $source = $this->initScrapper();        

        $link = $source->selectLink('Motor')->link();
        $data = $client->click($link);
        $announcements = $data->filter('.aditem .aditem-detail .aditem-detail-title')->each(function ($node) {
            return $node->text();
        });

        $descriptions[] = $data->filter('.aditem .aditem-detail .tx')->each(function ($node) {
            return $node->text();
        });


        $prices[] = $data->filter('.aditem .aditem-detail .aditem-price')->each(function ($node) {
            return $node->text();
        });

        
        if (is_array($announcements) && !empty($announcements)) {
            foreach ($announcements as $key => $announcement) {                
                $category = 145; // TODO- fix for get category_id respective
                try {
                    //instance of model
                    $classified = new Classified;
                    $classified->title = trim($announcement);
                    $classified->description = (!empty($descriptions[0][$key])) ? substr($descriptions[0][$key], 0, 150) : "";
                    $classified->image = "";
                    $classified->price =  (!empty($prices[0][$key])) ? $prices[0][$key] : "" ;
                    $classified->category_id = $category;
                    
                    //save in database
                    if($classified->save())
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
            echo "Anuncio creado satisfactoriamente!";
            
        } else {            
            echo "Anuncio no pudo ser creado, inténtalo nuevamente.";
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

    public function filterByCategory($category_id) {
        return Classified::where("category_id", $category_id)->get();
    }

    public function filterByTitle($title) {
        return Classified::where("title", $title)->get();
    }

    public function filterByDescription($description) {
        return Classified::where("description", $description)->get();
    }
}
