<?php
/*
Template Name: Ahmed's template
*/

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