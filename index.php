<?php
$software = '6.3.100.38'; //The tested version SoftWare
$bootrom = '5.1.3'; //The tested version BootRom
require_once "config.php";
require_once "PHPTelnet.php";
$s = fopen($fname = "swichs.txt", "rt");
$swich = explode(";", fread($s, filesize($fname)));
$colvo = count($swich);
echo "Set of testable switches: ", $colvo . "<br>";
require_once('library/Logger.php');

$login = $config["device"]["login"];
$password = $config["device"]["password"];

$telnet = new PHPTelnet();
$logger = new Logger('commutators_log');

$logger->info('This is first log comment');
echo $logger->file();

$logger->info('This is second log comment 2');
/*
for ($z = 0; $z < $colvo; $z++) {
    echo('<br>');
    echo "$swich[$z]  - ";
//$ip = $_GET['swiID'];
//$ranIP = array("qsw2800");

    $result = $telnet->Connect($swich[$z], $login, $password);

    if ($result == 0) {
        $telnet->DoCommand('', $result);
        if (stripos($result, '2800') !== false) {


            $telnet->DoCommand('sh ver', $result);
            // NOTE: $result may contain newlines
            parse_str($result);

            $soft = strstr((string)$result, "SoftWare Version ");
            if (stripos($result, $software) == false) {
                $nup = fopen("need updating.txt", "a");
                fwrite($nup, "$swich[$z];");
                fclose($nup);
            }
            $boot = strstr((string)$result, "BootRom Version ");
            if (stripos($result, $bootrom) == false) {
                $nup = fopen("boot updating.txt", "a");
                fwrite($nup, "$swich[$z];");
                fclose($nup);
            }
            echo substr($soft, 0, 28);
            echo substr($boot, 0, 25);
            echo('<br>');
            // echo $result;
               $telnet->DoCommand('reload', $result);
               if (stripos($result, 'Process with reboot? [Y/N]') !== false) {
               $telnet->DoCommand('y', $result); }
               print_r($result);
        } else {
            echo $result;
        }
        $telnet->Disconnect();
    }
}
*/

//echo ($ip);