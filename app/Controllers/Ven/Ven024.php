<?php

namespace App\Controllers\Ven;

use App\Controllers\BaseController;
use App\Models\Vendas\ParametroUploadsCsv_Model;
class Ven024 extends BaseController
{		
    private $campos_venda_lote = [
       	'DT_VENDA_CSV'=>'DATA PEDIDO FOI REALIZADO',
        'NOME_CLIENTE_CSV'=>'NOME CLIENTE',
        'GENERO_CSV'=>'GÊNERO',
        'DT_NASCIMENTO_CSV'=>'DATA DE NASCIMENTO',
        'CPF_CNPJ_CSV'=>'CPF',
        'RG_CSV'=>'RG',
        'ORGAO_EXPEDIDOR_CSV'=>'ÓRGÃO EXPEDIDOR',
        'NOME_MAE_CSV'=>'NOME COMPLETO DA MÃE',
        'CONTATO_PRINCIPAL_CSV'=>'CONTATO PRINCIPAL',
        'CONTATO_SECUNDARIO_CSV'=>'CONTATO SECUNDÁRIO',
        'EMAIL_CSV'=>'E-MAIL',
        'MATRICULA_VENDEDOR_CSV'=>'MATRICULA VENDEDOR',
        'BANDA_LARGA_VELOCIDADE_CSV'=>'BANDA LARGA - VELOCIDADE',
        'COMBO_CONTRATADO_CSV'=>'COMBO/OFERTA CONTRATADA',
        'PLANO_TV_CSV'=>'TV - PLANO TV',
        'FORMA_PAGAMENTO_CSV'=>'PAGAMENTO - FORMA DE PAGAMENTO',
        'VENCIMENTO_CSV'=>'PAGAMENTO - VENCIMENTO',
        'PAG_CONTA_ONLINE_CSV'=>'PAGAMENTO - CONTA ONLINE',
        'PAG_BANCO_CSV'=>'PAGAMENTO - BANCO',
        'PAG_AGENCIA_CSV'=>'PAGAMENTO - AGÊNCIA',
        'PAG_CONTA_CSV'=>'PAGAMENTO - CONTA',
        'PAG_AGENCIA_DIGITO_CSV'=>'PAGAMENTO - DIGITO',
        'PAG_OPERACAO_CSV'=>'PAGAMENTO - OPERAÇÃO',
        'OBS_VENDEDOR_CSV'=>'OBSERVAÇÃO VENDEDOR',
        'CEP_INSTALACAO_CSV'=>'INSTALAÇÃO - CEP',
        'LOGRADOURO_INSTALACAO_CSV'=>'INSTALAÇÃO - LOGRADOURO',
        'NUM_INSTALACAO_CSV'=>'INSTALAÇÃO - NÚMERO',
        'BAIRRO_INSTALACAO_CSV'=>'INSTALAÇÃO - BAIRRO',
        'CIDADE_INSTALACAO_CSV'=>'INSTALAÇÃO - CIDADE',
        'UF_INSTALACAO_CSV'=>'INSTALAÇÃO - ESTADO',
        'REF_INSTALACAO_CSV'=>'INSTALAÇÃO - REFERÊNCIA',
        'REFE_COMPLEMENTO1_TIPO_CSV'=>'INSTALAÇÃO - COMPLEMENTO 1 - TIPO',
        'REFE_COMPLEMENTO1_CSV'=>'INSTALAÇÃO - COMPLEMENTO 1',
        'REFE_COMPLEMENTO2_TIPO_CSV'=>'INSTALAÇÃO - COMPLEMENTO 2 - TIPO',
        'REFE_COMPLEMENTO2_CSV'=>'INSTALAÇÃO - COMPLEMENTO 2',
        'REFE_COMPLEMENTO3_TIPO_CSV'=>'INSTALAÇÃO - COMPLEMENTO 3 - TIPO',
        'REFE_COMPLEMENTO3_CSV'=>'INSTALAÇÃO - COMPLEMENTO 3'
    ];
private $campos_bov = [
						"NUMERO_PEDIDO" => "Num OS",
						"STATUS" => "Status BOV",
						"FLG_MIG_COBRE_VELOX" => "Migracao Velox",
						"FLG_MIG_COBRE_FIXO" => "Migracao Fixo",
						"DATA_STATUS" => "Data instalacao"
						];
    /*private $campos_bov = [ "NUMERO_PEDIDO"=>"NUMERO PEDIDO", 
                            "PRODUTO"=>"PRODUTO", 
                            "DATA_STATUS"=>"DATA STATUS", 
                            "STATUS"=>"STATUS", 
                            "FLG_VENDA_VALIDA"=>"FLG VENDA VALIDA",
							"FLG_MIG_COBRE_FIXO"=>"FLG MIG COBRE FIXO",
							"FLG_MIG_COBRE_VELOX"=>"FLG MIG COBRE VELOX",
							"PLANO"=>"PLANO",
							"ID_BUNDLE"=>"ID BUNDLE",
							"CPF_CNPJ"=>"CPF CNPJ",
							"SUBMOTIVO"=>"SUBMOTIVO",
                           ];*/
	
						   
    private $campos_mailing = [ //ao alterar, n esquece de alterar a lista no js
        'NOME'=>'NOME',
        'CPF'=>'CPF',
        'EMAIL'=>'EMAIL',
        'CONTATO1'=>'CONTATO 1',
        'CONTATO2'=>'CONTATO 2',
        'CONTATO3'=>'CONTATO 3',
        'CONTATO4'=>'CONTATO 4',
        'CEP'=>'CEP',
        'UF'=>'UF',
        'CIDADE'=>'CIDADE',
        'BAIRRO'=>'BAIRRO',
        'LOGRADOURO'=>'LOGRADOURO',
        'NUM_FACHADA'=>'NUM FACHADA'
    ];						   

    public function __construct()
    {
        $this->titulo = "PARAMETROS CSV";
		$this->ParamsModel = new ParametroUploadsCsv_Model();
		$this->params = $this->ParamsModel->findAll();
    }

    public function index()
    {		
		if( count($this->params) == 0 ){
			$this->cria();
		}else{
			if($this->params[0]['venda_lote'] == ""){
				$this->atualiza(['venda_lote'=>json_encode($this->campos_venda_lote)]);
			}if($this->params[0]['bov'] == ""){
				$this->atualiza(['bov'=>json_encode($this->campos_bov)]);
			}if($this->params[0]['mailing'] == ""){
				$this->atualiza(['mailing'=>json_encode($this->campos_mailing)]);
			}
		}
		
		$parametros_tb = $this->ParamsModel->findAll()[0];        
		
        $data = [           
            'campos_venda_lote' =>  json_decode($parametros_tb['venda_lote']),
            'campos_bov'        =>  json_decode($parametros_tb['bov']),
			'campos_mailing'    =>  json_decode($parametros_tb['mailing']),
        ];		
        $this->load_template($data);
    }
	public function salva_ordem_cabecalhos(){
		$dPost = $this->request->getPost();		
		$this->atualiza([$dPost['campo']=>$dPost['ordem']]);
		echo json_encode(["retorno"=>"ok"]);
	}
	public function cria(){
		$this->ParamsModel->insert([
									"venda_lote"=> json_encode($this->campos_venda_lote),
									"bov"       => json_encode($this->campos_bov),
									"mailing"   => json_encode($this->campos_mailing),
								 ]);		
	}
	public function atualiza($campo){
		$this->ParamsModel->set($campo)->update();		
	}	
}