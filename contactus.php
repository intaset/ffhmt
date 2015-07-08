<?php

$my_email = "info@ffhmt.com";

/*

Enter the continue link to offer the user after the form is sent.  If you do not change this, your visitor will be given a continue link to your homepage.

If you do change it, remove the "/" symbol below and replace with the name of the page to link to, eg: "mypage.htm" or "http://www.elsewhere.com/page.htm"

*/

$continue = "/";

/*

Step 3:

Save this file (FormToEmail.php) and upload it together with your webpage containing the form to your webspace.  IMPORTANT - The file name is case sensitive!  You must save it exactly as it is named above!  Do not put this script in your cgi-bin directory (folder) it may not work from there.

THAT'S IT, FINISHED!

You do not need to make any changes below this line.

*/

$errors = array();

// Remove $_COOKIE elements from $_REQUEST.

if(count($_COOKIE)){foreach(array_keys($_COOKIE) as $value){unset($_REQUEST[$value]);}}

// Check all fields for an email header.

function recursive_array_check_header($element_value)
{

global $set;

if(!is_array($element_value)){if(preg_match("/(%0A|%0D|\n+|\r+)(content-type:|to:|cc:|bcc:)/i",$element_value)){$set = 1;}}
else
{

foreach($element_value as $value){if($set){break;} recursive_array_check_header($value);}

}

}

recursive_array_check_header($_REQUEST);

if($set){$errors[] = "You cannot send an email header";}

unset($set);

// Validate email field.

if(isset($_REQUEST['email']) && !empty($_REQUEST['email']))
{
if(preg_match("/(%0A|%0D|\n+|\r+|:)/i",$_REQUEST['email'])){$errors[] = "Email address may not contain a new line or a colon";}

$_REQUEST['email'] = trim($_REQUEST['email']);

if(substr_count($_REQUEST['email'],"@") != 1 || stristr($_REQUEST['email']," ")){$errors[] = "Email address is invalid";}else{$exploded_email = explode("@",$_REQUEST['email']);if(empty($exploded_email[0]) || strlen($exploded_email[0]) > 64 || empty($exploded_email[1])){$errors[] = "Email address is invalid";}else{if(substr_count($exploded_email[1],".") == 0){$errors[] = "Email address is invalid";}else{$exploded_domain = explode(".",$exploded_email[1]);if(in_array("",$exploded_domain)){$errors[] = "Email address is invalid";}else{foreach($exploded_domain as $value){if(strlen($value) > 63 || !preg_match('/^[a-z0-9-]+$/i',$value)){$errors[] = "Email address is invalid"; break;}}}}}}

}

// Check referrer is from same site.

if(!(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) && stristr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST']))){$errors[] = "You must enable referrer logging to use the form";}

// Check for a blank form.

function recursive_array_check_blank($element_value)
{

global $set;

if(!is_array($element_value)){if(!empty($element_value)){$set = 1;}}
else
{

foreach($element_value as $value){if($set){break;} recursive_array_check_blank($value);}

}

}

recursive_array_check_blank($_REQUEST);

if(!$set){$errors[] = "You cannot send a blank form";}

unset($set);

// Display any errors and exit if errors exist.

if(count($errors)){foreach($errors as $value){print "$value<br>";} exit;}

if(!defined("PHP_EOL")){define("PHP_EOL", strtoupper(substr(PHP_OS,0,3) == "WIN") ? "\r\n" : "\n");}

// Build message.

function build_message($request_input){if(!isset($message_output)){$message_output ="";}if(!is_array($request_input)){$message_output = $request_input;}else{foreach($request_input as $key => $value){if(!empty($value)){if(!is_numeric($key)){$message_output .= str_replace("_"," ",ucfirst($key)).": ".build_message($value).PHP_EOL.PHP_EOL;}else{$message_output .= build_message($value).", ";}}}}return rtrim($message_output,", ");}

$message = build_message($_REQUEST);

$message = $message . PHP_EOL.PHP_EOL."-- ".PHP_EOL."";

$message = stripslashes($message);

$subject = $_REQUEST['Subject'];

$headers = "From: " . $_REQUEST['Email'];

mail($my_email,$subject,$message,$headers);

?>

---
layout: default
title: FFHMT'16 - Contact Us
---

<div class="unit unit-s-1 unit-m-1-4-1 unit-l-1-4-1">
  <div class="unit-spacer content">
    <p class="body">We have received your message and we will try our best to get back to you within the next 48 hours.<br>
    Thank you for your interest in FFHMT'16.</p>

  </div>
  </div>