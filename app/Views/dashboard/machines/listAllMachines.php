<div class="main p-3">
    <div class="text-center">
        <h1>Weeping Angel - Protótipo 0.1</h1>
        <h2 class="mb-3">Máquinas</h2>
    </div>
    <table class="table text-center table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Tombamento</th>
                <th scope="col">Comentário</th>
                <th scope="col">Criado em</th>
                <th scope="col">Editado em</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($machinesActives as $machine): ?>
            <tr>
                <td scope="col"><?= $machine['id']; ?></td>
                <td scope="col"><?= $machine['tombamento']; ?></td>
                <td scope="col"><?= $machine['comentario']; ?></td>
                <td scope="col"><?= $machine['created_at']; ?></td>
                <td scope="col"><?= $machine['updated_at']; ?></td>
                <td class="">
                    <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#edit-machine-modal"
                        data-id="<?= $machine['id'] ?>"
                        data-tomb="<?= $machine['tombamento'] ?>"
                        data-comment="<?= $machine['comentario'] ?>"
                    >Editar</button>
                    <button class="btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#softdelete-machine-modal"
                        data-id="<?= $machine['id'] ?>"
                        data-tomb="<?= $machine['tombamento'] ?>"
                    >Remover</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal para editar a porra da máquina -->
<div class="modal fade" id="edit-machine-modal" tabindex="-1" aria-labelledby="edit-machine-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="edit-machine-modal-label">Editar informações da máquina</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <form action="<?= site_url('admin/edit-machine') ?>" method="POST">
                <input type="hidden" id="machine-id" name="machine-id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="machine-tomb" class="form-label">Tombamento</label>
                        <input type="text" id="machine-tomb" name="machine-tomb" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="machine-comment" class="form-label">Comentário</label>
                        <input type="text" id="machine-comment" name="machine-comment" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Editar</button>
                    <button type="reset" class="btn btn-secondary">Limpar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fim do modal para editar a porra da máquina -->
<!-- modal para apagar uma máquina -->
 <div class="modal fade" id="softdelete-machine-modal" tabindex="-1" aria-labelledby="softdelete-machine-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="softdelete-machine-modal-label">Deseja apagar a máquina?</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <form action="<?= site_url('admin/delete-machine') ?>" method="POST">
                <input type="hidden" id="machine-id-delete" name="machine-id-delete">
                <input type="hidden" id="machine-tomb-delete" name="machine-tomb-delete">
                <div class="modal-body">
                    <div class="mb-3">
                        <p>Você está prestes a remover a máquina com tombamento: <strong id="tombamento-exibido"></strong>.</p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Remover</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // console.log("script rodando...");
    const editMachineModal = document.getElementById('edit-machine-modal');
    editMachineModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const tomb = button.getAttribute('data-tomb');
        const comment = button.getAttribute('data-comment');

        document.getElementById('machine-id').value = id;
        document.getElementById('machine-tomb').value = tomb;
        document.getElementById('machine-comment').value = comment;
    });

    const deleteMachineModal = document.getElementById('softdelete-machine-modal');
    deleteMachineModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const tomb = button.getAttribute('data-tomb');

        document.getElementById('machine-id-delete').value = id;
        document.getElementById('machine-tomb-delete').value = tomb;

        document.getElementById('tombamento-exibido').textContent = tomb;
    });

</script>
