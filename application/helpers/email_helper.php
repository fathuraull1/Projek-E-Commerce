<?php
error_reporting(0);
function kirim_email($email, $subject, $message)
{
    $ci = get_instance();
    $ci->load->library('email');
    $config['protocol'] = "smtp";
    $config['smtp_host'] = "smpt-relay.sendinblue.com";
    $config['smtp_crypto'] = "tls";
    $config['smtp_port'] = "587";
    $config['smtp_user'] = "no-reply@canvas.id";
    $config['smtp_pass'] = "xsmtpsib-3c99b88c122dd69d360632a218ecd61bed22b60556b4f7175d82e0683f58aaf3-QgkC6JsDBEOHjnKt";
    $config['charset'] = "iso-8859-1";
    $config['mailtype'] = "html";
    $config['newline'] = "\r\n";
    $ci->email->initialize($config);
    $ci->email->from('fathurrachmman2@gmail.com', "antihama");
    $ci->email->to("$email");
    $ci->email->subject("$subject");
    $ci->email->message("$message");
    $ci->email->send();
}
