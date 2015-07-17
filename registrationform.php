<?php
$my_email = "registration@ffhmt.com";
$errors = array();

// Remove $_COOKIE elements from $_REQUEST.
if(count($_COOKIE)){foreach(array_keys($_COOKIE) as $value){unset($_REQUEST[$value]);}}

// Check referrer is from same site.
if(!(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) && stristr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST']))){$errors[] = "You must enable referrer logging to use the form";}

// Display any errors and exit if errors exist.
if(count($errors)){foreach($errors as $value){print "$value<br>";} exit;}
if(!defined("PHP_EOL")){define("PHP_EOL", strtoupper(substr(PHP_OS,0,3) == "WIN") ? "\r\n" : "\n");}

// Build message.
function build_message($request_input){
	if(!isset($message_output)){
		$message_output ="";
	}
	if(!is_array($request_input)){
		$message_output = $request_input;
	}else{
		foreach($request_input as $key => $value){
			if(!empty($value)){
				if(!is_numeric($key)){
					$message_output .= str_replace("_"," ",ucfirst($key)).": ".build_message($value).PHP_EOL.PHP_EOL;
				}else{
					$message_output .= build_message($value).", ";
				}
			}
		}
	}
	return rtrim($message_output,", ");
}

// Defining the Variables

$date = date("Y-m-d,h_i_s A");

$message = build_message($_REQUEST);

$message = 'Thank you for registering for FFHMT 2016. We will provide you any of the requested documents with in 48 hours.<br><br>

	If you are an author, please make sure to send us your camera ready version and a signed copyright form (see papers) via email to info@ffhmt.com. We <b>cannot</b> include your paper in the proceedings otherwise.' . $message ;

$message = $message . 'File uploaded: ';

$message = $message . $date.'_'.$_FILES['file']['name'];

$message = $message . PHP_EOL.PHP_EOL."-- ".PHP_EOL."";

$message = stripslashes($message);

$subject = "Registration Details for " . $_REQUEST['Email'];

$headers = "From: " . $_REQUEST['Email'];

$your_email = $_REQUEST['Email'];

$your_subject = "Your Registration Details for FFHMT'16";

$your_headers = "From: " . $my_email;

if ((($_FILES["file"]["type"] == "image/gif")

|| ($_FILES["file"]["type"] == "image/jpeg")

|| ($_FILES["file"]["type"] == "image/png")

|| ($_FILES["file"]["type"] == "image/jpg")

|| ($_FILES["file"]["type"] == "image/tif"))

&& ($_FILES["file"]["size"] < 20000000))

  {

  if ($_FILES["file"]["error"] > 0)

    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  else
    {
      move_uploaded_file($_FILES["file"]["tmp_name"],"receipts/" . $_FILES["file"]["name"]);
      rename("receipts/".$_FILES['file']['name'],"receipts/".$date.'_'.$_FILES['file']['name']);
	$filename = $date.'_'.$_FILES['file']['name'];
    }
  }
else
  {
  die("The file you have selected for upload is invalid. <br />
	Please make sure the file you are trying to upload is an image (.jpg, .jpeg, .png, .gif, .tif) <br />
	No other file types are allowed.");
  }

mail($my_email,$subject,$message,$headers);
mail($your_email,$your_subject,$message,$your_headers);
?>

---
layout: default
title: FFHMT'16 - Registration Form Filled!
---

<div class="unit unit-s-1 unit-m-1-4-1 unit-l-1-4-1">
  <div class="unit-spacer content">
    <p class="body">Thank you for filling out the registration form. You should receive an email with your information. Please keep this email for your reference.</p>

    <p class="body">If you do not receive an email, <strong>please check your SPAM folder</strong>.</p>

 	<p class="body">If you have requested any official invitation letters, please allow up to 5 business days to receive your documents.</p> 

  	<p class="body">If there are any problems in the information you have filled out, please write an email to us at <a href="mailto:registration@ffhmt.com" class="body-link">registration@ffhmt.com</a> mentioning the mistakes made. Please note that you SHOULD NOT refill the form.</p>

	<p class="body">We are looking forward to seeing you at the FFHMT'16!</p>
  </div>
</div>