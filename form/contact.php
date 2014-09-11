<?php
$ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

//we do not allow direct script access
if (!$ajax) {
	//redirect to contact form
	$form = '../index.html';
	header("Location: " . $form);
	exit;
}
require_once "config.php";

/**
 * MAIL CONFIG
 */

$mail->Subject = "Contact Form";

//setup proper validation errors. If you change required=false, please make
//sure your contact form does not have "required" tag in input fields
//also keys of array (name, message, email) are the names used in contact form
$formFields = array(
	'name' => array('required' => true, 'required_error' => "This field is required"),
	'message' => array('required' => true, 'required_error' => "This field is required"),
	'email' => array('required' => true, 'required_error' => "Field is required", 'email_error' => "Please enter a valid email address"),
);

$errorMessage = "Unfortunately we couldn't deliver your message.<br>Please try again later.";
$successMessage = "<h3>Thank you.</h3> We will contact you shortly.";

//NO NEED TO EDIT ANYTHING BELOW

//let's validate and return errors if required
if ($errors = $mail->validate($formFields, $_REQUEST)) {
	echo json_encode(array('errors' => $errors));
	exit;
}

$mail->setup(dirname(__FILE__) . '/contact.html', $_REQUEST, $formFields);

if (!$mail->Send()) {
	$message = '<div class="span6 alert error">' . $errorMessage . '</div>';
} else {
	$message = '<div class="span6 alert alert-success">' . $successMessage . '</div>';
}

echo json_encode(array('msg' => $message));
exit;