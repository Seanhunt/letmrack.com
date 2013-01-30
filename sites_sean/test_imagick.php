<?php

//Read original image and create Imagick object
$thumb	=	new Imagick("test.jpg");

$newMaximumWidth	=	200;
$newMaximumHeight	=	200;

//Work out new dimensions
list($newX,$newY)=scaleImage(
							$thumb->getImageWidth(),
							$thumb->getImageHeight(),
							$newMaximumWidth,
							$newMaximumHeight
							);


print "newX: $newX<br>";
print "newY: $newY<br>";

//Scale the image
$thumb->thumbnailImage($newX,$newY);

//Write the new image to a file
$thumb->writeImage("test_thumb.jpg");


/**
 * Calculate new image dimensions to new constraints
 *
 * @param Original X size in pixels
 * @param Original Y size in pixels
 * @return New X maximum size in pixels
 * @return New Y maximum size in pixels
 */
function scaleImage($x,$y,$cx,$cy) {
    //Set the default NEW values to be the old, in case it doesn't even need scaling
    list($nx,$ny)=array($x,$y);
   
    //If image is generally smaller, don't even bother
    if ($x>=$cx || $y>=$cx) {
           
        //Work out ratios
        if ($x>0) $rx=$cx/$x;
        if ($y>0) $ry=$cy/$y;
       
        //Use the lowest ratio, to ensure we don't go over the wanted image size
        if ($rx>$ry) {
            $r=$ry;
        } else {
            $r=$rx;
        }
       
        //Calculate the new size based on the chosen ratio
        $nx=intval($x*$r);
        $ny=intval($y*$r);
    }   
   
    //Return the results
    return array($nx,$ny);
}
?>

