<div class="main p-3">
    <div class="text-center">
            <h1>Weeping Angel - Protótipo 0.1</h1>
            <h2>Registro de manutenções</h2>
        </div>
        <!-- Formulario -->
        <div class="container">
            <div class="col-9">
                <form class="form-control" action="<?= site_url('/machine/maintenance'); ?>" method="POST">
                    <div class="row mb-3">
                        <!-- Nome do prestador responsável -->
                        <label class="label p-2" for="label-person-service">
                            Nome do prestador de serviço
                        </label>
                        <select class="form-select" name="id-person-service" id="id-person-service">
                            <?php foreach($prestadoresDeServico as $prestador): ?>
                                <option value="<?= $prestador['id'];?>"><?= $prestador['nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <!-- Data da manutenção -->
                        <label class="label p-2" for="label-maintenance-date">
                            Data em que foi realizada a manutenção
                        </label>
                        <input class="form-control" type="date" name="date-maintenance">
                        <!-- Máquina -->
                         <label class="label p-2" for="label-machine">
                            Máquina em que foi realizada a manutenção
                        </label>
                        <select class="form-select" name="id-machine" id="select-machine">
                            <?php foreach($maquinasAtivas as $maquina): ?>
                                <option value="<?= $maquina['id'];?>"><?= $maquina['tombamento'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <!-- Comentário opcional -->
                        <label class="label p-2" for="label-comment">
                            Comentário (Opcional)
                        </label>
                        <!-- Comentario -->
                        <textarea class="form-control" name="comment" id="comment"></textarea>
                        <div class="mt-1 p-3">
                            <div class="d-grid gap-2">
                                <button class="btn btn-success">Registrar manutenção</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Fim do formulario -->
    </div>
</div>