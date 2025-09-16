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
                    <td scope="col"><?= $user['created_at']; ?></td>
                    <td scope="col"><?= $user['updated_at']; ?></td>
                    <div>
                        <td>
                            <button class="btn btn-alert">Editar</button>
                            <button class="btn btn-alert">Remover</button>
                        </td>
                    </div>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>