<?php

namespace App\Controller\Admin;

use \DateTime;

use \App\Utils\View;
use \App\Model\Entity\Decom as EntityDecom;
use \App\Controller\Admin\Servicos as AdminServico;
use \App\Controller\Admin\Itensconfs as AdminItensconfs;
use \App\Db\Pagination;

const DIR_DECOM = 'decom';
const FIELD_DECOM = 'decom';
const ROTA_DECOM = 'decoms';
const ICON_DECOM = 'decom';
const TITLE_DECOM = 'Conteúdo do Decom';
const TITLELOW_DECOM = 'o conteúdo do Decom';

class Decoms extends Page{

  /**
   * Método responsável pela renderização da view de listagem de Localizacões
   * @param Request $request
   * @return string
   */
    public static function getListDecoms($request,$errorMessage = null){

      $permissao = false;
      $status = self::getStatus($request);
      $currentDepartamento = $_SESSION['admin']['usuario']['departamento'];
      $currentPerfil = $_SESSION['admin']['usuario']['id_perfil'];

      //STATUS
      if(!isset($currentDepartamento)) return $permissao = false;

     //MENSAGENS DE STATUS
     switch ($currentDepartamento) {
       case 'DECOM':
         $permissao = true;
         break;
       case 'DETEC':
         $permissao = true;
         break;
       case 'EMERJ':
         $permissao = true;
         break;
     }
     //STATUS
     if(!isset($currentPerfil)) return $permissao = false;

    //MENSAGENS DE STATUS
    switch ($currentPerfil) {
      case 1:
        $permissao = true;
        break;
      case 2:
        $permissao = true;
        break;
    }

      if (!$permissao) {
        $request->getRouter()->redirect('/?status=sempermissao');
      }

      //CONTEÚDO DA HOME
      $content = View::render('admin/modules/'.DIR_DECOM.'/index',[
        'icon' => ICON_DECOM,
        'title' =>TITLE_DECOM,
        'titlelow' => TITLELOW_DECOM,
        'direntity' => ROTA_DECOM,
        'itens' => self::getDecomItens($request,$obPagination),
        'status' => $status
      ]);
      //RETORNA A PÁGINA COMPLETA
      return parent::getPanel(TITLE_DECOM.' - EMERJ',$content,ROTA_DECOM,$currentDepartamento,$currentPerfil);
  }

