<?php

namespace App\Model\Entity;

use \App\Db\Database;


class Calendario
{

  public static function getEventoCalendario(){
    return (new Database(null, 'emerjco_evento'))->select3(
      "SELECT DISTINCT(pt.data) as data, et.nome as nome, et.codigo as codigo FROM evento et
        JOIN porta pt
          ON pt.codevento = et.codigo
            WHERE pt.data >= '2021-01-01'
              ORDER BY pt.data ASC;"
    );
  }
}
