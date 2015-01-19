<?php
require_once "config.php";
require_once "library/PHPTelnet.php";
require_once "library/Logger.php";
$bootrom = $config["device"]["bootrom"];
$s = fopen($fname = "logs/swichs.txt", "rt");
$swich = explode(";", fread($s, filesize($fname)));
$colvo = count($swich);

$login = $config["device"]["login"];
$password = $config["device"]["password"];

$telnet = new PHPTelnet();
$logger = new Logger('commutators_log', 'Software Updater');
$softup = new Selector('vers_updating');
$bootup = new Selector('boot_updating');

for ($z = 0; $z < $colvo; $z++) {
 //   echo('<br>');
 //   echo "$swich[$z]  - ";

    $result = $telnet->Connect($swich[$z], $login, $password);
    $log = $telnet->ConnectError($result);


    if ($result == 0) {


        $telnet->DoCommand($config["device"]["ftp-boot.rom"], $result);
     //   echo($result);
        if (stripos($result, 'Confirm to overwrite the existed destination file?  [Y/N]') !== false) {
            $telnet->DoCommand('y', $result, 30 * 1000000);
        //    echo $result;

     //   $logger->info($swich[$z], $result);
        }

        if (stripos($result, 'Write ok.') !== false) {
            $telnet->DoCommand($config["device"]["ftp-nos.img"], $result);
            if (stripos($result, 'Confirm to overwrite the existed destination file?  [Y/N]') !== false) {
                $telnet->DoCommand('y', $result, 180 * 1000000); }
          //  echo('<br>');
          //  echo $result;

       //     $logger->info($swich[$z], $result);
           if (stripos($result, 'Write ok.') == false) {
        //      echo ("soft update WTF error");
               $logger->info("$swich[$z] - Ошибка software");
           break; }

        } else {
          //  echo ("boot update WTF error");
            $logger->info("$swich[$z] - Ошибка bootrom");
            break;
        }

         parse_str($result);


       //   echo($result);
        $logger->info("$swich[$z] - Успешно");
    }

    $telnet->Disconnect();



}
?>