<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['protocol']    = 'smtp';
$config['smtp_host']   = 'smtp.gmail.com'; // atau host email server kamu
$config['smtp_user']   = 'chalung.izha@gmail.com';  // ganti
$config['smtp_pass']   = 'app-password-16-karakter'; // ganti dengan App Password
$config['smtp_port']   = 587;
$config['smtp_crypto'] = 'tls';
$config['mailtype']    = 'html';
$config['charset']     = 'utf-8';
$config['newline']     = "\r\n";
$config['crlf']        = "\r\n";
$config['wordwrap']    = TRUE;
