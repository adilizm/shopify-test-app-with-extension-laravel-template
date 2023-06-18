<?php

declare(strict_types=1);

namespace App\Lib;

use App\Exceptions\ShopifyProductCreatorException;
use Illuminate\Support\Facades\Log;
use Shopify\Auth\Session;
use Shopify\Clients\Rest;

class ScriptCreator
{
   
    public static function call(Session $session)
    {
        $client = new Rest($session->getShop(), $session->getAccessToken());

        // get all themes
        $result = $client->get('/admin/api/2023-04/themes.json');
        
        // get active theme id
        foreach ($result->getDecodedBody()['themes'] as $key => $theme) {
            if($theme['role'] == 'main'){
                $active_theme =$theme;
            }
        }
        // log the active theme
        Log::debug("active theme id = " . $active_theme['id']);
        Log::debug("###########################################################################");
        
        // test get all assets (working)
        $result_All_Assets = $client->get('/admin/api/2023-04/themes/'.$active_theme['id'].'/assets.json');
        Log::debug($result_All_Assets->getDecodedBody()['assets']);
        Log::debug("###########################################################################");

        
        // test get single asset assets/base.css  (not working {"errors":"Not Found"})
        $result_single_Assets = $client->get('/admin/api/2023-04/themes/'.$active_theme['id'].'/assets.json?asset[key]=assets/base.css');
        Log::debug($result_single_Assets->getDecodedBody());
        Log::debug("###########################################################################");


        // test create an asset  (not working {"errors":"Not Found"})
        $file_info = [
            'asset' => [
                'key' => 'templates/index.liquid', 
                'value' => '<h1>Hello World from my app!</h1>'
                ]
        ];

        $result_create_asset = $client->put('/admin/api/2023-04/themes/151808999709/assets.json',$file_info);
        Log::debug($result_create_asset->getDecodedBody());
        Log::debug("###########################################################################");





    }

}
