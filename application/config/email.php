<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    'protocol' => 'ssmtp', // 'mail', 'sendmail', or 'smtp'
    'smtp_host' => 'ssl://ssmtp.googlemail.com',
    'smtp_port' => 465,
    'smtp_user' => 'no-reply@example.com',
    'smtp_pass' => '12345!',
    'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
    'mailtype' => 'text', //plaintext 'text' mails or 'html'
    'smtp_timeout' => '4', //in seconds
    'charset' => 'iso-8859-1',
    'wordwrap' => TRUE


//
//    'protocol' 	=> 'smtp', 											/********* default *********/
//            'smtp_host' => 'ssl://smtp.googlemail.com', /***** incoming server *****/
//            'smtp_port' => 465, 												/***** outgoing server *****/
//            'smtp_user' => 'ansrlysf@gmail.com', 				/********* username ********/
//            'smtp_pass' => 'acuy1234', 									/********* password ********/
//            'mailtype' 	=> 'html',
//            'charset' 	=> 'iso-8859-1'
);