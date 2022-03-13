<?php
require_once '../app/init.php';
ini_set( 'session.cookie_httponly', 1 ); //prevent cookie can be stolen by a javascript injection
ini_set('session.cookie_lifetime', 0); //make sure session cookie ID is deleted when browser is terminated
//ini_set('session.cookie_secure', 1); //allows access to session ID cookie only when protocol is HTTPS
ini_set('session_use_only_cookies', 1);

header("Cache-Control: no-cache");
header("Pragma: no-cache");
header_remove('X-Powered-By');
header("X-XSS-Protection: 1; mode=block");
header("X-Frame-Options: deny");

$headerCSP =
"Content-Security-Policy:".
"connect-src 'self' ;". // XMLHttpRequest (AJAX request)
"default-src 'self';". // Default policy for loading html elements
"frame-ancestors 'self' ;". //allow parent framing - this one blocks click jacking and ui redress
"frame-src www.google.com;". // vaid sources for frames
"object-src 'none'; ". // valid object embed and applet tags src
"img-src 'self';". //only allow images/favisons from CloudImage server
"script-src 'self' http://cdn.jsdelivr.net code.jquery.com gstatic.com/recaptcha/ www.google.com www.gstatic.com cdn.jsdelivr.net;".
"font-src fonts.googleapis.com/ fonts.gstatic.com/s/materialicons/;".
"style-src 'self' 'unsafe-inline' cdn.jsdelivr.net google.com/recaptcha/  https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css fonts.googleapis.com/;";// allows css from CloudImages and inline allows inline css (google recaptcha)

header($headerCSP);


//instanciate frontcontroller
$frontController = new FrontController();


?>




