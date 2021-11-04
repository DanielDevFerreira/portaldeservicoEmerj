<?php

namespace App\Controller\Pages;

use \App\Model\Entity\Calendario as EntityCalendario;


class Calendario extends Page{

    public static function getEventosCalendario($request,$errorMessage = null){

        $itens = [];

        $results = EntityCalendario::getEventoCalendario();
        
        while($obServico = $results->fetchObject(EntityCalendario::class)){   
            array_push($itens, $obServico);
        }

        return $itens;
    }
}