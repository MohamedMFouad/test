<?php
/*
Template Name: Ahmed's template
*/

echo '<html><title>Report Conversion tool!</title><body>';
echo "\n\n";
echo "<h1 style='color:green;font-size:30px;text-align:center;'>Report Conversion Tool V1.09</h1><br /><br /><br />";
//accept the file upload
$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');

if(in_array($_FILES['image']['type'],$mimes)){
    $errors= array();
    //echo 'hi';
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
    
    $extensions= array("csv","xls","xlsx");
    
    if(in_array($file_ext,$extensions)=== false){
       $errors[]="extension not allowed, please choose a csv or Excel file.";
    }
    
    if($file_size > 5097152) {
       $errors[]='File size must be excately 2 MB';
    }
    
    if(empty($errors)==true) {
       if(move_uploaded_file($file_tmp,"wp-content/uploads/".$file_name))
       {

       
        echo "<b style='color:red;font-size:30px;font-family:verdana;'>Success</b>";




        //start processing the file to get the sku
        // and generate the UPC

        $filex = "wp-content/uploads/".$file_name;
        $file = fopen($filex, 'r');








        //create the file where we will store the new report
        $fp = fopen("wp-content/uploads/".'persons.csv', 'w');


//$csv = fopen("wp-content/uploads/website_stock_report.csv", 'r');

//$data_as_csv = str_getcsv(file_get_contents('wp-content/uploads/website_stock_report.csv'));
//$data_as_csv = file_get_contents('wp-content/uploads/website_stock_report.csv');

//$csv =  explode(',',$data_as_csv);
//print_r($csv[1]);

//print_r($data_as_csv);
//$res = array_keys(array_column($csv, 1), "6222023913011");

//print_r($res);
echo "<br /><br /><br />\n\n\n";
$no = 1;
$upcArray = array();
while (($line = fgetcsv($file)) !== FALSE) {
    $linex = "";
    //$line is an array of the csv elements
  //  echo "\n";
    //print_r($line[3]);

//This code should allow us to detect the sku column location
    if($no == 2)
{
    print_r($line);
    echo "<br /> \n\n <br />";
    foreach($line as $newKey => $newValue)
    {
        $getSKU = $wpdb->get_results("SELECT * FROM wpul_postmeta where meta_key = '_sku' AND meta_value = '".$newValue."' limit 1");
        if(count($getSKU) > 0)
        {
            //echo "<b style='color:red;'>This is the SKu: </b>";
          //  print_r($getSKU);
            //echo "<br />\n";
            //echo $newKey;
            $rowLine = $newKey;
        }
    }
}

    if($no > 0)
{
    $posts = $wpdb->get_results("SELECT * FROM wpul_postmeta where meta_value = '".$line[$rowLine]."' limit 1");
    //print_r($posts);

   // echo "\n";
   // echo $line[4];

 //   echo "\n";

  //  echo $posts[0]->post_id;

  //  echo "\n<br />";
    $upc = get_post_meta( $posts[0]->post_id, '_upc_variation_code', true );
    //print_r($returned_data);
    if($line[1] != "")
    {
    //    update_post_meta( $posts[0]->post_id, '_upc_variation_code', esc_attr( $line[1] ) );
    }



//get the color from the stock
/*
$result  = [];
if (($handle = fopen("wp-content/uploads/website_stock_report.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 6000, ",")) !== FALSE) {
        print_r($data);
        echo "<br />\n\n<br />";
      if($data[1] == $upc){ //Checks secound colums is yes 
          //array_push($result, $data[9]);
$color = $data[8];
echo "\n $color \n <br />";
break;
        }
    fclose($handle);

//  var_dump($result);
}

}
*/


$linex[8] = "";
$linex[7] = "";
$csv = array_map('str_getcsv', file('wp-content/uploads/website_stock_report.csv'));

foreach($csv as $linex){
    if($linex[1] == $upc){
        //do what ever you want
        //echo "found yes";
        $color = $linex[8];
        $size = $linex[7];
        $thecode = $linex[0];
        //echo $linex[8] . " is the color! \n <br />";
        break;
    }
}

//echo $linex[8] . " is the color! \n <br />";
  //  $size = $wpdb->get_results("SELECT * FROM wpul_postmeta where post_id = '".$line[2]."' AND meta_key = 'attribute_pa_size' limit 1");
 //$size = get_post_meta( $posts[0]->post_id, 'attribute_pa_size', true );

//  $color =  get_post_meta( $posts[0]->post_id, 'attribute_pa_color', true );
if($no == 1)
{

    $line[] = "UPC Column";
    $line[] = "Size";
    $line[] = "Color";
    $line[] = "Code";
    fputcsv($fp, $line);
}else{
    $line[] = $upc;
    $line[] = $size;
    
    $line[] = $color;
    $line[] = $thecode;
  //$line[] = $linex[8];
    
  fputcsv($fp, $line);
unset($color);
unset($size);

unset($thecode);
}
 

}

    
    //echo count($posts);

    /*
    $val = explode('-', $line[2]);
    $arr = array_slice($val, -1);
//    $inserted = array( 'x' );

    $xCount = count($val);
    $beforeLast = array($val[$xCount-2]);


    array_splice( $line, 3, 0, $beforeLast );

    array_splice( $line, 4, 0, $arr );

//unset($line[2][4]);
    $newA = array();
$m = 0;
    $newMyV = "";
foreach ($val as $kk=>$vv)
{

    if($m < $xCount-2)
    {


    $newA[$m] = $vv;
    $m++;
    }
    }

foreach ($newA as $myV)
{

    $newMyV .= $myV."-";
//$b;
}

    $newMyV = substr_replace($newMyV ,"", -1);
$newMyV = array($newMyV);
    array_splice( $line, 5, 0, $newMyV );
//print_r($newA);
//echo $newMyV;
    echo "\n";
       */
  // fputcsv($fp, $line);

    //    print_r($arr);

 



$no++;
if($no == 50)
{
    break;
}
}


fclose($file);












    }else{
        echo "You have a problem!". $file_tmp;
        echo "<br />\n";
    //    print_r($_FILES);
    }
    }else{
       print_r($errors);
    }
 }else{
     echo "File type not allowed!";
 }
