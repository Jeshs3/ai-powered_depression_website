// // Expose functions globally
// window.openProfileModal = function() {
//     console.log("openProfileModal called"); // Step 1: function triggered

//     const modal = document.getElementById("profileModal");
//     if (!modal) {
//         console.error("Profile modal element not found!");
//         return;
//     }

//     modal.style.display = "block";
//     console.log("Modal display set to block");

//     // Fetch user data from JSON endpoint
//     fetch("view_profile.php")
//         .then(response => {
//             console.log("Fetch response received", response);

//             if (!response.ok) {
//                 throw new Error("Network response was not ok: " + response.status);
//             }
//             return response.json();
//         })
//         .then(data => {
//             console.log("Fetched user data:", data);

//             if (data.error) {
//                 throw new Error(data.error);
//             }

//             let profileHTML = `
//                 <p><strong>Name:</strong> ${data.first_name} ${data.middle_name} ${data.last_name}</p>
//                 <p><strong>Date of Birth:</strong> ${data.dob}</p>
//                 <p><strong>Contact:</strong> ${data.cn_num}</p>
//                 <p><strong>Email:</strong> ${data.email}</p>
//                 <p><strong>Gender:</strong> ${data.gender}</p>
//                 <p><strong>Year:</strong> ${data.year}</p>
//                 <p><strong>Course:</strong> ${data.course}</p>
//             `;
//             document.getElementById("profile-details").innerHTML = profileHTML;
//             console.log("Profile HTML injected into modal");
//         })
//         .catch(err => {
//             console.error("Failed to load profile:", err);
//             alert("Failed to load profile: " + err.message);
//         });
// };

// window.closeProfileModal = function() {
//     console.log("closeProfileModal called");
//     document.getElementById("profileModal").style.display = "none";
// };


// // Password form handler
// document.getElementById("passwordForm")?.addEventListener("submit", function(e) {
//     e.preventDefault();
//     let newPass = document.getElementById("newPassword").value;
//     let confirmPass = document.getElementById("confirmPassword").value;

//     if(newPass !== confirmPass) {
//         alert("Passwords do not match!");
//         return;
//     }

//     fetch("user_action/update_password.php", {
//         method: "POST",
//         headers: { "Content-Type": "application/x-www-form-urlencoded" },
//         body: "password=" + encodeURIComponent(newPass)
//     })
//     .then(res => res.text())
//     .then(msg => alert(msg)); 
// });

// document.addEventListener('DOMContentLoaded', () => {
//     const profileBtn = document.getElementById('profile_btn');
//     const modal = document.getElementById('profileModal');
//     const closeBtn = modal.querySelector('.close');

//     profileBtn.addEventListener('click', () => {
//         fetch('view_profile.php?ajax=1')
//             .then(res => res.json())
//             .then(data => {
//                 const userInfoDiv = modal.querySelector('#userInfo');

//                 if (data.error) {
//                     userInfoDiv.innerHTML = `<p>${data.error}</p>`;
//                 } else {
//                     userInfoDiv.innerHTML = `
//                         <p><strong>Name:</strong> ${data.first_name} ${data.middle_name} ${data.last_name}</p>
//                         <p><strong>Date of Birth:</strong> ${data.dob}</p>
//                         <p><strong>Contact:</strong> ${data.cn_num}</p>
//                         <p><strong>Email:</strong> ${data.email}</p>
//                         <p><strong>Gender:</strong> ${data.gender}</p>
//                         <p><strong>Year:</strong> ${data.year}</p>
//                         <p><strong>Course:</strong> ${data.course}</p>
//                     `;
//                 }

//                 modal.style.display = 'block';
//             })
//             .catch(err => {
//                 alert('Error fetching profile');
//             });
//     });

//     closeBtn.addEventListener('click', () => modal.style.display = 'none');
//     window.addEventListener('click', (e) => {
//         if (e.target === modal) modal.style.display = 'none';
//     });
// });

// user_info.js - Profile modal functions

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
    // Perform logout - redirect to logout script
    window.location.href = '../user_action/login.php';
}