  /**
   * Método responsável por obter a renderização das Conteúdos do Decom para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
   private static function getDecomItens($request,&$obPagination){

     $itens = '';
     $tipodeic = '';
     $idreferenciado = '';

     //RESONSÁVEL PELA RENDERIZAÇÃO DOS MODAIS DE EDIÇÃO NA PÁGINA DE LISTAGEM DE LOCALIZAÇÕES
     $strEditModal = View::render('admin/modules/'.DIR_DECOM.'/editmodal',[]);
     $strAddModal = View::render('admin/modules/'.DIR_DECOM.'/addmodal',[]);
     $strAtivaModal = View::render('admin/modules/'.DIR_DECOM.'/ativamodal',[]);
     $strDeleteModal = View::render('admin/modules/'.DIR_DECOM.'/deletemodal',[]);

     //RESULTADO DA PAGINA
     $results = EntityDecom::getDecoms();

     //MONTA E RENDERIZA OS ITENS DE Decom
     while($obDecom = $results->fetchObject(EntityDecom::class)){
       $itens .= View::render('admin/modules/'.DIR_DECOM.'/item',[
        'id' => $obDecom->decom_id,
        'nome' => $obDecom->decom_nm,
        'tipo' => ($obDecom->decom_tipo == 1) ? 'Link Decom' : '',
        'tipo_cod' => $obDecom->decom_tipo,
        'urlcont' => $obDecom->decom_url,
        'txturl' => $obDecom->decom_txturl,
        'dt_ini' => $obDecom->data_inicio,
        'dt_fim' => $obDecom->data_fim,
        'datainicio' => date('d/m/Y', strtotime($obDecom->data_inicio)),
        'horainicio' => date('H', strtotime($obDecom->data_inicio)),
        'mininicio' => date('i', strtotime($obDecom->data_inicio)),
        'datafim' => date('d/m/Y', strtotime($obDecom->data_fim)),
        'horafim' => date('H', strtotime($obDecom->data_fim)),
        'minfim' => date('i', strtotime($obDecom->data_fim)),
        'descricao' => $obDecom->decom_descricao ?? '',
        'icone' => $obDecom->decom_icon,
        'estilo' => $obDecom->decom_style,
        'texto_ativo' => ('s' == $obDecom->ativo_fl) ? 'Desativar' : 'Ativar',
        'class_ativo' => ('s' == $obDecom->ativo_fl) ? 'btn-warning' : 'btn-success',
        'style_ativo' => ('s' == $obDecom->ativo_fl) ? 'table-active' : 'table-danger',
        'icon' => ICON_DECOM,
        'title' =>TITLE_DECOM,
        'titlelow' => TITLELOW_DECOM,
        'direntity' => ROTA_DECOM
      ]);
    }
    $itens .= $strDeleteModal;
    $itens .= $strAtivaModal;
    $itens .= $strEditModal;
    $itens .= $strAddModal;
    return $itens;
  }



  /**
   * Método responsável por montar a renderização do select de Conteúdos do Decom para o formulário
   * @param Request $request
   * @param integer $id
   * @return string
   */
   public static function getDecomItensSelect($request,$id){
      $itensSelect = '';
      $resultsSelect = EntityDecom::getDecoms(null,'decom_id ASC');

      while($obDecom = $resultsSelect->fetchObject(EntityDecom::class)){
        $itensSelect .= View::render('admin/modules/'.DIR_DECOM.'/itemselect',[
          'idSelect' => $obDecom->decom_id,
          'selecionado' => ($id == $obDecom->decom_id) ? 'selected' : '',
          'nomeSelect' => $obDecom->decom_titulo
        ]);
      }
      return $itensSelect;
    }

    public static function getTransformaData($data)
    {
        [$d, $m, $y] = explode('/', $data);
        return implode('-', [$y, $m, $d]);
    }

