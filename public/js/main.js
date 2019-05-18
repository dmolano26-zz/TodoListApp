const personas = document.getElementById('personas');

if (personas) {
    personas.addEventListener('click', (e) => {
        if (e.target.className === 'btn btn-danger delete-persona') {
            if (confirm('¿Está seguro?')) {
                const id = e.target.getAttribute('data-id');
                fetch(`/persona/delete_persona/${id}`, {
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
        }
    });
}

const usuarios = document.getElementById('usuarios');

if (usuarios) {
    usuarios.addEventListener('click', (e) => {
        if (e.target.className === 'btn btn-danger delete-usuario') {
            if (confirm('¿Está seguro?')) {
                const id = e.target.getAttribute('data-id');
                fetch(`/usuario/delete_usuario/${id}`, {
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
        }
    });
}

const categorias = document.getElementById('categorias');

if (categorias) {
    categorias.addEventListener('click', (e) => {
        if (e.target.className === 'btn btn-danger delete-categoria') {
            if (confirm('¿Está seguro?')) {
                const id = e.target.getAttribute('data-id');
                fetch(`/categoria/delete_categoria/${id}`, {
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
        }
    });
}

const actividades = document.getElementById('actividades');

if (actividades) {
    actividades.addEventListener('click', (e) => {
        if (e.target.className === 'btn btn-danger delete-actividad') {
            if (confirm('¿Está seguro?')) {
                const id = e.target.getAttribute('data-id');
                fetch(`/actividad/delete_actividad/${id}`, {
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
        }
    });
}