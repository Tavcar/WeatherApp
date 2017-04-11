<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Weather</title>
        
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/assets/style.css">
    
</head>
<body>
    
    <div class="jumbotron jumbotron text-center" id="jumbotron">
        <div class="container">
            <h1>Weather News</h1>
            <p>Check the latest weather report and forecast for a chosen city.</p>
            <br>
            <div class="row vertical-center-row">
                <div class="col-lg-10 col-lg-offset-1 text-center">

                    <form class="form-inline" method="post">
                        <select name="location" class="form-control">
                            <option disabled="disabled" selected="selected">Please select a location</option>
                            <option value="Ljubljana|SI">Ljubljana</option>
                            <option value="Novo Mesto|SI">Novo mesto</option>
                            <option value="Moscow|RU">Moscow</option>
                            <option value="Tokyo|JP">Tokyo</option>
                            <option value="Houston|US">Houston</option>
                            <option value="Glasgow|GB">Glasgow</option>
                            <option value="Ulaanbaatar|MN">Ulaanbaatar</option>
                            <option value="Canberra|AU">Canberra</option>
                            <option value="Montevideo|UY">Montevideo</option>
                            <option value="Tarawa|KI">Tarawa</option>
                        </select>
                        <input type="submit" class="btn btn-danger" name="Submit" value="Select">
                    </form>
                    
                </div>  
            </div>
        </div> <!--container-->
    </div> <!--jumbotron-->
    
    <?php
    if(isset($_POST['location'])) {
        // api-key
        $ini = parse_ini_file("ApiKey.ini");
        $key = $ini['key'];

        $location = $_POST["location"];
        $location_explode = explode('|', $location);
        $city = $location_explode[0];
        $country = $location_explode[1];
            
        // Current weather
        $url1 = "http://api.openweathermap.org/data/2.5/weather?q=".$city.",".$country."&units=metric".$key;
        $json1 = file_get_contents($url1);
        $data1 = json_decode($json1, true);
            
        // 5-day/+3h weather forecast
        $url2 = "http://api.openweathermap.org/data/2.5/forecast?q=".$city.",".$country."&units=metric".$key;
        $json2 = file_get_contents($url2);
        $data2 = json_decode($json2, true);            
            
        // 13-day weather forecast
        $url3 = "http://api.openweathermap.org/data/2.5/forecast/daily?q=".$city.",".$country."&units=metric".$key;
        $json3 = file_get_contents($url3);
        $data3 = json_decode($json3, true);?>
    
        <!--Current weather--> 
    
        <div class="container vertical-center-row" id="current">                  
            <h2>Weather in <span><?php echo $data1['name'];?></span></h2>
        
            <div class="row col-sm-8 col-sm-offset-2 text-center">
                
                <div class="col-xs-3" id="current-img">
                    <img class="img-circle" src=<?php echo "http://openweathermap.org/img/w/".$data1['weather'][0]['icon'].".png";?> alt="Icon depicting current weather" width="110" height="110">
                    <p><span class="label label-warning"><?php echo $data1['main']['temp']." °C"."<br>";?></span></p>
                </div>
                    
                <div class="col-xs-5" id="current-text">
                    <p>
                    <?php echo $data1['weather'][0]['main']." - ".$data1['weather'][0]['description']."<br>";
                    echo "Humidity: ".$data1['main']['humidity']." %"."<br>";
                    echo "Cloudiness: ".$data1['clouds']['all']." %"."<br>";
                    echo "Wind speed: ".$data1['wind']['speed']." m/s"."<br>";
                    echo "Geo coords: [".$data1['coord']['lat'].", ".$data1['coord']['lon']."]"."<br>";?></p>
                </div>
                
            </div><!-- row --> 
            
        </div><!-- container --> 
  
    
        <!-- 3 Hour Forecast --> 
    
        <div class="container-fluid" id="hours">   
            <h2>3 Hour Forecast</h2>
            
            <div class="row" style ="text-align: center;">
                
                <div class="col-xs-8 col-xs-offset-2 col-sm-12 col-sm-offset-0">
                    <?php for ($i = 0; $i <= 3; $i++) {?>
                        <div class="col-sm-3 text-center" id="hours-panel">
                            <p id="time">+<?php echo ($i*3)+3;?> hours</p>
                            <img class="img-circle" width="80" height="80" src=<?php echo "http://openweathermap.org/img/w/".$data2['list'][$i]['weather'][0]['icon'].".png";?> alt="Icon depicting forecast weather">
                            <h3><?php echo $data2['list'][$i]['main']['temp']." °C";?></h3>
                            <p id="hours-temp"><?php echo $data2['list'][$i]['weather'][0]['description'];?></p>
                        </div>
                    <?php }?>
                </div>
                
            </div><!--row--> 
            
        </div> <!--container--> 
    
    
        <!--Weekly Day Forecast--> 
    
        <div class="container vertical-center-row" id="day-forecast">        

            <h2>Weekly Forecast</h2>
            <div class="list-group vertical-center-row">
                
                <div class="row  col-sm-8 col-sm-offset-2">
                    <?php for ($i = 0; $i <= 6; $i++) {?>
                        <div class="panel panel-default vertical-center-row" id="day-panel">
                            <div class="col-xs-5 col-sm-4 text-center" id="day-img">
                                <p><?php echo gmdate('D j. M', $data3['list'][$i]['dt']);?></p>
                                <img class="img-rounded" width="60" height="60" src=<?php echo "http://openweathermap.org/img/w/".$data3['list'][$i]['weather'][0]['icon'].".png";?> alt="Icon depicting forecast weather">
                                <?php if(gmdate('D j. M', $data3['list'][$i]['dt']) == gmdate('D j. M')) {?>
                                    <p id="today">Today</p>
                                <?php }?>
                            </div>

                            <div class="col-xs-7 col-sm-8 col-md-6 col-md-offset-1" id="day-text" >
                                <p><?php echo $data3['list'][$i]['weather'][0]['main']." - ".$data3['list'][$i]['weather'][0]['description']."<br>";?>
                                Day temperature: <span class="label label-warning" id="temp-day"><?php echo $data3['list'][$i]['temp']['day']." °C";?></span><br>
                                Night temperature: <span class="label label-default" id="temp-night"><?php echo $data3['list'][$i]['temp']['night']." °C";?></span></p>
                            </div>
                        </div>
                    <?php }?>
                </div>
                
            </div> <!--list-group--> 
            
        </div> <!--container-->   
        
    <?php }?>
        
</body>
</html>