?>
<html>
 <body>
    
    <form action = "" method = "POST" enctype = "multipart/form-data">
       <input type = "file" name = "image" />
       <input type = "submit"/>
          
       <ul>
          <li>Sent file: <?php echo $_FILES['image']['name'];  ?>
          <li>File size: <?php echo $_FILES['image']['size'];  ?>
          <li>File type: <?php echo $_FILES['image']['type'] ?>
       </ul>
          
    </form>
<?php



exit;
// open the csv file
$filex = "http://staging.magmasportswear.com/wp-content/uploads/website_stock_report.csv";
$file = fopen($filex, 'r');

echo "<br /><br /><br />\n\n\n";
$no = 1;

while (($line = fgetcsv($file)) !== FALSE) {
    //$line is an array of the csv elements
    echo "\n";
    //print_r($line[3]);


    if($no > 800)
{
    $posts = $wpdb->get_results("SELECT * FROM wpul_postmeta where meta_value = '".$line[3]."' limit 1");
    print_r($posts);

    echo "\n";
    echo $line[1];

    echo "\n";

    echo $posts[0]->post_id;
    if($line[1] != "")
    {
        update_post_meta( $posts[0]->post_id, '_upc_variation_code', esc_attr( $line[1] ) );
    }

}

    
    //echo count($posts);
if($no == 900)
{
    break;
}
    /*
    $val = explode('-', $line[2]);
    $arr = array_slice($val, -1);
//    $inserted = array( 'x' );

    $xCount = count($val);
    $beforeLast = array($val[$xCount-2]);


    array_splice( $line, 3, 0, $beforeLast );

    array_splice( $line, 4, 0, $arr );

//unset($line[2][4]);
    $newA = array();
$m = 0;
    $newMyV = "";
foreach ($val as $kk=>$vv)
{

    if($m < $xCount-2)
    {


    $newA[$m] = $vv;
    $m++;
    }
    }

foreach ($newA as $myV)
{

    $newMyV .= $myV."-";
//$b;
}

    $newMyV = substr_replace($newMyV ,"", -1);
$newMyV = array($newMyV);
    array_splice( $line, 5, 0, $newMyV );
//print_r($newA);
//echo $newMyV;
    echo "\n";
   fputcsv($fp, $line);

    //    print_r($arr);

    */



$no++;
}


fclose($file);




exit;

//print_r($wpdb);
   $posts = $wpdb->get_results("SELECT * FROM wpul_postmeta where meta_key = '_upc' limit 5");
   print_r($posts);

   $posts = $wpdb->get_row("SELECT * from wpul_postmeta");

$sql1 = "SELECT * FROM wp_posts WHERE post_status = 'publish' AND post_type = 'home-messages' AND ID = '$selected_post'";

$sql2 = "select * from wpul_postmeta";
//$result1 = mysql_query($sql2) or die(mysql_error());
exit;

/*
while ($row1 = mysql_fetch_assoc($result1)) {
  print_r($row1);
    }
*/
echo "<br /><br /><br />\n\n\n";
$no = 1;
while (($line = fgetcsv($file)) !== FALSE) {
    //$line is an array of the csv elements
    echo "\n";
    print_r($line[3]);

/*
    $val = explode('-', $line[2]);
    $arr = array_slice($val, -1);
//    $inserted = array( 'x' );

    $xCount = count($val);
    $beforeLast = array($val[$xCount-2]);


    array_splice( $line, 3, 0, $beforeLast );

    array_splice( $line, 4, 0, $arr );

//unset($line[2][4]);
    $newA = array();
$m = 0;
    $newMyV = "";
foreach ($val as $kk=>$vv)
{

    if($m < $xCount-2)
    {


    $newA[$m] = $vv;
    $m++;
    }
    }

foreach ($newA as $myV)
{

    $newMyV .= $myV."-";
//$b;
}

    $newMyV = substr_replace($newMyV ,"", -1);
$newMyV = array($newMyV);
    array_splice( $line, 5, 0, $newMyV );
//print_r($newA);
//echo $newMyV;
    echo "\n";
   fputcsv($fp, $line);

    //    print_r($arr);

    */



$no++;
}


fclose($file);


?>