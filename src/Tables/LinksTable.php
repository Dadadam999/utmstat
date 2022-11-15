<?php
/**
 * @package utmstat
 * @author Bogdanov Andrey (swarzone2100@yandex.ru)
 */

namespace utmstat\Tables;

class LinksTable
{
    protected $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function Create()
    {
        $this->wpdb->get_results(
           "CREATE TABLE `" . $this->wpdb->prefix . "utmstat_links`
           (
             id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
             user_id INT(20) UNSIGNED,
             ip VARCHAR(256),
             url VARCHAR(2048),
             source VARCHAR(2048),
             medium VARCHAR(2048),
             campaign VARCHAR(2048),
             referal VARCHAR(2048),
             date_referer DATETIME,
	           UNIQUE KEY id (id)
           )"
        );
    }

    public function Delete()
    {
        $this->wpdb->get_results(
          "DROP TABLE `" . $this->wpdb->prefix . "utmstat_links`"
        );
    }

    public function GetAll()
    {
      return $this->wpdb->get_results(
         "SELECT *
         FROM `" . $this->wpdb->prefix . "utmstat_links`",
         ARRAY_A
        );
    }

    public function GetByUser($user_id)
    {
      return $this->wpdb->get_results(
         "SELECT *
         FROM `" . $this->wpdb->prefix . "utmstat_links`
         WHERE `user_id` = " . $user_id,
         ARRAY_A
        );
    }

    public function GetLastByUser($user_id)
    {
      if(empty($user_id))
          $user_id = -1;

      return $this->wpdb->get_results(
         "SELECT *
         FROM `" . $this->wpdb->prefix . "utmstat_links`
         WHERE `user_id` = " . $user_id . " ORDER BY `date_referer` DESC LIMIT 1",
         ARRAY_A
         )[0];
    }

    public function GetByUrl($url)
    {
      return $this->wpdb->get_results(
         "SELECT *
         FROM `" . $this->wpdb->prefix . "utmstat_links`
         WHERE `url` = '" . $url . "'",
         ARRAY_A
        )[0];
    }

    public function GetByIp($ip)
    {
      return $this->wpdb->get_results(
         "SELECT *
         FROM `" . $this->wpdb->prefix . "utmstat_links`
         WHERE `ip` = '" . $ip . "'",
         ARRAY_A
        )[0];
    }

    public function CheckUserId($ip)
    {
      return $this->wpdb->get_results(
         "SELECT id
          FROM `" . $this->wpdb->prefix . "utmstat_links`
          WHERE `ip` = '" . $ip . "' AND ( `user_id` is NULL OR `user_id` = 0 )",
         ARRAY_A
        );
    }

    public function UpdateUserByIp($user_id, $ip)
    {
      $this->wpdb->get_results(
         "UPDATE `" . $this->wpdb->prefix . "utmstat_links`
         SET `user_id` = ". $user_id . "
         WHERE `ip` = '" . $ip . "' AND ( `user_id` is NULL OR `user_id` = 0 )"
      );
    }

    public function Add($user_id, $ip, $url, $source, $medium, $campaign, $referal, $date_referer)
    {
      $last_id = $this->wpdb->get_results(
      "SELECT MAX(`id`) AS last
       FROM `" . $this->wpdb->prefix . "utmstat_links`", ARRAY_A
      );

      $current_id = (int)$last_id[0]['last'] + 1;
      $this->wpdb->get_results(
        "INSERT INTO `" . $this->wpdb->prefix . "utmstat_links` (`id`, `user_id`, `ip`, `url`, `source`, `medium`, `campaign`, `referal`, `date_referer`)
        VALUES (" . $current_id . ", " . $user_id . ", '" .  $ip . "', '" .  $url . "', '" .  $source . "', '" .  $medium . "', '" .  $campaign . "', '" .  $referal . "',
        '" .  $date_referer . "')"
      );
    }

    public function DeleteLink($id)
    {
        $this->wpdb->get_results(
        "DELETE FROM `" . $this->wpdb->prefix . "utmstat_links` WHERE id = " . $id
        );
    }
}
