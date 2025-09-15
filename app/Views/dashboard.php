<div class="main my-3">
    <div class="text-center">
        <h1>Weeping Angel - Protótipo 0.1</h1>
        <h2>Dashboard</h2>
    </div>
    <aside>
        <div class="container-fluid">
            <div class="row">
                <h4 class="col-8">
                    Suas manutenções preventivas
                </h4>
            </div>
            <p class="" for="maintenance-total">
                Total de <?= count($manutencoesDoUsuario); ?> itens
            </p>
            <!-- Cards com as manutenções que o caba é responsável -->
            <?php foreach($manutencoesDoUsuario as $manutencao): ?>
            <div class="mb-3">
                <div class="card" style="width: auto;">
                    <div class="card-body">
                        <h3 class="card-title">Tombamento: <?= $manutencao['tombamento'] ?></h3><hr>
                        <h4 class="card-text">Data da última manutenção: <strong><?= $manutencao['ultima_manutencao']; ?></strong></h4>
                        <h4 class="card-text">Data prevista para a próxima manutenção: <strong><?= $manutencao['proxima_manutencao']; ?></strong></h4>
                        <h5 class="card-text">
                            <div class="mb-2">
                                <strong>Comentário do usuário:</strong>    
                            </div>
                            <div class="">
                                <p>
                                    <?php
                                        if($manutencao['comentario'] == null) {
                                            echo '[O usuário não adicionou nenhum comentário]';
                                        }
                                        echo $manutencao['comentario'];
                                    ?>
                                </p>
                            </div>
                        </h5>
                        <form action="<?= site_url('machine/maintenance/finish-maintenance'); ?>" method="POST">
                            <input type="hidden" name="id-maintenance" value="<?= $manutencao['id']; ?>">
                            <input type="hidden" name="date-maintenance" value="<?= $manutencao['ultima_manutencao']; ?>">
                            <button type="submit" class="btn btn-success">Marcar como concluído</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <!-- Fim do container -->
    </aside>
</div>
