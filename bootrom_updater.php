<?php
require_once "config.php";
require_once "library/PHPTelnet.php";
require_once "library/Logger.php";
$s = fopen($fname = "logs/.boot_updating", "rt");
$swich = explode(";", fread($s, filesize($fname)));
$colvo = count($swich);

$login = $config["device"]["login"];
$password = $config["device"]["password"];

$telnet = new PHPTelnet();
$logger = new Logger('commutators_log', 'Bootrom Updater');
$softup = new Selector('.vers_updating');
$bootup = new Selector('.boot_updating');

for ($z = 0; $z < $colvo; $z++) {
    echo('<br>');
    echo "$swich[$z]  - ";

    $result = $telnet->Connect($swich[$z], $login, $password);
    $log = $telnet->ConnectError($result);


    if ($result == 0) {

            $telnet->DoCommand('', $result);

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
            echo $reformvers, $reformboot;
            echo('<br>');
            // echo $result;
            /*  $telnet->DoCommand('reload', $result);
              if (stripos($result, 'Process with reboot? [Y/N]') !== false) {
              $telnet->DoCommand('y', $result); }
              print_r($result);
           */
            $log = ("$swich[$z] - $reformvers $reformboot");
            $logger->info($log);

        }

        $telnet->Disconnect();

        echo $log;
        $logger->info("$swich[$z] - $log");

}