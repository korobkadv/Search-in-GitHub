<?php 
session_name("github");
session_start();
?>

<!DOCTYPE html>
<html lang="ru">

  <head>
    <meta charset="utf-8">
    <meta name="description" content="Сервис единой регистрации для челенжа">
    <meta name="keywords" content="регистрация, html, css, php, curl, json, DEV челенж">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Поиск проектов на GitHub для WEB челенжа</title>
    <link rel="stylesheet" href="style.css" media="screen">
  </head>
  
<body>
 <header>
   <h1>Сервис единой регистрации</h1>
 </header>

 <?php 
if (isset($_POST['search'])) { $search = $_POST['search']; if ($search =='') { unset($search);} }
 if (isset($search))  {
       if (isset($_POST['name'])) { $name = $_POST['name']; $name = htmlspecialchars($name); }
       if (isset($_POST['star_ot'])) { $star_ot = $_POST['star_ot']; $star_ot = htmlspecialchars($star_ot); }
       if (isset($_POST['star_do'])) { $star_do = $_POST['star_do']; $star_do = htmlspecialchars($star_do); }
       if (isset($_POST['sort'])) { $sort = $_POST['sort']; $sort = htmlspecialchars($sort); }
       if (isset($_POST['calendar_ot'])) { $calendar_ot = $_POST['calendar_ot']; $calendar_ot = htmlspecialchars($calendar_ot);}
       if (isset($_POST['calendar_do'])) { $calendar_do = $_POST['calendar_do']; $calendar_do = htmlspecialchars($calendar_do); }
       
    }
?>

 <form class='formi' action="" method="post">
    
           <fieldset> <legend><h3>Критерии поиска:</h3></legend>
      <div>
         <b>Язык программирования:</b> <br> <input class='area2' name="name" type="text" size="30" maxlength="30" placeholder='Язык программирования' value='<? echo $name; ?>' required='required'>
     </div>
     <div>
         <b>Количество звезд:</b> <br> От: <input class='area' name="star_ot" type="number" size="10" required='required'  min="0" step="1" value='<? echo $star_ot; ?>'>  
                                До: <input class='area' name="star_do" type="number" size="10" required='required'  min="1" step="1" value='<? echo $star_do; ?>'>
    </div>
    
    <div>
        <b>Сортировать по звездам:</b> <br>  <input type='radio' name='sort' value='asc'  <? if (isset($sort) and $sort=='asc') { echo "checked"; } ?>> По возростанию
                                             <input type='radio' name='sort' value='desc' <? if (empty($sort))  { echo "checked"; } if (isset($sort) and $sort=='desc') { echo "checked"; } ?> > По убыванию
    </div>

   <div>  
       <b>Период создания проекта:</b>  <br> От: <input class='area' type="date" name="calendar_ot" required='required' value='<? echo $calendar_ot; ?>'>   
                                     До: <input class='area' type="date" name="calendar_do" required='required' value='<? echo $calendar_do; ?>'>
   </div>
 
    <div> 
       <input class='search' type="submit" name="search" value="Искать">          
    </div>   
  
     </fieldset>
 </form> 



<?php
  if (isset($search))  { 
  
      
  $res = file_get_contents('https://api.github.com/search/repositories?q=language:'.$name.'+stars:'.$star_ot.'..'.$star_do.'+created:'.$calendar_ot.'..'.$calendar_do.'&sort=stars&order='.$sort.'');
  $res = json_decode($res);
   
  $total_count = $res->total_count;
  $total_count0 = 1;
   
   echo "<br>";
  if ($total_count==0 or empty($total_count)) { echo "<h3>Ничего не найдено, попробуйте поменять критерии поиска.</h3>"; }
    
   while ( $total_count0<$total_count )
    { 

      echo $total_count0.".  <a href='repo.php?id=".$res->items[$total_count0]->url."&d1=".$calendar_ot."&d2=".$calendar_do."' target='_blanc'>".$res->items[$total_count0]->name."</a> | &#10026; ". $res->items[$total_count0]->stargazers_count." | <a href='".$res->items[$total_count0]->html_url."' target='_blanc'> &infin; </a><br>";
      $total_count0++;  if ($total_count0=='30') { $total_count0=$total_count; }
      
   }


 }
?>


</body>
</html>