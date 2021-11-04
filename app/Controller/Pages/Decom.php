<?php

namespace App\Controller\Pages;

use \DateTime;

use \App\Utils\View;
use \App\Model\Entity\Decom as EntityDecom;

class Decom extends Page{

  /**
   * Método responsável por montar a renderização do select de Conteúdos do Decom para o formulário
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getDecom($request){
    //CONTEÚDO DA HOME
    $content = View::render('pages/decom/index',[
      'linktree' => self::getDecomLinktree($request),
    ]);
    //RETORNA A PÁGINA COMPLETA
    return parent::getPage('EMERJ - Escola da Magistratura do Estado do Rio de Janeiro',$content,'Serviços DECOM');
  }

  /**
   * Método responsável por obter a renderização dos itens de Chamados para a página
   * @param Request $request
   * @return string
   */
    private static function getDecomLinktree($request){

      date_default_timezone_set('America/Sao_Paulo');

      $itens = '';
      $where = ' decom_tipo = 1 AND "'. date('Y-m-d H:i') .'" BETWEEN data_inicio AND data_fim';

      $results = EntityDecom::getDecoms($where,' data_up desc');

      while($obDecom = $results->fetchObject(EntityDecom::class)){
        $itens .= View::render('pages/decom/linktree',[
          'idSelect' => $obDecom->decom_id,
          'icon' => $obDecom->decom_icon,
          'style' => $obDecom->decom_style,
          'txturl' => $obDecom->decom_txturl,
          'descricao' => $obDecom->decom_descricao,
          'nome' => $obDecom->decom_nm,
          'url' => $obDecom->decom_url
        ]);
      }
      return $itens;
   }
}