   /**
    * Método responsável por cadastro de uma nova Localização no banco
    * @param Request $request
    * @return string
    */
    public static function setNovoDecom($request){

      date_default_timezone_set('America/Sao_Paulo');

      //DADOS DO POST
      $posVars = $request->getPostVars();
      $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_NUMBER_INT);
      $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
      $url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_STRING);
      $txturl = filter_input(INPUT_POST, 'txturl', FILTER_SANITIZE_STRING);
      $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
      $datainicio = filter_input(INPUT_POST, 'datainicio', FILTER_SANITIZE_STRING);
      $horainicio = filter_input(INPUT_POST, 'horainicio', FILTER_SANITIZE_STRING);
      $mininicio = filter_input(INPUT_POST, 'mininicio', FILTER_SANITIZE_STRING);
      $datafim = filter_input(INPUT_POST, 'datafim', FILTER_SANITIZE_STRING);
      $horafim = filter_input(INPUT_POST, 'horafim', FILTER_SANITIZE_STRING);
      $minfim = filter_input(INPUT_POST, 'minfim', FILTER_SANITIZE_STRING);

      $data_inicio = self::getTransformaData($datainicio).' '.$horainicio.':'.$mininicio.':00';
      $data_fim = self::getTransformaData($datafim).' '.$horafim.':'.$minfim.':00';

      $agora = date("Y-m-d H:i:s");
      $agoraDiff = new DateTime($agora);
      $data_inicioDiff = new DateTime($data_inicio);
      $data_fimDiff = new DateTime($data_fim);

      $intervalo1 = $data_inicioDiff->diff($agoraDiff);

      if ($agoraDiff >= $data_inicioDiff) {
        $request->getRouter()->redirect('/admin/decoms?status=updatefaildateAgora');
      }

      $intervalo2 = $data_fimDiff->diff($data_inicioDiff);

      if (($intervalo2->d < 1) || ($data_fimDiff <= $data_inicioDiff)) {
        $request->getRouter()->redirect('/admin/decoms?status=updatefaildatediff');
      }
      //NOVA ISNTANCIA DE DEPARTAMENTO
      $obDecom = new EntityDecom;

      $obDecom->decom_nm = $nome;
      $obDecom->decom_tipo = $tipo;
      $obDecom->decom_url = $url;
      $obDecom->decom_txturl = $txturl;
      $obDecom->decom_descricao = $descricao;
      $obDecom->data_inicio = $data_inicio;
      $obDecom->data_fim = $data_fim;
      $obDecom->cadastrar();

      //REDIRECIONA O DEPARTAMENTO
      $request->getRouter()->redirect('/admin/decoms?status=gravado&nm='.$nome.'&acao=alter');
    }

   /**
    * Método responsável por gravar a edição de uma Localização
    * @param Request $request
    * @param integer $id
    * @return string
    */
    public static function setEditDecom($request,$id){

      //DADOS DO POST
      $posVars = $request->getPostVars();
      $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
      $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
      $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
      $url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_STRING);
      $txturl = filter_input(INPUT_POST, 'txturl', FILTER_SANITIZE_STRING);
      $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
      $datainicio = filter_input(INPUT_POST, 'datainicio', FILTER_SANITIZE_STRING);
      $horainicio = filter_input(INPUT_POST, 'horainicio', FILTER_SANITIZE_STRING);
      $mininicio = filter_input(INPUT_POST, 'mininicio', FILTER_SANITIZE_STRING);
      $datafim = filter_input(INPUT_POST, 'datafim', FILTER_SANITIZE_STRING);
      $horafim = filter_input(INPUT_POST, 'horafim', FILTER_SANITIZE_STRING);
      $minfim = filter_input(INPUT_POST, 'minfim', FILTER_SANITIZE_STRING);

      $data_inicio = self::getTransformaData($datainicio).' '.$horainicio.':'.$mininicio.':00';
      $data_fim = self::getTransformaData($datafim).' '.$horafim.':'.$minfim.':00';

      $agora = date("Y-m-d H:i:s");
      $agoraDiff = new DateTime($agora);
      $data_inicioDiff = new DateTime($data_inicio);
      $data_fimDiff = new DateTime($data_fim);

      $intervalo1 = $data_inicioDiff->diff($agoraDiff);

      if ($agoraDiff >= $data_inicioDiff) {
        $request->getRouter()->redirect('/admin/decoms?status=updatefaildateAgora');
      }

      $intervalo2 = $data_fimDiff->diff($data_inicioDiff);

      if (($intervalo2->d < 1) || ($data_fimDiff <= $data_inicioDiff)) {
        $request->getRouter()->redirect('/admin/decoms?status=updatefaildatediff');
      }

      //OBTÉM O USUÁRIO DO BANCO DE DADOS
      $obDecom = EntityDecom::getDecomPorId($id);

      if(!$obDecom instanceof EntityDecom){
        $request->getRouter()->redirect('/admin/decoms?status=updatefail');
      }

      //ATUALIZA A INSTANCIA
      $obDecom->decom_id = $id;
      $obDecom->decom_nm = $nome;
      $obDecom->decom_tipo = $tipo;
      $obDecom->decom_url = $url;
      $obDecom->decom_txturl = $txturl;
      $obDecom->decom_descricao = $descricao;
      $obDecom->data_inicio = $data_inicio;
      $obDecom->data_fim = $data_fim;
      $obDecom->atualizar();

      //REDIRECIONA O USUÁRIO PARA A PAGINA INICIAL DE LISTAR CATEGORIAS DE IC's
      $request->getRouter()->redirect('/admin/decoms?status=alterado&nm='.$nome.'&acao=alter');
    }

    /**
     * Método responsável por retornar o formulário de alteração de status de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     */
     public static function getAltStatusDecom($request,$id){

       //OBTÉM A CATEGORIA DO IC PELO SEU ID DO BANCO DE DADOS
       $obDecom = EntityDecom::getDecomPorId($id);
       $strNome = $obDecom->decom_nm;



       if(!$obDecom instanceof EntityDecom){
         $request->getRouter()->redirect('/admin/decoms?status=updatefail');
       }

       //OBTÉM O USUÁRIO DO BANCO DE DADOS
       if($obDecom->ativo_fl == 's'){
         $altStatus = 'n';
         $strMsn = ' DESATIVADO ';
       } elseif (($obDecom->ativo_fl == 'n') || ($obDecom->ativo_fl == '')) {
         $strMsn = ' ATIVADO ';
         $altStatus = 's';
       }

       //ATUALIZA A INSTANCIA (RESETA A SENHA DO USUÁRIO)
       $obDecom->ativo_fl = $altStatus;
       $obDecom->atualizar();

       //REDIRECIONA O USUÁRIO
       $request->getRouter()->redirect('/admin/decoms?status=statusupdate&nm='.$strNome.$strMsn);

     }

   /**
      * Método responsável por retornar o formulário de exclusão de um usuário atraves de um Modal
      * @param Request $request
      * @param integer $id
      * @return string
      */
      public static function getDeleteDecom($request,$id){

       //OBTÉM O LOCALIZAÇÃO DO BANCO DE DADOS
        $obDecom = EntityDecom::getDecomPorId($id);
        $strNome = $obDecom->decom_nm;

        if(!$obDecom instanceof EntityDecom){
          $request->getRouter()->redirect('/admin/decoms');
        }

       //EXCLUI O USUÁRIO
        $obDecom->excluir();
        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/decoms?status=deletado&nm='.$strNome.$strMsn.'&acao=alter');
      }

    /**
     * Método responsável por retornar a mensagem de status
     * @param Request $request
     * @return string
     */
     private static function getStatus($request){
      //QUERY PARAMS
      $queryParams = $request->getQueryParams();
      $nm = filter_input(INPUT_GET, 'nm', FILTER_SANITIZE_STRING) ?? '';
      $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING);

      //STATUS
      if(!isset($status)) return '';

     //MENSAGENS DE STATUS
     switch ($status) {
       case 'gravado':
         return Alert::getSuccess('Dados d'.TITLE_DECOM.' <strong>'.$nm.'</strong> cadastrados com sucesso!');
         // code...
         break;
       case 'alterado':
         return Alert::getSuccess('Dados d'.TITLELOW_DECOM.' <strong>'.$nm.'</strong> alterados com sucesso!');
         // code...
         break;
       case 'deletado':
         return Alert::getSuccess('Registro d'.TITLE_DECOM.'  <strong>'.$nm.'</strong> deletado com sucesso!');
         // code...
         break;
       case 'updatefaildatediff':
         return Alert::getError('A diferença entre as datas fim e a início não pode ser inferior a um dia!');
         // code...
         break;
       case 'updatefaildateAgora':
         return Alert::getError('A data de início não pode ser anterior a data/hora de agora!');
         // code...
         break;

       case 'duplicado':
         return Alert::getError('Já existe '.TITLELOW_DECOM.' com este nome (<strong>'.$nm.'</strong>) para outr'.TITLELOW_DECOM.'!');
         // code...
         break;
       case 'statusupdate':
         return Alert::getSuccess('Status d'.TITLELOW_DECOM.' <strong>'.$nm.'</strong> com sucesso!');
         // code...
         break;
     }
   }
}
