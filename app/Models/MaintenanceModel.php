<?php

namespace App\Models;

use CodeIgniter\Model;


class MaintenanceModel extends Model {

    protected $table            = 'manutencoes_tb';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
            'ultima_manutencao',
            'proxima_manutencao', 
            'fk_id_prestador_de_servico_responsavel', 
            'fk_id_maquina',
            'comentario',
            'manutencao_feita'
    ];
    // protected $useTimestamps   = true;


    // metodos porra

    public function getAllMaintenancesByUserID(string $userID){
        $usersMaintenances = $this->select('manutencoes_tb.id, ultima_manutencao, proxima_manutencao, manutencoes_tb.comentario, maquinas_tb.tombamento, maquinas_tb.soft_delete, fk_id_prestador_de_servico_responsavel, prestadores_de_servico_tb.nome')
            ->join('maquinas_tb', 'maquinas_tb.id = manutencoes_tb.fk_id_maquina')
            ->join('prestadores_de_servico_tb', 'prestadores_de_servico_tb.id = manutencoes_tb.fk_id_prestador_de_servico_responsavel')
            ->where('manutencao_feita', 0)
            ->where('maquinas_tb.soft_delete', 0)
            // ->where('fk_id_prestador_de_servico_responsavel', $userID)
            ->findAll();

        if(!$usersMaintenances){
            return false;
        }

        return $usersMaintenances;
    }

    // Método para finalizar uma manutenção
    // Atualiza a coluna 'manutencao_feita' para 1
    // E registra a manutenção na tabela historico_manutencoes
    public function finishMaintenance(array $machineData){
        // Ajuste técnico para usar uma outra tabela que não a 'manutencoes_tb'
        // $historyMaintenceTable = 'historico_manutencoes';

        if(!$this->update($machineData['id'], $machineData)){
            return false;
        }

        return true;

    }
}