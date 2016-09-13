<!DOCTYPE html>
<html lang="ru">

  <head>
    <meta charset="utf-8">
    <meta name="description" content="Поиск проектов на GitHub для челенжа">
    <meta name="keywords" content="GitHub, html, css, php, curl, json, UA челенж">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Поиск проектов на GitHub для WEB челенжа</title>
    <link rel="stylesheet" href="style.css" media="screen">
  </head>
  
<body>
  <?php  
  
  if (isset($_GET['id'])) { $url = $_GET['id']; }  
    if (empty($url) ) { echo "<h2>Начни с <a href='index.php'>главной</a> страницы! </2>";}
    else {

    if (isset($_GET['d1'])) { $d1 = $_GET['d1']; } 
    if (isset($_GET['d2'])) { $d2 = $_GET['d2']; }   


$result = file_get_contents($url);
$result = json_decode($result);

    echo "
 <header>
   <h1>Проект: ".$result->name." </h1>
 </header>";
   
$res = file_get_contents("https://api.github.com/repos/". $result->full_name ."/contributors?anon=1");
$res = json_decode($res);

 echo "<b>Проект создан:</b> ". date_create($result->created_at)->Format('d.m.Y')."<br>";
 echo "<b>Последнее обновление:</b> ". date_create($result->updated_at)->Format('d.m.Y')."<br><hr>";

$datetime1 = new DateTime($d1);
$datetime2 = new DateTime($d2);
$interval = $datetime1->diff($datetime2);
echo $interval->format('Статистика за %a дней:')."<br>"; 
 
 echo "<b>Всего контрибюторов:</b> ".$res[0]->contributions."<br><br>";
 
 
$res2 = file_get_contents("https://api.github.com/repos/". $result->full_name ."/commits?since=".$d1."&until=".$d2."");
$res2 = json_decode($res2);
$res20 = count($res2);
 echo "<b>Коммитев за выбранный период:</b> ".$res20."<br>";
    $i0=0;
          while ( $i0<$res20 )
    {
    $sortstardate0 = (strtotime($d1) < strtotime($res2[$i0]->commit->author->date)); 
    $sortstardate20 = (strtotime($d2) > strtotime($res2[$i0]->commit->author->date));  
     if ($sortstardate0=='true' and $sortstardate20=='true') {  
       echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>От:</b> ". $res2[$i0]->commit->author->name. " | ". date_create($res2[$i0]->commit->author->date)->Format('d.m.Y')."<br>";
       } 
       $i0++;
    }

$ch = curl_init("https://api.github.com/repos/". $result->full_name ."/stargazers?since=".$d1."&until=".$d2."");
      curl_setopt ($ch, CURLOPT_USERAGENT, "Google");
$headers = array ('Accept: application/vnd.github.v3.star+json'); 
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $results = curl_exec($ch);
    $res3 = json_decode($results);
    $star_count = count($res3);
    curl_close($ch);
 
 echo "<br><b>Звезды за выбранный период:</b> ". $star_count ." &#10026; <br>";
         $i=0;
          while ( $i<$star_count )
    {
    $sortstardate = (strtotime($d1) < strtotime($res3[$i]->starred_at)); 
    $sortstardate2 = (strtotime($d2) > strtotime($res3[$i]->starred_at));  
     if ($sortstardate=='true' and $sortstardate2=='true') {  
       echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>От:</b> ". $res3[$i]->user->login. " | ". date_create($res3[$i]->starred_at)->Format('d.m.Y')."<br>";
       }
       $i++;
    }
 
 //print_r($res3);
}
?>


 <br><br><br>
</body>
</html>