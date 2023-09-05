<?php
unset($config);
$config = [];

/* MAIN INFORMATIONS */
$config['title'] = "Coupe du Monde de Rugby 2023";
$config['author'] = "JoPs";
$config['email'] = "johan.poirier+cdm2023@gmail.com";
$config['lang'] = "fr";
$config['url'] = "http://cdm2023.jops-dev.com";
$config['template'] = "cdm2023";
$config['github'] = "https://github.com/johanpoirier/bet4rugby";

$config['email_simulation'] = false;
$config['support_email'] = $config['email'];
$config['support_team'] = "cdm2023";

$config['email_address_sender'] = 'cdm2023@jops-dev.com';
$config['email_address_replyto'] = $config['email'];
$config['email_simulation'] = false;
$config['email_use_third_party_sender'] = false;
$config['sendinblue_apikey'] = 'your api key';

$config['sentry_enable'] = false;
$config['sentry_dsn'] = 'your Sentry dsn';

$config['db_common_prefix'] = 'bet4soccer__';
$config['db_prefix'] = "rwc2019__";

$config['secret_key'] = 'this is a rugby world cup';

$config['invitation_expiration'] = 30; /* in days */

//                     hh,m,s,M,J,AAAA
$config['steps'][0] = "21,00,0,9,8,2023";

// rugby bet score data
$config['limite1'] = 20;
$config['ecart1a'] = 1;
$config['ecart1b'] = 4;
$config['limite2'] = 40;
$config['ecart2a'] = 3;
$config['ecart2b'] = 8;
$config['limite3'] = 60;
$config['ecart3a'] = 5;
$config['ecart3b'] = 12;
$config['ecart4a'] = 7;
$config['ecart4b'] = 20;
