// theme.js
document.addEventListener("DOMContentLoaded", async () => {
  try {
    const res = await fetch("../database/get_admin.php");
    const data = await res.json();
    if (data.success && data.settings.theme === "dark") {
      document.body.classList.add("dark-mode");
    }
  } catch (err) {
    console.error("Error applying theme:", err);
  }
});
