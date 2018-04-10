<?php

$option=$_GET['option'];

if($option=="weather")
{

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.weatherbit.io/v2.0/current?ip=". $_SERVER['REMOTE_ADDR']."&key=6b29a8ccbffb4e888306ae9f9d69698a",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
));

$response = curl_exec($curl);
    $response=json_decode($response,true);
$err = curl_error($curl);

$icon=$response['data'][0]['weather']['icon'];
$city=$response['data'][0]['city_name'];
$weather=$response["data"][0]['temp'];
echo "<div id='weather'>
       <div id='icon-container'>
          <img id='weather-icon' src='img/icons/".$icon.".png'></div>
          <div id='weather-desc'>".$weather.' C <br>'.$city."</div>
       </div>
      </div>";
}

if($option=="home")
{

$curl = curl_init();

curl_setopt_array($curl, array(
CURLOPT_URL => "https://newsapi.org/v2/top-headlines?sources=abc-news&apiKey=3481b84d166847b78345bf6e04b8d8e4",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 30,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => "GET",
));

$response = curl_exec($curl);
$response=json_decode($response,true);

echo show($response);

}

if($option=="category")
{

    $country=array("eg","us");
    $data="";
    for($i=0;$i<2;$i++)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://newsapi.org/v2/top-headlines?country=".$country[$i]."&category=".$_GET['category']."&apiKey=3481b84d166847b78345bf6e04b8d8e4",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response = curl_exec($curl);
        $response=json_decode($response,true);
        $data.=show($response);
        curl_close($curl);

    }

    echo $data;

}

if($option=="search")
{

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://newsapi.org/v2/everything?q=".$_GET["q"]."&sortBy=popularity&apiKey=3481b84d166847b78345bf6e04b8d8e4",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));

    $response = curl_exec($curl);
    $response=json_decode($response,true);

    echo show($response);

}


if($option=="sources")
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://newsapi.org/v2/sources?&apiKey=3481b84d166847b78345bf6e04b8d8e4",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));

    $response = curl_exec($curl);
    $response=json_decode($response,true);

    for($i=0;$i<sizeof($response["sources"]);$i++)
    {
        echo "<button id='".$response['sources'][$i]['id']."' class='src'>".$response['sources'][$i]['name']."</button>";
    }
}

if($option=="filter")
{
    $temp=$_GET["choices"];
    $userChoices=explode(',',$temp);
    $data="";
    if(sizeof($userChoices)>0){
    for($i=0;$i<sizeof($userChoices);$i++)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://newsapi.org/v2/top-headlines?sources=".$userChoices[$i]."&apiKey=3481b84d166847b78345bf6e04b8d8e4",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $response=json_decode($response,true);

        $data.=show($response);
    }}
    echo $data;
}

function show($response)
{
    for($i=0;$i<sizeof($response["articles"]);$i++)
    {
        if($response["articles"][$i]['urlToImage']==null)
            $imagesourcec="img/notfound.png";
        else
            $imagesourcec=$response["articles"][$i]['urlToImage'];
        echo "<a target='_blank' href='".$response['articles'][$i]['url']."'>
       <div class='thecard'>
        <div class='card-img'>
           <img src=".$imagesourcec.">
           <span class='source'>".$response['articles'][$i]['source']['name']."</span>
        </div>
        <div class='card-caption'>
            <h1 class='title'>".$response['articles'][$i]['title']."</h1>
            <p class='desc'>".$response['articles'][$i]['description']."</p> 
        </div>
        <div class='date'>".$response['articles'][$i]['publishedAt']."</div> </div>
        </a>";
    }
}
?>
