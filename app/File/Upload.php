<?php

namespace App\File;

class Upload{

/**
 * Nome do arquivo (sem extensão)
 * @var string
 */
public $name;

/**
 * Extensão do arquivo (sem ponto)
 * @var string
 */
public $extension;

/**
 * Nome temporário/Caminho temporário do arquivo
 * @var string
 */
private $tmpName;

/**
 * Código de erro do upload
 * @var integer
 */
public $error;

/**
 * Tamanho do arquivo
 * @var string
 */
public $size;

/**
 * Contador de duplicação de arquivo
 * @var integer
 */
private $duplicates = 0;

/**
 * Construtor da classe
 * @param array $file
 */
public function __construct($file){
    $this->type      = $file['type'];
    $this->tmpName   = $file['tmp_name'];
    $this->error     = $file['error'];
    $this->size      = $file['size'];

    $info = pathinfo($file['name']);
    $infotmp = pathinfo($file['tmp_name']);
    $this->name      = $info['filename'];
    $this->tmp_name      = $infotmp['filename'];
    $this->extension = $info['extension'];
  }

/**
 * Método responsável por alterar o nome do arquivo
 * @param string $name
 */
  public function setName($name){
    $this->name = $name;
  }

 /**
  * Método responsável por gerar um nome aleatório para o arquuivo
  */
  public function generateNewName(){
    $this->name = time().'-'.rand(10000000,99999999).'-'.uniqid();
  }

/**
 * Método responsável por retornar o nome do arquivocom sua extensão
 * @return string
 */
  public function getBasename(){
    //VALIDA EXTENSÃO
    $extension = strlen($this->extension) ? '.'.$this->extension : '';

    $extension = strtolower($extension);

    //VALIDA DUPLICAÇÃO
    $duplicates = $this->duplicates > 0 ? '-'.$this->duplicates : '';

    //RETORNA O NOME COMPLETO
    return $this->name.$duplicates.$extension;
  }
/**
 * Método responsável por receber onome possível para o arquivo
 * @param  string $dir
 * @param  boolean $overwrite
 * @return string
 */
  private function getPossibleBasename($dir,$overwrite){
    //SOBRESCREVER ARQUIVO
    if($overwrite) return $this->getBasename();

    //NÃO PODE SOBRESCREVER ARQUIVO
    $basename = $this->getBasename();

    //VERIFICA DUPLICAÇÃO
    if(!file_exists($dir.'/'.$basename)){
      return $basename;
    }
    //INCREMENTAR DUPLICAÇÕES
    $this->duplicates++;

    //RETORNO O PRÓPRIO MÉTODO
     return $this->getPossibleBasename($dir,$overwrite);
  }

/**
 * Método responsável por mover o arquivo de upload
 * @param string $dir
 * @param boolean $overwrite
 * @return boolean
 */
  public function upload($dir, $overwrite = true){
    //VERIFICAR ERRO
    if($this->error != 0) return false;

    //CAMINHO COMPLETO DE DESTINO
    $path = $dir.'/'.$this->getPossibleBasename($dir,$overwrite);

    //MOVE O ARQUIVO PARA A PASTA DE DESTINO
    return move_uploaded_file($this->tmpName,$path);

      //Valor: 1; "O tamanho do arquivo enviado excede o limite máximo permitido."
      //Valor: 2; "O arquivo excede o tamanho limite definido."
      //Valor: 3; "Ocorreu uma falha durante o envio do arquivo. O upload do arquivo foi feito parcialmente."
      //Valor: 4; "Nenhum arquivo foi enviado."
      //Valor: 6; "Pasta temporária ausênte."
      //Valor: 7; "Falha em escrever o arquivo em disco."
      //Valor: 8; "Uma extensão do arquivo enviado, não permitida, interrompeu o upload do arquivo."
  }

  /**
   * Método responsável por criar instâncias de uploads para múltiplos arquivos
   * @param  array $files $_FILES['campo']
   * @return array
   */
  public static function createMultiUpload($files){
    $uploads = [];
    foreach ($files['name'] as $key => $value) {
      //ARRAY DE ARQUIVO
      $file = [
        'name'     => $files['name'][$key],
        'type'     => $files['type'][$key],
        'tmp_name' => $files['tmp_name'][$key],
        'error'    => $files['error'][$key],
        'size'     => $files['size'][$key]
      ];
      //NOVA INSTÂNCIA
      $uploads[] = new Upload($file);
    }
    return $uploads;
  }
}
