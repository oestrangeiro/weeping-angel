<div class="main p-3">
    <div class="text-center">
        <h1>Weeping Angel - Protótipo 0.1</h1>
        <h2 class="mb-3">Usuários</h2>
    </div>
    <div class="">
        <table class="table text-center table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Cargo</th>
                    <th scope="col">Email</th>
                    <th scope="col">CPF</th>
                    <th scope="col">Criado em</th>
                    <th scope="col">Editado em</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($usersActives as $user): ?>
                <tr>
                    <td scope="col"><?= $user['id']; ?></td>
                    <td scope="col"><?= $user['nome']; ?></td>
                    <td scope="col"><?= $user['cargo']; ?></td>
                    <td scope="col"><?= $user['email']; ?></td>
                    <td scope="col"><?= $user['cpf']; ?></td>
                    <td scope="col"><?= $user['created_at']; ?></td>
                    <td scope="col"><?= $user['updated_at']; ?></td>
                    <td class="">
                        <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#edit-user-modal"
                            data-id="<?= $user['id'] ?>"
                            data-name="<?= $user['nome'] ?>"
                            data-role="<?= $user['cargo'] ?>"
                            data-email="<?= $user['email'] ?>"
                            data-cpf="<?= $user['cpf'] ?>"
                        >Editar</button>
                        <button class="btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#softdelete-user-modal"
                            data-id="<?= $user['id'] ?>"
                            data-name="<?= $user['nome'] ?>"
                            data-cpf="<?= $user['cpf'] ?>"
                        >Remover</button>
                    </td>
                    <!-- <div>
                        <td>
                            <button class="btn btn-alert">Editar</button>
                            <button class="btn btn-alert">Remover</button>
                        </td>
                    </div> -->
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para editar a porra do usuário -->
<div class="modal fade" id="edit-user-modal" tabindex="-1" aria-labelledby="edit-user-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="edit-user-modal-label">Editar informações do usuário</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <form action="<?= site_url('admin/edit-user') ?>" method="POST">
                <input type="hidden" id="edit-user-id" name="user-id">
                <div class="modal-body">
                    <!-- Nome -->
                    <div class="mb-3">
                        <label for="user-name" class="form-label">Nome</label>
                        <input type="text" id="edit-user-name" name="user-name" class="form-control">
                    </div>
                    <!-- cargo -->
                    <div class="mb-3">
                        <label for="user-role" class="form-label">Cargo</label>
                        <select class="form-select" id="edit-user-role" name="user-role">
                            <option value="usuario">Usuário</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <!-- email -->
                    <div class="mb-3">
                        <label for="user-email" class="form-label">Email</label>
                        <input type="email" id="edit-user-email" name="user-email" class="form-control">
                    </div>
                    <!-- cpf -->
                     <div class="mb-3">
                        <label for="user-cpf" class="form-label">CPF</label>
                        <input type="text" id="edit-user-cpf" name="user-cpf" class="form-control">
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

<!-- Modal para editar a porra do usuário -->
<div class="modal fade" id="softdelete-user-modal" tabindex="-1" aria-labelledby="softdelete-user-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="softdelete-user-modal">Deletar usuário</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <form action="<?= site_url('admin/delete-user') ?>" method="POST">
                <input type="hidden" id="softdelete-user-id" name="user-id">
                <div class="modal-body">
                    <!-- Nome -->
                    <div class="mb-3">
                        <label for="user-name" class="form-label">Nome</label>
                        <input type="text" id="softdelete-user-name" name="user-name" class="form-control">
                    </div>
                    <!-- cpf -->
                     <div class="mb-3">
                        <label for="user-cpf" class="form-label">CPF</label>
                        <input type="text" id="softdelete-user-cpf" name="user-cpf" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Deletar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // console.log("script rodando...");
    const editUserModal = document.getElementById('edit-user-modal');
    editUserModal.addEventListener('show.bs.modal', function (event) {

        const button    = event.relatedTarget;

        const id        = button.getAttribute('data-id');
        const name      = button.getAttribute('data-name');
        const role      = button.getAttribute('data-role');
        const email     = button.getAttribute('data-email');
        const cpf       = button.getAttribute('data-cpf');

        document.getElementById('edit-user-id').value    = id;
        document.getElementById('edit-user-name').value  = name;
        document.getElementById('edit-user-role').value  = role;
        document.getElementById('edit-user-email').value = email;
        document.getElementById('edit-user-cpf').value   = cpf;

    });

    const softDeleteUserModal = document.getElementById('softdelete-user-modal');
    softDeleteUserModal.addEventListener('show.bs.modal', function (event) {
        
        const button    = event.relatedTarget;

        const id        = button.getAttribute('data-id');
        const name      = button.getAttribute('data-name');
        const cpf       = button.getAttribute('data-cpf');

        document.getElementById('softdelete-user-id').value    = id;
        document.getElementById('softdelete-user-name').value  = name;
        document.getElementById('softdelete-user-cpf').value   = cpf;

    });

</script>