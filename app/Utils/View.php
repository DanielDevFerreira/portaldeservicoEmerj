<?php

namespace App\Utils;

class View{

  /**
   * Variáveis padrÕES da view
   * @var array
   */
  private static $vars = [];

  /**
   * Método responsável por definir os dados iniciais da classe
   * @param array $vars
   */
  public static function init($vars = []){
    self::$vars = $vars;
  }

  /**
   * Método responsável por retornar o conteúdo de uma view
   * @param string $view
   * @return string
   */
  private static function getContentView($view){
    $file = __DIR__.'/../../resources/view/'.$view.'.html';
    //echo "<pre>";    print_r($file);    echo "</pre>"; //exit;
    return file_exists($file) ? file_get_contents($file) : '';
  }

  /**
   * Método responsável por retornar o conteúdo renderizado de uma view
   * @param string $view
   * @param array $rars (string/numeric)
   * @return string
   */
  public static function render($view, $vars = []){
    // CONTEÚDO DA VIEW
    $contentView = self::getContentView($view);

    //MERGE DE VARIAVEIS DA VIEW
    $vars = array_merge(self::$vars,$vars);

    //DESCUBRIR AS CHAVES DO ARRAY DE VARIÁVEIS
    $keys = array_keys($vars);
    $keys = array_map(function($item){
      return '{{'.$item.'}}';
    },$keys);

  //  echo "<pre>";    print_r($keys);    echo "</pre>";
  //  echo "<pre>";    print_r($vars);    echo "</pre>";
    //RETORNA O CONTEÚDO RENDERIZADO
    return str_replace($keys,array_values($vars),$contentView);
  }
}
