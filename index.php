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
    <!--<link rel="stylesheet" href="/assets/css/style.css">-->
</head>
<body style ="background-color: #f9faea; font-family: Georgia, serif;">
    
    <div class="jumbotron jumbotron text-center" style ="background-color: #00293C; margin-bottom: 0px;">
        <div class="container">
            <h1 style="color: #F62A00;">Weather News</h1>
            <p style="color: #f9faea;">Check the latest weather report and forecast for a chosen city.</p>
            <br>
            <div class="row vertical-center-row">
                <div class="col-lg-10 col-lg-offset-1 text-center">

                    <form class="form-inline" method="post">
                        <select name="location" class="form-control" style="background-color: #f9faea; color: #00293C; border: 1px solid #F62A00; font-size: 20px; min-width: 60%; margin-bottom: 5px; margin-top: 5px;">
                            <option value="" disabled="disabled" selected="selected">Please select a location</option>
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
                        <input type="submit" class="btn btn-danger" name="Submit" value="Select" style="font-size: 18px; background-color: #F62A00; color: #f9faea; border: 1px solid #F62A00">
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
    
        <div class="container vertical-center-row" style ="padding-bottom: 10px;">                  
        <h2 style="text-align: center; color: #353547;">Weather in <span style="font-size: 40px; color: #F62A00;"><?php echo $data1['name'];?></span></h2>
        
            <div class="row col-sm-8 col-sm-offset-2 text-center">
                
                <div class="col-xs-3 " style ="display: inline-block; float: none; min-width: 150px;">
                    <img class="img-circle" src=<?php echo "http://openweathermap.org/img/w/".$data1['weather'][0]['icon'].".png";?> alt="Icon depicting current weather" width="110" height="110" style="background-color: #f9faea; margin-bottom: 10px; margin-top: 5px;">
                    <p><span class="label label-warning" style="font-size: 22px; background-color: #F62A00; color: #f9faea; border: 1px solid #F62A00; padding-bottom: 2px;"><?php echo $data1['main']['temp']." °C"."<br>";?></span></p>
                </div>
                    
                <div class="col-xs-5 " style="min-width: 260px; display: inline-block; float: none;">
                    <p style="font-size: 18px; text-align: left; color: #353547;">
                    <?php
                    echo $data1['weather'][0]['main']." - ".$data1['weather'][0]['description']."<br>";
                    echo "Humidity: ".$data1['main']['humidity']." %"."<br>";
                    echo "Cloudiness: ".$data1['clouds']['all']." %"."<br>";
                    echo "Wind speed: ".$data1['wind']['speed']." m/s"."<br>";
                    echo "Geo coords: [".$data1['coord']['lat'].", ".$data1['coord']['lon']."]"."<br>";?></p>
                </div>
                
            </div><!-- row --> 
            
        </div><!-- container --> 
  
    
        <!-- 3 hours forecast --> 
    
        <div class="container-fluid" style ="background-color: #1E656D; padding-bottom: 20px;">   
        <h2 style="text-align: center; color: #f9faea;">3 Hour Forecast</h2>
            
            <div class="row" style ="text-align: center;">
                
                <div class="col-xs-8 col-xs-offset-2 col-sm-12 col-sm-offset-0">
                    <?php for ($i = 0; $i <= 3; $i++) {?>
                        <div class="col-sm-3 text-center" style ="background-color: #f1f3ce; border: 2px solid #F62A00; border-radius: 5px; margin: 10px; display: inline-block; float: none; width: 150px; height: 220px; vertical-align: top;">
                            <p style="color: #00293C; margin-top: 10px;  font-size: 15px;">+<?php echo ($i*3)+3;?> hours</p>
                            <img class="img-circle"  src=<?php echo "http://openweathermap.org/img/w/".$data2['list'][$i]['weather'][0]['icon'].".png";?> alt="Icon depicting forecast weather" width="80" height="80" style ="background-color: #f9faea;">
                            <h3 style="margin-bottom: 5px; font-weight: 600; color: #F62A00;"><?php echo $data2['list'][$i]['main']['temp']." °C";?></h3>
                            <p style="font-size: 16px; color: #00293C;"><?php echo $data2['list'][$i]['weather'][0]['description'];?></p>
                        </div>
                    <?php }?>
                </div>
                
            </div><!--row--> 
            
        </div> <!--container--> 
    
    
        <!--Day Forecast--> 
    
        <div class="container vertical-center-row" style="padding-bottom: 10px;">        

            <h2 style="text-align: center; color: #00293C;">Weekly Forecast</h2>
            <div class="list-group vertical-center-row">
                
                <div class="row  col-sm-8 col-sm-offset-2">
                    <?php for ($i = 0; $i <= 6; $i++) {?>
                        <div class="panel panel-default vertical-center-row" style="width: 100%; margin-bottom: 5px; display: inline-block; background-color: #1E656D; border-radius: 5px; padding-top: 5px; padding-bottom: 5px;">
                            <div class="col-xs-5 col-sm-4 text-center" style="float: left;">
                                <p style="margin-bottom: 0px; color: #f9faea;"><?php echo gmdate('D j. M', $data3['list'][$i]['dt']);?></p>
                                <img class="img-rounded" src=<?php echo "http://openweathermap.org/img/w/".$data3['list'][$i]['weather'][0]['icon'].".png";?> width="60" height="60" style="display: inline-block; float: none; margin-bottom: 4px; background-color: #f1f3ce;" alt="Icon depicting forecast weather">
                                <?php if(gmdate('D j. M', $data3['list'][$i]['dt']) == gmdate('D j. M')) {?>
                                    <p style="color: #f9faea; font-size: 12px; margin-bottom: 0px;">Today</p>
                                <?php }?>
                            </div>

                            <div class="col-xs-7 col-sm-6 col-md-offset-1" >
                                <p style="font-size: 16px; margin-top: 10px; line-height: 1.7; text-align: left; color: #f9faea;"><?php echo $data3['list'][$i]['weather'][0]['main']." - ".$data3['list'][$i]['weather'][0]['description']."<br>";?>
                                Day temperature: <span class="label label-warning" style="font-size: 11px; background-color: #F62A00; color: #f9faea; border: 1px solid #F62A00; padding-bottom: 1px;"><?php echo $data3['list'][$i]['temp']['day']." °C";?></span><br>
                                Night temperature: <span class="label label-default" style="font-size: 11px; background-color: #00293C; color: #f9faea; border: 1px solid #00293C; padding-bottom: 1px;"><?php echo $data3['list'][$i]['temp']['night']." °C";?></span></p>
                            </div>
                        </div>
                    <?php }?>
                </div>
                
            </div> <!--list-group--> 
            
        </div> <!--container-->   
        
    <?php }?>
        
</body>
</html>