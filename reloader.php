<?php


require_once "config.php";
require_once "library/PHPTelnet.php";
require_once "library/Logger.php";
$software = $config["device"]["software"];
$bootrom = $config["device"]["bootrom"];
$s = fopen($fname = "logs/swichs.txt", "rt");
$swich = explode(";", fread($s, filesize($fname)));
$colvo = count($swich);
//echo "Set of testable switches: ", $colvo . "<br>";


$login = $config["device"]["login"];
$password = $config["device"]["password"];

$telnet = new PHPTelnet();
$logger = new Logger('commutators_log', 'reloader');
$softup = new Selector('vers_updating');
$bootup = new Selector('boot_updating');

for ($z = 0; $z < $colvo; $z++) {
  //  echo('<br>');
  //  echo "$swich[$z]  - ";

    $result = $telnet->Connect($swich[$z], $login, $password);
    $log = $telnet->ConnectError($result);


    if ($result == 0) {
          $telnet->DoCommand('reload', $result);
              if (stripos($result, 'Process with reboot? [Y/N]') !== false) {
                 // echo - "reload";
                  $logger->info("$swich[$z],reload");
                  $telnet->DoCommand('y', $result);


              }

    }

}
