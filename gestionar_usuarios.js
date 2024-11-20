$(document).ready(function () {
    loadUsers();

    function loadUsers() {
        $.ajax({
            url: 'gestionar_usuarios.php',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.users) {
                    const usersTable = $('#usersTable tbody');
                    usersTable.empty();

                    response.users.forEach(user => {
                        const row = `
                            <tr>
                                <td>${user.id}</td>
                                <td>${user.name}</td>
                                <td>${user.email}</td>
                                <td>
                                    <button class="editBtn" data-id="${user.id}" data-name="${user.name}" data-email="${user.email}">Editar</button>
                                    <button class="deleteBtn" data-id="${user.id}">Eliminar</button>
                                </td>
                            </tr>
                        `;
                        usersTable.append(row);
                    });
                }
            },
            error: function (xhr) {
                console.error("Error al cargar usuarios:", xhr.responseText);
                alert("Error al cargar los usuarios.");
            }
        });
    }

    // Registrar usuario
    $('#addUserBtn').click(function () {
        const name = $('#name').val().trim();
        const email = $('#email').val().trim();
        const password = $('#password').val();

        if (!name || !email || !password) {
            alert("Todos los campos son obligatorios.");
            return;
        }

        $.ajax({
            url: 'gestionar_usuarios.php',
            method: 'POST',
            data: {
                register_user: true,
                name: name,
                email: email,
                password: password
            },
            dataType: 'json',
            success: function (response) {
                alert(response.message);
                if (response.success) {
                    loadUsers();
                    $('#addUserForm')[0].reset();
                    $('#addUserModal').hide();
                }
            },
            error: function (xhr) {
                console.error("Error al registrar usuario:", xhr.responseText);
                alert("Error al registrar el usuario.");
            }
        });
    });

    // Mostrar el modal de registro
    $('#openAddUserModalBtn').click(function () {
        $('#addUserModal').show();
    });

    // Eliminar usuario
    $(document).on('click', '.deleteBtn', function () {
        const userId = $(this).data('id');
        if (confirm("¿Estás seguro de eliminar este usuario?")) {
            $.ajax({
                url: 'gestionar_usuarios.php',
                method: 'POST',
                data: { delete_user_id: userId },
                dataType: 'json',
                success: function (response) {
                    alert(response.message);
                    if (response.success) {
                        loadUsers();
                    }
                },
                error: function (xhr) {
                    console.error("Error al eliminar usuario:", xhr.responseText);
                    alert("Error al eliminar el usuario.");
                }
            });
        }
    });

    // Mostrar modal de edición
    $(document).on('click', '.editBtn', function () {
        const userId = $(this).data('id');
        const userName = $(this).data('name');
        const userEmail = $(this).data('email');

        $('#editUserId').val(userId);
        $('#editName').val(userName);
        $('#editEmail').val(userEmail);
        $('#editUserModal').show();
    });

    // Actualizar usuario
    $('#updateUserBtn').click(function () {
        const userId = $('#editUserId').val();
        const name = $('#editName').val();
        const email = $('#editEmail').val();

        $.ajax({
            url: 'gestionar_usuarios.php',
            method: 'POST',
            data: {
                update_user_id: userId,
                name: name,
                email: email
            },
            dataType: 'json',
            success: function (response) {
                alert(response.message);
                if (response.success) {
                    loadUsers();
                    $('#editUserModal').hide();
                }
            },
            error: function (xhr) {
                console.error("Error al actualizar usuario:", xhr.responseText);
                alert("Error al actualizar el usuario.");
            }
        });
    });
});
