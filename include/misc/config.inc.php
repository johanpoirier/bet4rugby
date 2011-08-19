<?
unset($config);
$config = array();

/* MAIN INFORMATIONS */
$config['title'] = "Coupe du Monde de Rugby 2011";
$config['author'] = "JoPs";
$config['email'] = "johan.poirier@gmail.com";
$config['lang'] = "fr";
$config['url'] = "http://cdm2011.nirgal.org";
$config['template'] = "cdm2011";
$config['db_prefix'] = "cdm2011__";
$config['email_simulation'] = false;
$config['support_email'] = $config['email'];
$config['support_team'] = "CdM2011";

$config['invitation_expiration'] = 30; /* in days */

//                     hh,m,s,M,J,AAAA
$config['steps'][0] = "20,30,0,9,9,2011";
?>
