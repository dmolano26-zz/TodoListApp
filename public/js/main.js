const personas = document.getElementById('personas');

if (personas) {
    personas.addEventListener('click', (e) => {
        if (e.target.className === 'btn btn-danger delete-persona') {
            if (confirm('¿Está seguro?')) {
                const id = e.target.getAttribute('data-id');
                fetch(`/persona/delete/${id}`, {
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
        }
    });
}