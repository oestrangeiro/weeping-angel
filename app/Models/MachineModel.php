<?php

namespace App\Models;

use CodeIgniter\Model;


class MachineModel extends Model {
    protected $table            = 'maquinas_tb';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['tombamento', 'comentario', 'soft_delete', 'created_at', 'updated_at'];
    protected $useTimestamps    = true;


    // Método que informa se uma máquina já existe no banco de dados
    public function machineAlreadyExists(string $machineTomb): bool {
        $machine = $this->select('id')->where('tombamento', $machineTomb)->find();

        // Se existe, eu retorno true
        if($machine){
            return true;
        }
        return false;
    }

    // Outro método que verifica se uma máquina existe, mas pelo id
    public function machineAlreadyExistsByID(string $machineID): bool{
        $machine = $this->select('id')->where('id', $machineID)->find();

        if($machine){
            return true;
        }

        return false;
    }

    // Método que busca máquinas 'soft_deletadas'
    public function getMachinesDeleted(){
        $machinesDeleted = $this->select('*')->where('soft_delete', '1')->findAll();

        return $machinesDeleted;
    }

    // Método para listar todas as máquinas ativas no banco
     public function getAllMachinesActives(){
        $machines = $this->select('id, tombamento, comentario, created_at, updated_at')->where('soft_delete', 0)->findAll();

        return $machines;
    }

    // Método para fazer atualização de uma máquina no banco de dados
    public function updateMachineData(array $machineData): bool{

        if($this->update($machineData['id'], $machineData)){
            return true;
        }
        return false;
    }

    // Método que pega dados da máquina com base no id
    public function getMachineData(string $machineID){
        $machineData = $this->select('id, tombamento')->where('id', $machineID)->find();

        if($machineData){
            return $machineData;
        }

        return false;
    }

    // Método que softdeleta uma máquina
    public function softDeleteMachine(array $machineData){

        if($this->update($machineData['id'], $machineData)){
            return true;
        }

        return false;
    }
}