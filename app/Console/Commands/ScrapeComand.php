<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;

use App\Category;
use App\Subcategory;
use DB;

class ScrapeComand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scraping data from url';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();
        // use a url 404 random for get the category list
        $source = $client->request('GET', 'https://www.milanuncios.com/category');
        $categories =  $source->filter('.cat1Item .cat1')->each(function ($node) {
            return $node->text()."\n";
        });

        $subCategories = $source->filter('.cat2Item .cat2')->each(function ($node) {
            return $node->text()."\n";
        });

        $this->insertCategories($categories);
        //$this->insertSubcategories($subCategories);
    
    }

    public function insertCategories($categories) {
        $success = false;
        if (is_array($categories) && !empty($categories)) {
            foreach ($categories as $key => $category) {
                DB::beginTransaction();
                try {
                    //instance of model
                    $cat = new Category;
                    $cat->nombre = $category;               
                    
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
            DB::commit();            
            echo "Categorias creadas satisfactoriamente!";
            
        } else {
            DB::rollback();            
            echo "La categoría no pudo ser creada, inténtalo nuevamente.";
        }

    }

    public function insertSubcategories($subCategories) {
        $success = false;
        if (is_array($subCategories) && !empty($subCategories)) {
            foreach ($subCategories as $key => $subcat) {
                $category = rand(145, 176);
                DB::beginTransaction();
                try {
                    //instance of model
                    $subCategory = new Subcategory;
                    $subCategory->name = $subcat;
                    $subCategory->category_id = $category;
                    //save in database
                    if($subCategory->save())
                    {
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
            DB::commit();            
            echo "Sub-Categorias creadas satisfactoriamente!";
            
        } else {
            DB::rollback();            
            echo "La Sub-Categoría no pudo ser creada, inténtalo nuevamente.";
        }
    }
}
