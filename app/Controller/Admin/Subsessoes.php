<?php

namespace App\Controller\Admin;

use \DateTime;

use \App\Utils\View;
use \App\Model\Entity\Subsessao as EntitySubsessao;
use \App\Model\Entity\Sessao as EntitySessao;
use \App\Controller\Admin\Servicos as AdminServico;
use \App\Controller\Admin\Sessoes as AdminSessoes;
use \App\Model\Entity\Arquivo as EntityArquivo;
use \App\File\Upload;
use \App\Db\Pagination;

const DIR_SUBSESSAO = 'subsessao';
const FIELD_SUBSESSAO = 'subsessao';
const ROTA_SUBSESSAO = 'subsessoes';
const ICON_SUBSESSAO = 'book';
const TITLE_SUBSESSAO = 'Subsessões';
const TITLELOW_SUBSESSAO = 'a Subsessão';

class Subsessoes extends Page{

  /**
   * Método responsável pela renderização da view de listagem de Localizacões
   * @param Request $request
   * @return string
   */
    public static function getListSubsessoes($request,$errorMessage = null){

      $permissao = false;
      $status = self::getStatus($request);
      $currentDepartamento = $_SESSION['admin']['usuario']['departamento'];
      $currentPerfil = $_SESSION['admin']['usuario']['id_perfil'];

      //STATUS
      if(!isset($currentDepartamento)) return $permissao = false;

     //MENSAGENS DE STATUS
     switch ($currentDepartamento) {
       case 'DECOM':
         $permissao = false;
         break;
       case 'DETEC':
         $permissao = true;
         break;
       case 'EMERJ':
         $permissao = false;
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
      $content = View::render('admin/modules/'.DIR_SUBSESSAO.'/index',[
        'icon' => ICON_SUBSESSAO,
        'title' =>TITLE_SUBSESSAO,
        'titlelow' => TITLELOW_SUBSESSAO,
        'direntity' => ROTA_SUBSESSAO,
        'itens' => self::getSubsessaoItens($request,$obSubsessaotion),
        'status' => $status
      ]);
      //RETORNA A PÁGINA COMPLETA
      return parent::getPanel(TITLE_SUBSESSAO.' - EMERJ',$content,ROTA_SUBSESSAO,$currentDepartamento,$currentPerfil);
  }

  /**
   * Método responsável por obter a renderização das Conteúdos do Subsessao para a página
   * @param Request $request
   * @param Subsessaotion $obSubsessaotion
   * @return string
   */
   private static function getSubsessaoItens($request,&$obSubsessaotion){

     $itens = '';
     $tipodeic = '';
     $idreferenciado = '';

     //RESONSÁVEL PELA RENDERIZAÇÃO DOS MODAIS DE EDIÇÃO NA PÁGINA DE LISTAGEM DE LOCALIZAÇÕES
     $strAtivaModal = View::render('admin/modules/'.DIR_SUBSESSAO.'/ativamodal',[]);
     $strDeleteModal = View::render('admin/modules/'.DIR_SUBSESSAO.'/deletemodal',[]);

     //RESULTADO DA SUBSESSAO
     $results = EntitySubsessao::getSubsessoes();

     //MONTA E RENDERIZA OS ITENS DE Subsessao
     while($obSubsessao = $results->fetchObject(EntitySubsessao::class)){
       $itens .= View::render('admin/modules/'.DIR_SUBSESSAO.'/item',[
        'id' => $obSubsessao->subsessao_id,
        'subsessao_nm' => $obSubsessao->subsessao_nm,
        'subsessao_titulo' => $obSubsessao->subsessao_titulo ?? '',
        'subsessao_conteudo' => $obSubsessao->subsessao_conteudo ?? '',
        'sessao_nm' => EntitySubsessao::getSubsessaoPorId($obSubsessao->id_sessao)->sessao_nm ?? '',
        'descricao' => $obSubsessao->subsessao_descricao ?? '',
        'subsessao_icon' => $obSubsessao->subsessao_icon ?? '',
        'subsessao_style' => $obSubsessao->subsessao_style ?? '',
        'texto_ativo' => ('s' == $obSubsessao->ativo_fl) ? 'Desativar' : 'Ativar',
        'class_ativo' => ('s' == $obSubsessao->ativo_fl) ? 'btn-warning' : 'btn-success',
        'style_ativo' => ('s' == $obSubsessao->ativo_fl) ? 'table-active' : 'table-danger',
        'icon' => ICON_SUBSESSAO,
        'title' =>TITLE_SUBSESSAO,
        'titlelow' => TITLELOW_SUBSESSAO,
        'direntity' => ROTA_SUBSESSAO
      ]);
    }
    $itens .= $strDeleteModal;
    $itens .= $strAtivaModal;
    return $itens;
  }

  /**
   * Método responsável por montar a renderização do select de Conteúdos do Subsessao para o formulário
   * @param Request $request
   * @param integer $id
   * @return string
   */
   public static function getSubsessaoItensSelect($request,$id){
      $itensSelect = '';
      $resultsSelect = EntitySubsessao::getSubsessoes(null,'subsessao_id ASC');

      while($obSubsessao = $resultsSelect->fetchObject(EntitySubsessao::class)){
        $itensSelect .= View::render('admin/modules/'.DIR_SUBSESSAO.'/itemselect',[
          'idSelect' => $obSubsessao->subsessao_id,
          'selecionado' => ($id == $obSubsessao->subsessao_id) ? 'selected' : '',
          'nomeSelect' => $obSubsessao->subsessao_titulo
        ]);
      }
      return $itensSelect;
    }

    /**
    * Método responsável pela exclusão dos arquivos temporários de upload dos Chamados
    * @param Request $request
    * @return string
    */
    public static function setRemoveUploadAjax($request){

      $id_arquivo = filter_input(INPUT_POST, 'id_arquivo', FILTER_SANITIZE_NUMBER_INT) ?? 0;

      $obArquivo = EntityArquivo::getArquivoPorId($id_arquivo);

      if(!$obArquivo instanceof EntityArquivo){
        echo "Arquivo não existe no banco.";
      }
      $arquivo = __DIR__.'/../../../files/'.ROTA_SUBSESSAO.'/tmp/'.$obArquivo->arquivo_temp;
      //EXCLUI O USUÁRIO
      $obArquivo->excluir();
      //REDIRECIONA O USUÁRIO
      if(file_exists($arquivo)){
         unlink($arquivo);
       } else {
         echo "Arquivo '.$arquivo.' não existe no diretório.";
       }
    }

    /**
    * Método responsável pela renderização da view de listagem de Chamados
    * @param Request $request
    * @return string
    */
    public static function redimensionaJPG($nome,$tamx,$tamy,$newJpg){

      $imagem_temporaria = imagecreatefromjpeg($nome);

			$largura_original = imagesx($imagem_temporaria);
			$altura_original = imagesy($imagem_temporaria);

			$imagem_redimensionada = imagecreatetruecolor($tamx,$tamy);
			imagecopyresampled($imagem_redimensionada, $imagem_temporaria, 0, 0, 0, 0, $tamx, $tamy, $largura_original, $altura_original);

			imagejpeg($imagem_redimensionada, $newJpg);

    }

  /**
  * Método responsável pela renderização da view de listagem de Chamados
  * @param Request $request
  * @return string
  */
  public static function setUploadAjax($request){

    $strMsnUpload = '';
    $strMsnUploadSucess = '';
    $strMsnUploadFail = '';
    $strMsnUploadError = '';
    if(isset($_FILES['arquivo'])){
      //INSTÂNCIAS DO UPLOAD
      $uploads = Upload::createMultiUpload($_FILES['arquivo']);
      foreach ($uploads as $obUpload) {

        $strIcon = '';
        $strNome = $obUpload->name ?? '';
        $strNomeTmp = $obUpload->tmp_name ?? '';
        $strSize = $obUpload->size ?? '';
        $strExtensao = strtolower($obUpload->extension) ?? '';

        //GERA UM NOME ALEATÓRIO PARA O ARQUIVO
        $obUpload->generateNewName();

        $allowed = array('jpg','jepg','png','gif');
        if(!in_array($strExtensao,$allowed)) {
          $strMsnUploadFail = $strMsnUploadFail.'
            <div class="alert mb-0 pr-2 pl-3 pt-2 pb-2 alert-warning alert-dismissible fade show" role="alert">
              <i class="bi-exclamation-octagon" style="font-size: 1.5rem; margin-right: 8px; vertical-align: bootom; padding-bootom: 0px; color: red;"></i>
               Problemas ao enviar o arquivo <strong>'.$strNome.'.'.$strExtensao.'</strong>. Extensão <strong>'.strtoupper($strExtensao).'</strong> não permitida!
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
              </button>
            </div>';
        } else {
          $allowedIMG = array('jpg','jepg','png','gif');
           if(in_array($strExtensao,$allowedIMG)) {
             $strIcon = 'file-image';
          }
          $strErro = $obUpload->error ?? '';



          //MOVE OS ARQUIVOS DE UPLOAD
          $sucesso = $obUpload->upload(__DIR__.'/../../../files/'.ROTA_SUBSESSAO.'/tmp',false);

          if($sucesso){

            self::redimensionaJPG(__DIR__.'/../../../files/'.ROTA_SUBSESSAO.'/tmp/'.$obUpload->name.'.'.$strExtensao, 1150, 600, __DIR__.'/../../../files/'.ROTA_SUBSESSAO.'/fotos/2021' . $strNome . '.jpg');
            self::redimensionaJPG(__DIR__.'/../../../files/'.ROTA_SUBSESSAO.'/tmp/'.$obUpload->name.'.'.$strExtensao, 800, 419, __DIR__.'/../../../files/'.ROTA_SUBSESSAO.'/banner/' . $strNome . '.jpg');
            self::redimensionaJPG(__DIR__.'/../../../files/'.ROTA_SUBSESSAO.'/tmp/'.$obUpload->name.'.'.$strExtensao, 450, 236, __DIR__.'/../../../files/'.ROTA_SUBSESSAO.'/principal/' . $strNome . '.jpg');
            self::redimensionaJPG(__DIR__.'/../../../files/'.ROTA_SUBSESSAO.'/tmp/'.$obUpload->name.'.'.$strExtensao, 350, 183, __DIR__.'/../../../files/'.ROTA_SUBSESSAO.'/capa/' . $strNome . '.jpg');


            //NOVA ISNTANCIA DE CHAMADO
            $obArquivo = new EntityArquivo;
            ////$obChamado::getChamadoPorEmail($posVars['email']);
            $obArquivo->arquivo_nm = $strNome;
            $obArquivo->arquivo_temp = $obUpload->getBasename();
            $obArquivo->arquivo_tam = $strSize;
            $obArquivo->arquivo_type = $strExtensao;
            $obArquivo->arquivo_icon = $strIcon;
            $obArquivo->id_usuario = $_SESSION['admin']['usuario']['usuario_id'];
            $obArquivo->id_subsessao = $_SESSION['admin']['usuario']['fileschamado'];
            $obArquivo->cadastrar();

            $strMsnUploadSucess = $strMsnUploadSucess.'
                   <div class="p-1 bg-light border" id="upload_arquivo_'.$obArquivo->arquivo_id.'">
                     <i class="bi-'.$strIcon.'" style="font-size: 1.5rem; margin: 8px; vertical-align: bootom; padding-bootom: 0px; color: cornflowerblue;"></i>
                     Arquivo <strong>'.$strNome.'.'.$strExtensao.'</strong> carregado com sucesso!
                     <a tabindex="0" id="'.$obArquivo->arquivo_id.'" onclick="file_remove('.$obArquivo->arquivo_id.');" class="btn btn-sm btn-danger float-sm-right role="button" title="Id='.$obArquivo->arquivo_id.'Clique para remover o arquivo '.$strNome.'.'.$strExtensao.' da sua lista de upload.">X</a>
                   </div>';

            continue;
          }else{
            $strMsnUploadError = $strMsnUploadError.'Problemas ao enviar o(s) arquivo(s) <strong>Erro: '.$strErro.'<br>';
          }
          echo $strMsnUploadError;
          exit;
        }
      }
      echo $strMsnUploadSucess.$strMsnUploadFail;
    }
  }

    /**
     * Método responsável por retornar o formulário de cadastro de um novo Chamado
     * @param Request $request
     * @return string
     */
     public static function getNovaSubsessao($request){

       $id = '';
       $status = '';
       $currentDepartamento = $_SESSION['admin']['usuario']['departamento'];
       $currentPerfil = $_SESSION['admin']['usuario']['id_perfil'];

       $sessaoSelecionada = AdminSessoes::getSessaoItensSelect($request,$id);

       //CONTEÚDO DA NOTÍCIA
       $content = View::render('admin/modules/'.DIR_SUBSESSAO.'/form',[
         'icon' => ICON_SUBSESSAO,
         'title' =>TITLE_SUBSESSAO,
         'titlelow' => TITLELOW_SUBSESSAO,
         'direntity' => ROTA_SUBSESSAO,
         'itens' => self::getSubsessaoItens($request,$obSubsessaotion),
         'subsessao_nm' => '',
         'subsessao_titulo' => '',
         'subsessao_conteudo' => '',
         'optionsSessoes' => $sessaoSelecionada,
         'descricao' => '',
         'id_usuario' => $_SESSION['admin']['usuario']['usuario_id'] ?? '',
         'status' => $status
       ]);

       //RETORNA A PÁGINA COMPLETA
       return parent::getPanel('Cadastrar Subsessão - EMERJ',$content,'subsessoes',$currentDepartamento,$currentPerfil);
     }

   /**
    * Método responsável por cadastro de uma nova Localização no banco
    * @param Request $request
    * @return string
    */
    public static function setNovaSubsessao($request){

      date_default_timezone_set('America/Sao_Paulo');

      if (isset($_FILES['subsessaoUpload'])) {

        //INSTÂNCIAS DO UPLOAD
        $uploads = Upload::createMultiUpload($_FILES['subsessaoUpload']);

        foreach ($uploads as $obUpload) {

          $strNome = $obUpload->name ?? '';
          $strNomeTmp = $obUpload->tmp_name ?? '';
          $strSize = $obUpload->size ?? '';
          $strExtensao = strtolower($obUpload->extension) ?? '';

          //GERA UM NOME ALEATÓRIO PARA O ARQUIVO
          $obUpload->generateNewName();

          //MOVE OS ARQUIVOS DE UPLOAD
          $sucesso = $obUpload->upload(__DIR__.'/../../../files/'.ROTA_SUBSESSAO.'/originais',false);
          $strNomeImg = $obUpload->getBasename();

        }
      }

      //DADOS DO POST
      $posVars = $request->getPostVars();
      $id_sessao = filter_input(INPUT_POST, 'sessao', FILTER_SANITIZE_NUMBER_INT);
      $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
      $subsessao_titulo = filter_input(INPUT_POST, 'subsessao_titulo', FILTER_SANITIZE_STRING);
      $subsessao_imgalt = filter_input(INPUT_POST, 'subsessao_imgalt', FILTER_SANITIZE_STRING);
      $subsessao_imgtittle = filter_input(INPUT_POST, 'subsessao_imgtittle', FILTER_SANITIZE_STRING);
      $conteudo = $posVars['subsessao_conteudo'];
      $id_usuario = filter_input(INPUT_POST, 'id_usuario', FILTER_SANITIZE_NUMBER_INT);

      //NOVA ISNTANCIA DE DEPARTAMENTO
      $obSubsessao = new EntitySubsessao;

      $obSubsessao->subsessao_nm = $nome;
      $obSubsessao->subsessao_titulo = $subsessao_titulo;
      $obSubsessao->subsessao_conteudo = html_entity_decode($conteudo);
      $obSubsessao->subsessao_dropdownlabel = $subsessao_titulo;
      $obSubsessao->id_sessao = $id_sessao;
      $obSubsessao->id_usuario = $id_usuario ?? $_SESSION['admin']['usuario']['usuario_id'];
      $obSubsessao->cadastrar();

      $idSubsessao = $obSubsessao->subsessao_id;

      if($sucesso){

        $dirOrigem = __DIR__.'/../../../files/'.ROTA_SUBSESSAO.'/originais';
        $dirImages = __DIR__.'/../../../files/'.ROTA_SUBSESSAO.'/images';

        $imageUploaded = $dirOrigem.'/'.$strNomeImg;

        self::redimensionaJPG($imageUploaded, 1150, 600, $dirImages.'/1150_'.$idSubsessao.'_'.$strNomeImg);
        self::redimensionaJPG($imageUploaded, 800, 419, $dirImages.'/800_'.$idSubsessao.'_'.$strNomeImg);
        self::redimensionaJPG($imageUploaded, 450, 236, $dirImages.'/450_'.$idSubsessao.'_'.$strNomeImg);
        self::redimensionaJPG($imageUploaded, 350, 183, $dirImages.'/350_'.$idSubsessao.'_'.$strNomeImg);
      }

      //REDIRECIONA O DEPARTAMENTO
      $request->getRouter()->redirect('/admin/subsessoes?status=gravado&nm='.$nome.'&acao=alter');
    }

    /**
     * Método responsável por gravar a edição de uma Localização
     * @param Request $request
     * @param integer $id
     * @return string
     */
     public static function getEditSubsessao($request,$id){

       $status = '';
       $currentDepartamento = $_SESSION['admin']['usuario']['departamento'];
       $currentPerfil = $_SESSION['admin']['usuario']['id_perfil'];

        $obSubsessao = EntitySubsessao::getSubsessaoPorId($id);

        $sessaoSelecionada = AdminSessoes::getSessaoItensSelect($request,$obSubsessao->id_sessao);

       //CONTEÚDO DA NOTÍCIA
       $content = View::render('admin/modules/'.DIR_SUBSESSAO.'/form',[
         'icon' => ICON_SUBSESSAO,
         'title' => 'Alterar '.TITLE_SUBSESSAO,
         'titlelow' => TITLELOW_SUBSESSAO,
         'direntity' => ROTA_SUBSESSAO,
         'itens' => self::getSubsessaoItens($request,$obSubsessaotion),
         'optionsSessoes' => $sessaoSelecionada,
         'subsessao_nm' => $obSubsessao->subsessao_nm ?? '',
         'subsessao_titulo' => $obSubsessao->subsessao_titulo ?? '',
         'subsessao_imgalt' => $obSubsessao->subsessao_imgalt ?? '',
         'subsessao_imgtittle' => $obSubsessao->subsessao_imgtittle ?? '',
         'subsessao_conteudo' => $obSubsessao->subsessao_conteudo ?? '',
         'status' => $status
       ]);

       return parent::getPanel('Alterar Subsessão - EMERJ',$content,'subsessoes',$currentDepartamento,$currentPerfil);


     }

   /**
    * Método responsável por gravar a edição de uma Localização
    * @param Request $request
    * @param integer $id
    * @return string
    */
    public static function setEditSubsessao($request,$id){

      //DADOS DO POST
      $posVars = $request->getPostVars();
      $id_sessao = filter_input(INPUT_POST, 'sessao', FILTER_SANITIZE_NUMBER_INT);
      $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
      $subsessao_titulo = filter_input(INPUT_POST, 'subsessao_titulo', FILTER_SANITIZE_STRING);
      $subsessao_imgalt = filter_input(INPUT_POST, 'subsessao_imgalt', FILTER_SANITIZE_STRING);
      $subsessao_imgtittle = filter_input(INPUT_POST, 'subsessao_imgtittle', FILTER_SANITIZE_STRING);
      $conteudo = $posVars['subsessao_conteudo'];
      $id_usuario = filter_input(INPUT_POST, 'id_usuario', FILTER_SANITIZE_NUMBER_INT);

      //OBTÉM O USUÁRIO DO BANCO DE DADOS
      $obSubsessao = EntitySubsessao::getSubsessaoPorId($id);

      if(!$obSubsessao instanceof EntitySubsessao){
        $request->getRouter()->redirect('/admin/subsessoes?status=updatefail');
      }

      //ATUALIZA A INSTANCIA
      $obSubsessao->subsessao_id = $id;
      $obSubsessao->subsessao_nm = $nome;
      $obSubsessao->subsessao_titulo = $subsessao_titulo;
      $obSubsessao->subsessao_conteudo = html_entity_decode($conteudo);
      $obSubsessao->subsessao_dropdownlabel = $subsessao_titulo;
      $obSubsessao->id_sessao = $id_sessao;
      $obSubsessao->atualizar();

      //REDIRECIONA O USUÁRIO PARA A SUBSESSAO INICIAL DE LISTAR CATEGORIAS DE IC's
      $request->getRouter()->redirect('/admin/subsessoes?status=alterado&nm='.$nome.'&acao=alter');
    }

    /**
     * Método responsável por retornar o formulário de alteração de status de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     */
     public static function getAltStatusSubsessao($request,$id){

       //OBTÉM A CATEGORIA DO IC PELO SEU ID DO BANCO DE DADOS
       $obSubsessao = EntitySubsessao::getSubsessaoPorId($id);
       $strNome = $obSubsessao->subsessao_nm;



       if(!$obSubsessao instanceof EntitySubsessao){
         $request->getRouter()->redirect('/admin/subsessoes?status=updatefail');
       }

       //OBTÉM O USUÁRIO DO BANCO DE DADOS
       if($obSubsessao->ativo_fl == 's'){
         $altStatus = 'n';
         $strMsn = ' DESATIVADO ';
       } elseif (($obSubsessao->ativo_fl == 'n') || ($obSubsessao->ativo_fl == '')) {
         $strMsn = ' ATIVADO ';
         $altStatus = 's';
       }

       //ATUALIZA A INSTANCIA (RESETA A SENHA DO USUÁRIO)
       $obSubsessao->ativo_fl = $altStatus;
       $obSubsessao->atualizar();

       //REDIRECIONA O USUÁRIO
       $request->getRouter()->redirect('/admin/subsessoes?status=statusupdate&nm='.$strNome.$strMsn);

     }

   /**
      * Método responsável por retornar o formulário de exclusão de um usuário atraves de um Modal
      * @param Request $request
      * @param integer $id
      * @return string
      */
      public static function getDeleteSubsessao($request,$id){

       //OBTÉM O LOCALIZAÇÃO DO BANCO DE DADOS
        $obSubsessao = EntitySubsessao::getSubsessaoPorId($id);
        $strNome = $obSubsessao->subsessao_nm;

        if(!$obSubsessao instanceof EntitySubsessao){
          $request->getRouter()->redirect('/admin/subsessoes');
        }

       //EXCLUI O USUÁRIO
        $obSubsessao->excluir();
        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/subsessoes?status=deletado&nm='.$strNome.$strMsn.'&acao=alter');
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
         return Alert::getSuccess('Dados d'.TITLE_SUBSESSAO.' <strong>'.$nm.'</strong> cadastrados com sucesso!');
         // code...
         break;
       case 'alterado':
         return Alert::getSuccess('Dados d'.TITLELOW_SUBSESSAO.' <strong>'.$nm.'</strong> alterados com sucesso!');
         // code...
         break;
       case 'deletado':
         return Alert::getSuccess('Registro d'.TITLE_SUBSESSAO.'  <strong>'.$nm.'</strong> deletado com sucesso!');
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
         return Alert::getError('Já existe '.TITLELOW_SUBSESSAO.' com este nome (<strong>'.$nm.'</strong>) para outr'.TITLELOW_SUBSESSAO.'!');
         // code...
         break;
       case 'statusupdate':
         return Alert::getSuccess('Status d'.TITLELOW_SUBSESSAO.' <strong>'.$nm.'</strong> com sucesso!');
         // code...
         break;
     }
   }
}
