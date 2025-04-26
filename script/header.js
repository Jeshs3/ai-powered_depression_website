function toggleLogout() {
    const form = document.querySelector('.logout-form');
    form.style.display = form.style.display === 'block' ? 'none' : 'block';
}

// Keep form visible while hovering
document.querySelector('.floating-logout').addEventListener('mouseleave', () => {
    document.querySelector('.logout-form').style.display = 'none';
});

function toggleDropdown() {
    document.getElementById("dropdownContent").classList.toggle("show");
}

// Close dropdown when clicking outside
window.onclick = function(event) {
    if (!event.target.matches('.profile-btn')) {
        const dropdowns = document.getElementsByClassName("dropdown-content");
        for (let i = 0; i < dropdowns.length; i++) {
            const openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

// Admin switch functionality
document.getElementById('adminSwitch').addEventListener('click', function() {
    this.classList.toggle('active');
    const isAdmin = this.classList.contains('active');
    document.getElementById('adminLink').style.display = isAdmin ? 'block' : 'none';
    this.innerHTML = isAdmin ? 
        '<i class="fas fa-user"></i>' : 
        '<i class="fas fa-user-shield"></i>';
    
});