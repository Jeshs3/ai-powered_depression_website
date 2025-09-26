document.addEventListener("DOMContentLoaded", () => {

    // 🔹 Edit button
    document.querySelectorAll(".edit-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            const userId = btn.dataset.id;

            Swal.fire({
                title: 'Edit User?',
                text: `Are you sure you want to edit User ID ${userId}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, edit',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa'
            }).then(result => {
                if (result.isConfirmed) {
                    // Redirect to edit page
                    window.location.href = `edit_user.php?id=${userId}`;
                }
            });
        });
    });

    // 🔹 Delete button
    document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            const userId = btn.dataset.id;

            Swal.fire({
                title: 'Delete User?',
                text: `Are you sure you want to delete User ID ${userId}? This cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#aaa'
            }).then(result => {
                if (result.isConfirmed) {
                    // Send AJAX request instead of redirect
                    fetch(`../database/action.php?action=delete_user&id=${userId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Deleted!', data.message, 'success')
                                .then(() => location.reload()); // Refresh table
                        } else {
                            Swal.fire('Error occurred!');
                        }
                    }).catch(err => {
                        Swal.fire('Error!', 'Failed to connect to server.', 'error');
                    });
                }
            });
        });
    });
});
