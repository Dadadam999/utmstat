<?php
/**
 * Plugin Name: utmstat
 * Plugin URI: https://github.com/
 * Description: Плагин, для отслеживания utm меток и внешних ссылок. Последние переходы доступны в профиле пользователя под админом или мененджером.
 * Version: 1.0.0
 * Author: Bogdanov Andrey
 * Author URI: mailto://swarzone2100@yandex.ru
 *
 * @package Кнопка НМО
 * @author Bogdanov Andrey (swarzone2100@yandex.ru)
 * @since 1.0.9
*/
require_once __DIR__.'/utmstat-autoload.php';

use utmstat\TableMananger;
use utmstat\Main;

register_activation_hook(__FILE__, 'Install');
register_deactivation_hook(__FILE__, 'Uninstall');

function Install()
{
  $tables = new TableMananger();
  $tables->Install();
}

function Uninstall()
{
  $tables = new TableMananger();
  $tables->Uninstall();
}

new Main();
