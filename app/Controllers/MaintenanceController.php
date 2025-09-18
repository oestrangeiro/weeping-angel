<?php

namespace App\Controllers;

// models
use App\Models\UsersModel;
use App\Models\MachineModel;
use App\Models\MaintenanceModel;

// controllers
// use App\Controllers\MachineController;

// Trait de validação
use App\Traits\Validate;
use DateTime;

class MaintenanceController extends BaseController {

    use Validate;

    // ATRIBUTOS
    private $namePersonService;
    private $dateMaintenance;
    private $comment;

    const MINIMAL_MONTHS_TO_NEXT_MAINTENANCE = 3;

    // INJEÇÂO
    private $userModel;
    private $machineModel;
    private $maintenanceModel;

    public function __construct(){
        $this->userModel        = new UsersModel();
        $this->machineModel     = new MachineModel();
        $this->maintenanceModel = new MaintenanceModel();
    }

    // Método para criar uma manutenção
    public function createMaintenance(){
        // Pegando os dados via post
        $idPersonService = $this->request->getPost('id-person-service')     ?? null;
        $dateMaintenance = $this->request->getPost('date-maintenance')      ?? null;
        $idMachine       = $this->request->getPost('id-machine')            ?? null;
        $comment         = $this->request->getPost('comment')               ?? null;

        // dd([$idMachine, $dateMaintenance, $idPersonService, $comment]);

        // Validações dos dados do front

        // Checando se algum dado veio vazio
        // Como o comentário é opcional, não preciso checar se ele veio vazio ou não
        $isThereSomeValueEmpty = $this->isSomeValueNull(
            [
                $idPersonService,
                $dateMaintenance,
                $idMachine
            ]
        );

        if($isThereSomeValueEmpty){
            return redirect()->back()->with(
                'error_message', 'Um ou mais campos vazios!'
            );
        }

        // ID PESSOA PRESTADORA DE SERVICO
        // Checando se existe alguém com o id no banco de dados
        $idPersonExists = $this->userModel->checksIfUserAlreadyExistsById($idPersonService);

        if(!$idPersonExists){
            return redirect()->back()->with(
                'error_message', 'Um ou mais dados inválidos!'
            );
        }

        // ID MAQUINA
        // Checando se a máquina existe
        $idMachineExists = $this->machineModel->machineAlreadyExistsByID($idMachine);

        if(!$idMachineExists){
            return redirect()->back()->with(
                'error_message', 'Um ou mais dados inválidos!'
            );
        }

        // DATA DA MANUTENÇÂO
        // Verifico se o sujeito não está tentando colocar uma data no futuro
        // (TODO: FAZER ESSE TRATAMENTO NO FRONT END TAMBEM)

        $currentDateObj     = new DateTime();
        // setTime(0, 0, 0) Força que a comparação das datas
        // seja feita com base no DIA e não nas HORAS
        // Isso evita que, se o usuário marcar a manutenção como
        // tendo sido feita AMANHA, o código não acuse erro
        $currentDateObj->setTime(0, 0, 0);

        $dateMaintenanceObj = new DateTime($dateMaintenance);
        $dateMaintenanceObj->setTime(0, 0, 0);

        $interval = $currentDateObj->diff($dateMaintenanceObj);

        // --------
        // DEBUG
        // --------
        // echo "Data manutenção: {$dateMaintenanceObj->format('Y-m-d')}<br>";
        // echo "Data de hoje: {$currentDateObj->format('Y-m-d')}<br>";
        // echo $interval->days . " dias";

        // Caso o cara tenha marcado para o dia de hoje
        if($interval->days !== 0){
            // Se o atributo invert for 0: a data está no futuro
            // se for 1, está no passado
            if($interval->invert == 0){
                return redirect()->back()->with(
                    'error_message', 'Data inválida!'
                );
            }
        }

        $lastMaintenanceDate = $dateMaintenanceObj->format('Y-m-d');
        $nextMaintenanceDate = $dateMaintenanceObj->modify('+' . $this::MINIMAL_MONTHS_TO_NEXT_MAINTENANCE . ' month');
        $nextMaintenanceDateFormated = $nextMaintenanceDate->format('Y-m-d');

        // Agora é só inserir no banco
        $maintenanceDataToInsertoIntoDB = [
            'ultima_manutencao'                      => $lastMaintenanceDate,
            'proxima_manutencao'                     => $nextMaintenanceDateFormated, 
            'fk_id_prestador_de_servico_responsavel' => $idPersonService, 
            'fk_id_maquina'                          => $idMachine,
            'comentario'                             => $comment ? $comment : null,
        ];

        if($this->maintenanceModel->insert($maintenanceDataToInsertoIntoDB)){
            return redirect()->back()->with(
                'success_message',  'Manutenção adicionada com sucesso!'
            );
        }

        return redirect()->back()->with(
            'error_message',  'Falha ao tentar registrar manutenção!'
        );
        
    }

