<?php

namespace App\Controllers;

use App\Models\MachineModel;
use App\Traits\Validate;

class MachineController extends BaseController{

    // ATRIBUTOS
    private $machineID;
    private $machineTomb;
    private $machineComment;

    use Validate;

    // INJEÇÂO
    private $machineModel;

    // MÉTODOS
    public function __construct(){
        $this->machineModel = new MachineModel();
    }

    // Método para add máquina
    public function addMachine(){
        // Capturando os dados
        $machineTomb        = $this->request->getPost('machine-tomb') ?? null;
        $machineComment     = $this->request->getPost('comment') ?? null;

        // dd([$machineTomb, $machineComment]);

        // Como o comentário é opcional, verifico aepenas se o tombamento veio vazio
        $isMachineTombEmpty = $this->isSomeValueNull([$machineTomb]);

        if($isMachineTombEmpty){
            return redirect()->back()->with(
                'error_message', 'O tombamento da máquina não pode estar vazio!'
            );
        }

        // Removo os possiveis espaços
        $machineTomb = $this->removeSpaces($machineTomb);

        // Checo se o tombamento só possui números
        $thereAreOnlyDigits = $this->onlyDigits($machineTomb);

        if(!$thereAreOnlyDigits){
            return redirect()->back()->with(
                'error_message', 'O tombamento da máquina deve possuir apenas dígitos!'
            );
        }

        // O tombamento pode ter, no máximo, 20 caracteres
        if(strlen($machineTomb) > 20){
            return redirect()->back()->with(
                'error_message', 'Tombamento da máquina muito grande!'
            );
        }

        // Antes de prosseguir, checo se a máquina já existe no banco de dados
        $machineAlreadyExists = $this->machineModel->machineAlreadyExists($machineTomb);

        if($machineAlreadyExists){
            return redirect()->back()->with(
                'error_message', 'Já existe uma máquina com estre tombamento!'
            );
        }

        // Tamanho máximo do comentário é de 1024 caracteres
        if(strlen($machineComment) > 1024){
            return redirect()->back()->with(
                'error_message', 'Comentário muito grande!'
            );
        }
        // sanitizando
        $machineComment = htmlspecialchars($machineComment, ENT_QUOTES);

        $machineDataToInsertInToDB = [
            'tombamento' => $machineTomb,
            'comentario' => strlen($machineComment) == 0 ? NULL : $machineComment
        ];


        // dd($machineDataToInsertInToDB);

        if(!$this->machineModel->insert($machineDataToInsertInToDB)){
            return redirect()->back()->with(
                'error_message', 'Erro ao inserir máquina no banco de dados!'
            );
        }

        return redirect()->back()->with(
            'success_message', 'Máquina inserida com sucesso!'
        );
    }

    // Méotodo para visualizar todas as máquinas ativas
    public function listAllMachines(){
        $allMachines = $this->machineModel->getAllMachinesActives();

        return  view('includes/header') .
                view('includes/alerts') .
                view('includes/sidebar') .
                view('dashboard/machines/listAllMachines', ["machinesActives" => $allMachines]) .
                view('includes/footer');
    }

