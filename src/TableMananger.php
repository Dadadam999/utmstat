<?php
/**
 * @package utmstat
 * @author Bogdanov Andrey (swarzone2100@yandex.ru)
 */

 namespace utmstat;

 use WP_REST_Request;
 use wpdb;

 use utmstat\Tables\LinksTable;
 use utmstat\Tables\ClikcsTable;

 class TableMananger
 {
   protected $wpdb;
   public $linksTable;

   public function __construct()
   {
       global $wpdb;
       $this->wpdb = $wpdb;
       $this->Init();
   }

   protected function Init() : self
   {
     $this->linksTable = new LinksTable();
     return $this;
   }

   public function Install()
   {
     $this->linksTable->Create();
   }

   public function Uninstall()
   {
     $this->linksTable->Delete();
   }
 }
?>
