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
     * Funci�n para formatear el json en un array multidimencional
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
     * Funci�n para lanzar la api de twitter hay que a�adir las:
     * 
     * A�ADIR ACCESS TOKEN
     * A�ADIR ACCESS TOKEN SECRET
     * A�ADIR CONSUMER KEY
     * A�ADIR CONSUMER KEY SECRET
     * 
     * */
    private function launchTwitterRandockApi($name,$number)
    {
        $settings = array(
            'oauth_access_token' => "A�ADIR ACCESS TOKEN",
            'oauth_access_token_secret' => "A�ADIR ACCESS TOKEN SECRET",
            'consumer_key' => "A�ADIR CONSUMER KEY",
            'consumer_secret' => "A�ADIR CONSUMER KEY SECRET"
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