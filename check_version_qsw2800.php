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
$logger = new Logger('commutators_log', 'Check Version qsw 2800');
$softup = new Selector('vers_updating');
$bootup = new Selector('boot_updating');

for ($z = 0; $z < $colvo; $z++) {
   // echo('<br>');
  //  echo "$swich[$z]  - ";

    $result = $telnet->Connect($swich[$z], $login, $password);
    $log = $telnet->ConnectError($result);


    if ($result == 0) {
        $telnet->DoCommand('', $result);
        if (stripos($result, '2800') !== false) {


            $telnet->DoCommand('sh ver', $result);

            parse_str($result);

            $soft = strstr((string)$result, "SoftWare Version ");
            if (stripos($result, $software) == false) {
                $softup->note("$swich[$z];");
            }
            $boot = strstr((string)$result, "BootRom Version ");
            if (stripos($result, $bootrom) == false) {
                $bootup->note("$swich[$z];");
            }
            $reformvers = substr($soft, 0, 28);
            $reformboot = substr($boot, 0, 25);
        //    echo $reformvers, $reformboot;
        //    echo('<br>');
         //    echo $result;
             /*  $telnet->DoCommand('reload', $result);
               if (stripos($result, 'Process with reboot? [Y/N]') !== false) {
               $telnet->DoCommand('y', $result); }
               print_r($result);
            */
            $log = ("$swich[$z] - $reformvers $reformboot");
            $logger->info($log);

        } else {
            $qsw = $result;
         //   echo $qsw;
            $qsw = mb_substr($qsw, 1, -1);
            $logger->info("$swich[$z] - $qsw");
        }

        $telnet->Disconnect();
    } else {
   // echo $log;
    $logger->info("$swich[$z] - $log");
    }

}




