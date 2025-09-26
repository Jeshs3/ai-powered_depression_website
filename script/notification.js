document.addEventListener("DOMContentLoaded", () => {
  const notifList = document.getElementById("notifList");            // dropdown list (may be null on some pages)
  const notifCount = document.getElementById("notifCount");          // badge (may be null)
  const notificationPageList = document.getElementById("notificationList"); // full-page container (may be null)
  const markAllBtn = document.getElementById("markAllRead");        // "Mark all as read" button on page (may be null)
  // Prefer the known id; fallback to any dropdown toggle if id isn't in header
  const dropdownToggle = document.getElementById("navbarDropdown") || document.querySelector('[data-bs-toggle="dropdown"]');

  /* Render notifications for the full page */
  function renderNotifications(notifications) {
    if (!notificationPageList) return; // nothing to render to
    notificationPageList.innerHTML = "";

    if (!Array.isArray(notifications) || notifications.length === 0) {
      notificationPageList.innerHTML = `
        <div class="col-12 text-center text-muted py-5">
          <i class="fa-solid fa-bell-slash fa-2x mb-3"></i>
          <p>No notifications yet.</p>
        </div>`;
      return;
    }

    notifications.forEach(n => {
      const card = document.createElement("div");
      card.className = `col-12 notification-card card p-3 ${n.is_read == 0 ? "notification-unread" : "notification-read"}`;
      card.innerHTML = `
        <div class="d-flex justify-content-between">
          <div>
            <h6 class="mb-1">${n.type.replace('_',' ').toUpperCase()}</h6>
            <p class="mb-1">${n.message}</p>
            <small class="text-muted">${new Date(n.created_at).toLocaleString()}</small>
          </div>
          <div class="align-self-center">
            ${n.is_read == 0 ? `<span class="badge bg-primary">New</span>` : ""}
          </div>
        </div>
      `;

      // Optional: click a card to mark single notification as read
      card.addEventListener("click", async () => {
        if (n.is_read == 0) {
          const ok = await markSingleAsRead(n.notification_id);
          if (ok) {
            n.is_read = 1; // update local model
            card.classList.remove("notification-unread");
            card.classList.add("notification-read");
            const badge = card.querySelector(".badge");
            if (badge) badge.remove();

            // Decrement dropdown badge if present
            if (notifCount) {
              const current = parseInt(notifCount.textContent) || 0;
              const next = Math.max(0, current - 1);
              notifCount.textContent = next;
              notifCount.style.display = next ? "inline-block" : "none";
            }
          }
        }
      });

      notificationPageList.appendChild(card);
    });
  }

  /* Fetch notifications (used by both dropdown & full page) */
  async function fetchNotifications() {
    try {
      const res = await fetch("../database/get_notify.php");
      const data = await res.json();

      // Update dropdown UI if present
      if (notifList) {
        if (!Array.isArray(data) || data.length === 0) {
          notifList.innerHTML = `<div class="text-center text-muted py-3">No notifications</div>`;
          if (notifCount) { notifCount.textContent = 0; notifCount.style.display = "none"; }
        } else {
          notifList.innerHTML = "";
          let unreadCount = 0;
          data.forEach(notif => {
            const isUnread = notif.is_read == 0;
            if (isUnread) unreadCount++;

            const item = document.createElement("div");
            item.className = `list-group-item list-group-item-action d-flex justify-content-between align-items-start ${isUnread ? 'bg-light fw-bold' : ''}`;
            item.innerHTML = `
              <div class="ms-2 me-auto">
                <div class="fw-semibold">${notif.type.replace('_',' ').toUpperCase()}</div>
                <small class="text-muted">${notif.message}</small>
              </div>
              <small class="text-muted">${new Date(notif.created_at).toLocaleString()}</small>
            `;
            notifList.appendChild(item);
          });

          if (notifCount) {
            notifCount.textContent = unreadCount;
            notifCount.style.display = unreadCount ? "inline-block" : "none";
          }
        }
      }

      // Render full-page notification list if present
      if (notificationPageList) {
        renderNotifications(data);
      }
    } catch (err) {
      console.error("Error fetching notifications:", err);
      if (notifList) notifList.innerHTML = `<div class="text-center text-danger py-3">Failed to load notifications</div>`;
      if (notificationPageList) notificationPageList.innerHTML = `<div class="col-12 text-center text-danger py-5">Failed to load notifications</div>`;
    }
  }

  /* Mark all as read (POST { mark_all: true } to your get_notify.php) */
  async function markAllAsRead() {
    try {
      const res = await fetch("../database/get_notify.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ mark_all: true })
      });
      const data = await res.json();
      if (data.success) {
        // update small dropdown UI
        if (notifCount) { notifCount.textContent = 0; notifCount.style.display = "none"; }
        if (notifList) {
          notifList.querySelectorAll(".list-group-item").forEach(it => it.classList.remove("bg-light", "fw-bold"));
        }
        // update full-page UI
        if (notificationPageList) {
          notificationPageList.querySelectorAll(".notification-card").forEach(card => {
            card.classList.remove("notification-unread");
            card.classList.add("notification-read");
            const badge = card.querySelector(".badge");
            if (badge) badge.remove();
          });
        }
      }
    } catch (err) {
      console.error("Error marking all as read:", err);
    }
  }

  /* Mark a single notification as read */
  async function markSingleAsRead(id) {
    try {
      const res = await fetch("../database/get_notify.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id })
      });
      const data = await res.json();
      return !!data.success;
    } catch (err) {
      console.error("Error marking single as read:", err);
      return false;
    }
  }

  // Wire up "Mark all" button on the page
  if (markAllBtn) {
    markAllBtn.addEventListener("click", async () => {
      await markAllAsRead();
      await fetchNotifications(); // refresh list
    });
  }

  // When dropdown opens, mark all as read (if dropdown exists)
  if (dropdownToggle) {
    // Bootstrap emits custom events like 'show.bs.dropdown' on the toggle element
    dropdownToggle.addEventListener("show.bs.dropdown", async () => {
      await markAllAsRead();
      // Optionally refresh dropdown contents immediately
      await fetchNotifications();
    });
  }

  // initial load + polling
  fetchNotifications();
  setInterval(fetchNotifications, 30000);
});
