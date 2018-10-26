<?php
// src/Controller/RandockApiController.php
namespace App\Controller;


use function App\Apilauncher\Twitterlauncher\launchTwitterRandockApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use TwitterAPIExchange;

class RandockApiController extends AbstractController
{
    
    public function randockapi($name, $number)
    {
        $jsonraw = $this->launchTwitterRandockApi($name,$number);

        $rawdata = $this->getArrayTweets($jsonraw);
              
        return $this->render('randockapi/randockapi.html.twig', [
            'number' => $number,
            'name' => $name,
            'rawdata' => $rawdata,
        ]);
    }
    
    /**
     * Función para formatear el json en un array multidimencional
     * */
    private function getArrayTweets($jsonraw){
        
        $json = json_decode($jsonraw);
        
        $num_items = count($json);        
        
        for($i=0; $i<$num_items; $i++){
            
            $user = $json[$i];
            
            $screen_name = $user->user->screen_name;
            $tweet = $user->text;
            
            $name = "<a href='https://twitter.com/".$screen_name."' target=_blank>@".$screen_name."</a>";
            
            $rawdata[$i][0]=$name;
            $rawdata[$i]["screen_name"]=$name;
            $rawdata[$i][1]=$tweet;
            $rawdata[$i]["tweet"]=$tweet;
        }
        return $rawdata;
    }
    
    /**
     * Función para lanzar la api de twitter hay que añadir las:
     * 
     * AÑADIR ACCESS TOKEN
     * AÑADIR ACCESS TOKEN SECRET
     * AÑADIR CONSUMER KEY
     * AÑADIR CONSUMER KEY SECRET
     * 
     * */
    private function launchTwitterRandockApi($name,$number)
    {
        $settings = array(
            'oauth_access_token' => "AÑADIR ACCESS TOKEN",
            'oauth_access_token_secret' => "AÑADIR ACCESS TOKEN SECRET",
            'consumer_key' => "AÑADIR CONSUMER KEY",
            'consumer_secret' => "AÑADIR CONSUMER KEY SECRET"
        );
       
        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $getfield = '?screen_name=' . $name . '&count=' . $number;
        $requestMethod = 'GET';
        $twitter = new TwitterAPIExchange($settings);
        $json = $twitter->setGetfield($getfield)
        ->buildOauth($url, $requestMethod)
        ->performRequest();        
        
        return $json;
    }

}