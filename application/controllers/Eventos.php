<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use chriskacerguis\RestServer\RestController;
class Eventos extends RestController {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Model_evento','Model_eventoMDL');
        $this->methods['index_get']['limit'] = 10;   // Configuração para os limites de requisições (por hora)
    }

    // Lista todos os eventos ou filtra por id
    public function index_get()
    {
        $retorno = null;
         // Recebe id passada pela url
         $evento =  (string) $this->uri->segment(2);

         if (!empty($evento)){   
            $retorno = $this->Model_eventoMDL->retorna_e($evento);     
         }else{
            $retorno = $this->Model_eventoMDL->retorna();   
         }
 
         // usando os devidos cabeçalhos
         if ($retorno) {
             $response['data'] = $retorno;
             $this->response($response,200);
         } else {
             $this->response([
                'status' => false,
                'msg' => 'Nenhum evento encontrado',
                'retorno'=>$evento
            ], 404 );
         }
    }

    // Insere novo envento 
    public function index_post()
    {
        // recupera os dados informado no formulário
        $dados_evento = $this->post();
        if(empty($dados_evento)){
            $response['message'] = array('mensagem'=>'Preencha os campos do formulário','retorno'=>'error');
        }else{
            $insert = $this->Model_eventoMDL->insere($dados_evento);
            $response['message'] = array('msg'=>$insert['message'],'retorno'=>'sucesso'); 
        }
        $this->response($response,200); 
    }
}