    // Método para editar informações de uma máquina
    public function editMachine(){

        $machineID      = $this->request->getPost('machine-id') ?? null;
        $machineTomb    = $this->request->getPost('machine-tomb') ?? null;
        $machineComment = $this->request->getPost('machine-comment') ?? null;

        // VALIDAÇÕES E SANITIZAÇÕES

        $someValueIsNull = $this->isSomeValueNull([
            $machineID,
            $machineTomb,
            // Como o comentário é opcional na criação de uma máquina, optei por não verificar se
            // é ou não uma variável com valor
            // $machineComment 
        ]);

        if($someValueIsNull){
            return redirect()->back()->with(
                'error_message', 'Um ou mais campos inválidos!'
            );
        }

        // ID
        // Como os ID's só podem serem numéricos, faço
        $thereAreOnlyDigits = $this->onlyDigits($machineID);

        if(!$thereAreOnlyDigits){
            return redirect()->back()->with(
                'error_message', 'ID inválido!'
            );
        }

        // TOMBAMENTO
        // Mesmo caso dos id's

        $thereAreOnlyDigits = $this->onlyDigits($machineTomb);
        // No banco de dados, defini que o tamanho máximo de um tombamento seria de 20 bytes
        if(!$thereAreOnlyDigits || (strlen($machineTomb) > 20)){
            return redirect()->back()->with(
                'error_message', 'Tombamento inválido!'
            );
        }

        $this->machineID        = $machineID;
        $this->machineTomb      = $machineTomb;
        $this->machineComment   = htmlspecialchars($machineComment, ENT_QUOTES, 'UTF-8'); // evitando que algum gaiato aplique xss
        

        // Verificando se existe realmente uma máquina com o id informado
        $machineExisInDataBase = $this->machineModel->machineAlreadyExistsByID($this->machineID);

        if(!$machineExisInDataBase){
            return redirect()->back()->with(
                'error_message', 'ID inválido!'
            );
        }

        // Atualização do registro no banco
        $machineData = [
            'id'            => $this->machineID,
            'tombamento'    => $this->machineTomb,
            'comentario'    => $this->machineComment
        ];

        $updatedSuccess = $this->machineModel->updateMachineData($machineData);

        if(!$updatedSuccess){
            return redirect()->back()->with(
                "error_message", "Falha ao atualizar máquina!"
            );
        }

        return redirect()->back()->with(
            "success_message", "Máquina atualizada com sucesso!"
        );
    }

    // Método para softdeletar uma máquina
    public function softDeleteMachine(){
        
        $machineID      = $this->request->getPost('machine-id-delete') ?? null;
        $machineTomb    = $this->request->getPost('machine-tomb-delete') ?? null;

        // dd([$machineID, $machineTomb]);

        // Agora é fazer aquelas verificações básicas e chatas pra caralho

        $data = [
            $machineTomb, $machineID
        ];

        // dd($data);

        $isMachineDataEmpty = $this->isSomeValueNull([$machineTomb, $machineID]);

        if($isMachineDataEmpty){
            return redirect()->back()->with(
                'error_message', 'Um ou mais campos inválidos!'
            );
        }

        // Como o tombamento e o id são númericos, verifico se alguém modificou
        // o front end para enviar algum XSS ou alguma sql injection

        $thereAreOnlyDigits = $this->onlyDigits([$machineID, $machineTomb]);

        if(!$thereAreOnlyDigits){
            return redirect()->back()->with(
                'error_message', 'O tombamento deve possuir apenas dígitos!'
            );
        }

        $this->machineID    = $machineID;
        $this->machineTomb  = $machineTomb;

        // PRA AMANHÃ: Verificar no banco se existe uma máquina com id e tombamentos iguais a esses
        // Ou seja: o front não foi adulterado, caso exista, faço um update da coluna 'soft_delete' para 1 no registro

        $machineData = $this->machineModel->getMachineData($this->machineID);

        if(!$machineData){
            return redirect()->back()->with(
                'error_message', 'Erro ao capturar informações da máquina!'
            );
        }

        // Verificando se as informações da máquina são legítimas
        // Parando pra pensar melhor, eu nem precisaria fazer essa verificação toda
        // if( ($machineData[0]['id'] !== $this->machineID) || ($machineData[0]['tombamento'] !== $this->machineTomb) ){
        //     return redirect()->back()->with(
        //         'error_message', 'Dados da máquina foram adulterados!'
        //     );
        // }

        // Fazendo o softdelte no banco
        $machineDataToSoftDelete = [
            'id'            => $this->machineID,
            'soft_delete'   => '1'
        ];

        $machineSoftDeleteSuccess = $this->machineModel->softDeleteMachine($machineDataToSoftDelete);

        if(!$machineSoftDeleteSuccess){
            return redirect()->back()->with(
                'error_message', 'Erro ao deletar máquina!'
            );
        }

        return redirect()->back()->with(
            'success_message', 'Máquina deletada com sucesso!'
        );

    }

}