    public function finishMaintenance(){

        $idMaintenance   = $this->request->getPost('id-maintenance');
        $dateMaintenance = $this->request->getPost('date-maintenance');

        // echo "id da máquina para a manutenção ser fechada: {$idMaintenance}\n";
        $idHasOnlyDigits = $this->onlyDigits($idMaintenance);

        if(!$idHasOnlyDigits){
            return redirect()->back()->with(
                'error_message',  'ID inválido!'
            );
        }

        $currentDateObj     = new DateTime();
        // setTime(0, 0, 0) Força que a comparação das datas
        // seja feita com base no DIA e não nas HORAS
        // Isso evita que, se o usuário marcar a manutenção como
        // tendo sido feita AMANHA, o código não acuse erro
        $currentDateObj->setTime(0, 0, 0);

        $dateMaintenanceObj = new DateTime($dateMaintenance);
        $dateMaintenanceObj->setTime(0, 0, 0);

        $interval = $currentDateObj->diff($dateMaintenanceObj);

        // --------
        // DEBUG
        // --------
        // echo "Data manutenção: {$dateMaintenanceObj->format('Y-m-d')}<br>";
        // echo "Data de hoje: {$currentDateObj->format('Y-m-d')}<br>";
        // echo $interval->days . " dias";
        

        // Caso o cara tenha marcado para o dia de hoje
        if($interval->days !== 0){
            // Se o atributo invert for 0: a data está no futuro
            // se for 1, está no passado
            if($interval->invert == 0){
                return redirect()->back()->with(
                    'error_message', 'Data inválida!'
                );
            }
        }

        // Ultima manutenção feita vai ser a data de hj
        // quando o usuário clica no botão para marcar a manutenção como feita
        $lastMaintenanceDate = $currentDateObj->format('Y-m-d');

        // A proxima manutençao vai ser a data de hj + 3 meses
        $nextMaintenanceDate = $currentDateObj->modify('+' . $this::MINIMAL_MONTHS_TO_NEXT_MAINTENANCE . ' month');
        $nextMaintenanceDateFormated = $nextMaintenanceDate->format('Y-m-d');
        
        $idLastResponsableByMaintenance = $this->userModel->getUserIDByCPF($_SESSION['cpf']);
        
        $maintenanceDataToUpdateIntoDB = [
            'id'                                       => $idMaintenance,
            'fk_id_prestador_de_servico_responsavel'   => $idLastResponsableByMaintenance,
            'ultima_manutencao'                        => $lastMaintenanceDate,
            'proxima_manutencao'                       => $nextMaintenanceDateFormated
        ];
 

        // dd($maintenanceDataToUpdateIntoDB);

        $maintenanceFinishedSuccessfully = $this->maintenanceModel->finishMaintenance($maintenanceDataToUpdateIntoDB);

        if(!$maintenanceFinishedSuccessfully){
            return redirect()->back()->with(
                'error_message',  'Erro ao concluir manutenção!'
            );
        }

        return redirect()->back()->with(
            'success_message',  'Manutenção concluída com sucesso!'
        );
    }
}