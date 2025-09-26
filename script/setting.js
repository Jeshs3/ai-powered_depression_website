document.addEventListener("DOMContentLoaded", async () => {
  console.log("Settings JS loaded");

  //Fetch admin settings first to populate UI
  const themeToggle = document.getElementById("themeToggle");

  // Immediately update button in case DOM exists
  if (themeToggle) updateThemeButton(themeToggle);

  // Fetch admin settings
  const admins = await fetchAdmin();
  if (!admins) return;

  // Apply theme
  if (admins.theme === "dark") {
    document.body.classList.add("dark-mode");
  }

  // Re-update button after theme applied
  if (themeToggle) {
    updateThemeButton(themeToggle);

    themeToggle.addEventListener("click", () => {
      document.body.classList.toggle("dark-mode");
      updateThemeButton(themeToggle);

      saveSetting(
        "theme",
        document.body.classList.contains("dark-mode") ? "dark" : "light"
      );
    });
  }

  // Populate notifications
  const notifToggle = document.getElementById("notifications");
  if (notifToggle) {
    notifToggle.checked = admins.notifications == 1;
    notifToggle.addEventListener("change", () => {
      saveSetting("notifications", notifToggle.checked ? 1 : 0);
    });
  }

  // Populate data retention
  const retentionSelect = document.querySelector("select[name='data_retention']");
  if (retentionSelect) {
    retentionSelect.value = admins.data_retention;
    retentionSelect.addEventListener("change", () => {
      saveSetting("data_retention", parseInt(retentionSelect.value));
    });
  }

  // Populate profile form
  const profileForm = document.querySelector("form");
  if (profileForm) {
    profileForm.querySelector("input[name='username']").value = admins.username;
    profileForm.querySelector("input[name='email']").value = admins.email;

    profileForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const formData = new FormData(profileForm);
      const payload = Object.fromEntries(formData.entries());
      saveSetting("profile", payload, true);
    });
  }
});

//Fetch admin data
async function fetchAdmin() {
  try {
    const res = await fetch("../database/get_admin.php");
    const data = await res.json();
    if (!data.success) throw new Error(data.message);
    return data.admin;
  } catch (err) {
    console.error("Error fetching admin data:", err);
    showToast("Failed to load settings.", true);
    return null;
  }
}

//Save function
function saveSetting(key, value, isObject = false) {
  fetch("../Admin/update_setting.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ key, value })
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        showToast(`Setting updated: ${key}`);
      } else {
        showToast(`Error updating ${key}: ${data.message}`, true);
      }
    })
    .catch(err => {
      console.error("Error saving setting:", err);
      showToast("Network error. Try again.", true);
    });
}

//Update theme button text/icon
function updateThemeButton(btn) {
  const isDark = document.body.classList.contains("dark-mode");
  btn.innerHTML = isDark
    ? '<i class="fa-solid fa-moon me-2"></i> Switch to Light'
    : '<i class="fa-solid fa-sun me-2"></i> Switch to Dark';
}

//Simple toast
function showToast(message, isError = false) {
  const toast = document.createElement("div");
  toast.className =
    "position-fixed bottom-0 end-0 m-3 px-3 py-2 rounded shadow-sm " +
    (isError ? "bg-danger text-white" : "bg-success text-white");
  toast.innerText = message;
  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 3000);
}
