<?php
   // PhpBarGraph HitLog Version 1.0
   // Bar Graph Hit Log Generator for PHP
   // Written By TJ Hunter (tjhunter@ruistech.com)
   // Released Under the GNU Public License.
   // http://www.ruistech.com/phpBarGraph

   // Specify which file format to output too.
   $outputFormat = "png";

require_once('include/db.inc.php');

   // The page id in hitCount that you want to show
   $pageId = 1;

   // We need to be able to use the bar graph class in phpBarGraph2.php
   require('include/phpBarGraph2.php');

   $history="21";

   $sql = "SELECT DISTINCT date,count FROM hitlog WHERE date > DATE_SUB(now(), INTERVAL $history DAY) ORDER BY date ASC limit 0, $history";
   $result = mysql_query($sql)
    or die(mysql_errno().": ".mysql_error()."<BR>".$sql);

   // Setup how high and how wide the ouput image is
   $imageHeight = 240;
   $imageWidth = 70+mysql_num_rows($result)*45;

   // Create a new Image
   $image = ImageCreate($imageWidth, $imageHeight);

   // Fill it with your favorite background color..
   $backgroundColor = ImageColorAllocate($image, 50, 50, 50);
   ImageFill($image, 0, 0, $backgroundColor);
   $white = ImageColorAllocate($image, 255, 255, 255);

   // Interlace the image..
   Imageinterlace($image, 1);


   // Create a new BarGraph..
   $myBarGraph = new PhpBarGraph;
   $myBarGraph->SetX(10);              // Set the starting x position
   $myBarGraph->SetY(10);              // Set the starting y position
   $myBarGraph->SetWidth($imageWidth-20);    // Set how wide the bargraph will be
   $myBarGraph->SetHeight($imageHeight-20);  // Set how tall the bargraph will be
   $myBarGraph->SetNumOfValueTicks(5); // Set this to zero if you don't want to show any. These are the vertical bars to help see the values.


   // You can try uncommenting these lines below for different looks.

   // $myBarGraph->SetShowLabels(false);  // The default is true. Setting this to false will cause phpBarGraph to not print the labels of each bar.
   // $myBarGraph->SetShowValues(false);  // The default is true. Setting this to false will cause phpBarGraph to not print the values of each bar.
   // $myBarGraph->SetBarBorder(false);   // The default is true. Setting this to false will cause phpBarGraph to not print the border of each bar.
   // $myBarGraph->SetShowFade(false);    // The default is true. Setting this to false will cause phpBarGraph to not print each bar as a gradient.
   // $myBarGraph->SetShowOuterBox(false);   // The default is true. Setting this to false will cause phpBarGraph to not print the outside box.
   $myBarGraph->SetBarSpacing(15);     // The default is 10. This changes the space inbetween each bar.

   // Add Values to the bargraph..
   

	// AddValue(TIME, COUNT)
   while ($r = mysql_fetch_row($result))
   {
      $myBarGraph->AddValue(preg_replace("/^200../", "", $r[0]), $r[1]);
   }
   $fp=fopen("acc.txt","r");
   $count=fgets($fp,1024);
   fclose($fp);
   $myBarGraph->AddValue(date("m-d", time()), $count);

   // Set the colors of the bargraph..
   $myBarGraph->SetStartBarColor("ff0000");  // This is the color on the top of every bar.
   $myBarGraph->SetEndBarColor("000000");    // This is the color on the bottom of every bar. This is not used when SetShowFade() is set to false.
   $myBarGraph->SetLineColor("ffffff");      // This is the color all the lines and text are printed out with.

   // Print the BarGraph to the image..
   $myBarGraph->DrawBarGraph($image);

   Imagestring($image, 2, 2, $imageHeight-14, "Number of hits to this page for the past " . (mysql_num_rows($result)+1) . " days.", $white);

   // Print out the header to tell the browser which file format we're sending in.
   if ($outputFormat == "gif")
   {
      header("Content-type: image/gif");
      // Output the Image to the browser in GIF format
      ImageGif($image);
   }
   else if ($outputFormat == "png")
   {
      header("Content-type: image/png");
      // Output the Image to the browser in PNG format
      ImagePNG($image);
   }
   else if ($outputFormat == "jpg")
   {
      header("Content-type: image/jpeg");
      // Output the Image to the browser in jpg format
      Imagejpeg($image);
   }

   // Destroy the image.
   Imagedestroy($image);

?>
