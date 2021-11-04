<?php

namespace App\Utils;

class Geralog{



  public static $instance;

      private function __construct() {
          //
      }

      public static function getInstance(){
          if (!isset(self::$instance))
          self::$instance = new Geralog();

          return self::$instance;
      }

      public function inserirLog($tipo,$msg){

          date_default_timezone_set('America/Sao_Paulo');

          $strNome = "admin/logs/".$tipo."_log_".date("d-m-Y").".txt";
          $msg = $msg." em: ".date("d-m-Y, H:i:s")."\n";
          $fp = fopen($strNome,'a');
          fwrite($fp,$msg);
          fclose($fp);
      }

  }
