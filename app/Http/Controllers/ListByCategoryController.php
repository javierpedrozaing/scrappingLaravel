<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;

class ListByCategoryController extends Controller
{
    public function getList(Request $request, $name) {
        if ($request->isMethod('get')) {         

            $category = ucwords($name);        
            
            $client = new Client();
            $announcements = [];   
            // use a url 404 random for get the category list
            $source = $client->request('GET', 'https://www.milanuncios.com/categories');        
            
            try {
                $link = $source->selectLink($category)->link();
                $crawler = $client->click($link);
            } catch (\Exception $e) {
                echo $e->getMessage();die();
            }
            
           
            if (is_object($crawler)) {
                $announcements = $crawler->filter('.aditem')->each(function ($node) {
                    return $node->html()."\n";
                });
            }
            
            if (empty($announcements)) {
                return "no se encontraron resultados";
            } else {
                print_r($announcements);
            }
        
            
            
        }
    }


}
