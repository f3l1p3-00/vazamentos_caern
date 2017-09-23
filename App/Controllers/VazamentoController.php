<?php
  namespace App\Controllers;
  use App\Models\LocalizacaoDAO;
  use App\Models\VazamentoDAO;
  use App\Models\Entidades\Usuario;
  use App\Models\Entidades\Localizacao;
  use App\Models\Entidades\Vazamento;


   class VazamentoController extends Controller{

        public function index(){
          
         
          $this->render('layouts/home');
        }

        //pegando os dados via post e mandando para class vazamentoDAO para cadstrar no banco
        public function cadastrar(){
            $localizacaoDAO = new LocalizacaoDAO();
            $vazamentoDAO= new VazamentoDAO();
            //setando as entidades necessarias
            $localizacao = new Localizacao();
            
            $log = $this->limita_caracteres($_POST['long'], 10, $quebra = true);
            $lat = $this->limita_caracteres($_POST['lat'], 9, $quebra = true);
          
           
            $localizacao->setLat($lat);
            $localizacao->setLog($log);
            $localizacao->setRua("Rua Fake");
            $localizacao->setCidade("Natal");
            $localizacao->setEstado("RN");

            //Salvando o ponto no banco
            $localizacaoDAO->Inserir($localizacao);
        
            //retornando o id do ponto cadastrado
            $idLocalizacao = $localizacaoDAO->retornaID( $log, $lat);
           

            if($idLocalizacao > 0 ){
                //setando vazamento
                $vazamento = new Vazamento();
                
                $vazamento->setDescricao($_POST['descricaoV']);
                $vazamento->setStatus(1);
                $vazamento->setDate('2017-09-20');
                $vazamento->setGravidade("Grave");
                $vazamento->setTempo(0);
                $vazamento->setFkPonto($idLocalizacao);
                $vazamento->setFkUsuario(3);
                //salvando objeto vazamento no banco
                $row = $vazamentoDAO->Inserir($vazamento);
               
                if($row > 0){
                    echo "vazamento cadastrado com sucesso!!";
                }else{
                    echo "Erro ao cadstrar o vazamento...";
                }
            }else{
                echo "Erro ao buscar o id..";
            }
            

           



        }

        public function limita_caracteres($texto, $limite, $quebra = true){
            $tamanho = strlen($texto);
            if($tamanho <= $limite){ //Verifica se o tamanho do texto é menor ou igual ao limite
               $novo_texto = $texto;
            }else{ // Se o tamanho do texto for maior que o limite
               if($quebra == true){ // Verifica a opção de quebrar o texto
                  $novo_texto = trim(substr($texto, 0, $limite));
               }else{ // Se não, corta $texto na última palavra antes do limite
                  $ultimo_espaco = strrpos(substr($texto, 0, $limite)); // Localiza o útlimo espaço antes de $limite
                  $novo_texto = trim(substr($texto, 0, $ultimo_espaco)); // Corta o $texto até a posição localizada
               }
            }
            return $novo_texto; // Retorna o valor formatado
         }
       
     }
