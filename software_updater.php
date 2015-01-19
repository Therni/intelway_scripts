<?php
# Подключаем необходимые библиотеки
require_once "config.php";
require_once "library/Telnet.php";
require_once "library/Logger.php";

$bootrom = $config["device"]["bootrom"]; # Версия bootrom

# Получаем данные по коммутаторам для обновления
$switch_file = fopen($fname = "logs/swichs.txt", "rt");
$switch_list = explode(";", fread($switch_file, filesize($fname)));

# Получаем данные доступа из конфигурации
$login = $config["device"]["login"];
$password = $config["device"]["password"];

# Создаем дополнительные объекты
$logger = new Logger('commutators_log', 'Software Updater');
$softup = new Selector('vers_updating');
$bootup = new Selector('boot_updating');

# Перебираем список всех коммутаторов
foreach ($switch_list as $switch_ip) {

    echo '<br>';
    echo $switch_ip . ' - ';

    # Записываем переменные по умолчанию
    $switch_status = 'Write bootrom completed';

    try {
        # Создаем новый объект с сокетом подключения по Telnet
        $telnet = new Telnet($switch_ip, 23, 10, 'login:', 10);

        # Авторизуемся на устройстве
        $result = $telnet->setPrompt('password:');
        $result = $telnet->exec($login);
        $result = $telnet->setPrompt('#');
        $result = $telnet->exec($password);

        # Выполняем команду обновления BootRom
        $result = $telnet->exec($config["device"]["ftp-boot.rom"]);

        if (stripos($result, 'Confirm to overwrite the existed destination file?  [Y/N]') !== false) {
            # Если коммутатор спрашивает подтверждение, подтверждаем
            $result = $telnet->exec('y');
        }
        # Уничтожаем объект (деструктор сделает disconnect)
        $telnet = null;

    } catch (Exception $error) {
        # Если на любом этапе возникнет ошибка, запишем ее в статус
        $switch_status = $error;
    }

    # Выведем результат выполнения для свича
    echo $switch_status;

    # Записываем в лог результат обработки
    $logger->info($switch_ip, $switch_status);

}