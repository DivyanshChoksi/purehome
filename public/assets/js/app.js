/* ===============================
   PUREHOME GLOBAL JS
=============================== */

document.addEventListener("DOMContentLoaded", () => {

    /* ===============================
       USER DROPDOWN (CLICK BASED)
    =============================== */
    const userMenu = document.getElementById("userMenu");
    const userDropdown = document.getElementById("userDropdown");

    if (userMenu && userDropdown) {
        userMenu.addEventListener("click", (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle("show");
        });

        document.addEventListener("click", () => {
            userDropdown.classList.remove("show");
        });
    }

    /* ===============================
       GUEST DROPDOWN (CLICK BASED)
    =============================== */
    const guestMenu = document.querySelector(".guest-menu");
    const guestDropdown = document.querySelector(".guest-dropdown");

    if (guestMenu && guestDropdown) {
        guestMenu.addEventListener("click", (e) => {
            e.stopPropagation();
            guestDropdown.classList.toggle("show");
        });

        document.addEventListener("click", () => {
            guestDropdown.classList.remove("show");
        });
    }

    /* ===============================
       AUTO HIDE ALERTS
    =============================== */
    document.querySelectorAll(".success, .error, .info").forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = "0";
            alert.style.transition = "opacity 0.4s";
            setTimeout(() => alert.remove(), 400);
        }, 3500);
    });

    /* ===============================
       CONFIRM DELETE ACTIONS
    =============================== */
    document.querySelectorAll("[data-confirm]").forEach(el => {
        el.addEventListener("click", (e) => {
            const message = el.dataset.confirm || "Are you sure?";
            if (!confirm(message)) e.preventDefault();
        });
    });

    /* ===============================
       PREVENT DOUBLE FORM SUBMIT
    =============================== */
    document.querySelectorAll("form").forEach(form => {
        form.addEventListener("submit", () => {
            form.querySelectorAll("button[type='submit']").forEach(btn => {
                btn.disabled = true;
                btn.dataset.originalText = btn.innerText;
                btn.innerText = "Please wait...";
            });
        });
    });

    /* ===============================
       FILE INPUT IMAGE PREVIEW
    =============================== */
    document.querySelectorAll("input[type='file']").forEach(input => {
        input.addEventListener("change", () => {
            const file = input.files[0];
            if (!file || !file.type.startsWith("image/")) return;

            let preview = input.parentNode.querySelector(".img-preview");
            if (!preview) {
                preview = document.createElement("img");
                preview.className = "img-preview";
                preview.style.maxWidth = "120px";
                preview.style.marginTop = "10px";
                input.parentNode.appendChild(preview);
            }

            preview.src = URL.createObjectURL(file);
        });
    });

});

/* ===============================
   GLOBAL HELPERS
=============================== */

/** Redirect helper */
function redirect(url) {
    window.location.href = url;
}

/** Format currency */
function money(amount) {
    return "₹" + Number(amount).toFixed(2);
}

/* ===============================
   THEME TOGGLE (ADMIN + VENDOR)
=============================== */

document.addEventListener("DOMContentLoaded", () => {
    const toggleBtn = document.getElementById("themeToggle");
    const icon = document.getElementById("themeIcon");

    if (!toggleBtn || !icon) return;

    // Load saved theme
    const savedTheme = localStorage.getItem("theme");

    if (savedTheme === "dark") {
        document.body.classList.add("dark");
        icon.classList.remove("fa-sun");
        icon.classList.add("fa-moon");
    } else {
        icon.classList.remove("fa-moon");
        icon.classList.add("fa-sun");
    }

    // Toggle theme
    toggleBtn.addEventListener("click", () => {
        document.body.classList.toggle("dark");

        if (document.body.classList.contains("dark")) {
            localStorage.setItem("theme", "dark");
            icon.classList.remove("fa-sun");
            icon.classList.add("fa-moon");
        } else {
            localStorage.setItem("theme", "light");
            icon.classList.remove("fa-moon");
            icon.classList.add("fa-sun");
        }
    });
});

