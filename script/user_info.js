//user view for his or her details
// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Set up the profile button click event
    const profileBtn = document.querySelector('.profile-btn');
    if (profileBtn) {
        profileBtn.addEventListener('click', openProfile);
    }
    
    // Close modal when clicking outside content
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('profileModal');
        if (event.target === modal) {
            closeProfile();
        }
    });
});

function openProfile() {
    const modal = document.getElementById('profileModal');
    if (modal) {
        modal.style.display = 'flex';
    }
}

function closeProfile() {
    const modal = document.getElementById('profileModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

function redirectToLogin() {
    // Redirect to login page - update with your actual login page URL
    window.location.href = '../user_action/login.php';
}

function logout() {
    fetch('../user_action/logout.php', { method: 'POST' })
        .then(() => window.location.href = '../user_action/login.php');
}

// function logout() {
//     // Perform logout - redirect to logout script
//     window.location.href = '../user_action/logout.php';
// }


