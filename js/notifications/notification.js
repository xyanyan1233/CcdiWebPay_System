
document.addEventListener("DOMContentLoaded", function () {
    const bell = document.querySelector(".notification-bell");
    const dropdown = document.querySelector(".notification-dropdown");
    const unreadBadge = document.querySelector(".notification-count");
    const notificationList = document.getElementById("notification-list");

    // Function to fetch notifications
    function fetchNotifications() {
        Promise.all([
            fetch("../../theme/students/functions/get_unread_count.php").then(res => res.json()),
            fetch("../../theme/students/functions/get_duedate.php").then(res => res.json())
        ]).then(([unreadData, dueData]) => {
            const unreadCount = unreadData.unreadCount || 0;
            const dueCount = dueData.dueCount || 0;
            const totalCount = unreadCount + dueCount;

            // Update Notification Badge
            if (totalCount > 0) {
                unreadBadge.style.display = "block";
                unreadBadge.textContent = totalCount;
            } else {
                unreadBadge.style.display = "none";
            }

            // Fetch Message Notifications
            fetch("../../theme/students/functions/get_message.php")
                .then(res => res.json())
                .then(messages => {
                    notificationList.innerHTML = ""; // Clear old notifications

                    // Append messages
                    messages.forEach(msg => {
                        const li = document.createElement("li");
                        li.className = msg.is_read ? "read" : "unread";
                        li.innerHTML = `<a href="inbox.php?id=${msg.id}">${msg.message}</a>`;
                        notificationList.appendChild(li);
                    });

                    // Append Due Payments
                    if (dueCount > 0) {
                        const dueLi = document.createElement("li");
                        dueLi.innerHTML = `<a href="payments.php">You have ${dueCount} due payment(s) in 7 days.</a>`;
                        notificationList.appendChild(dueLi);
                    }

                    // "View All" link
                    const viewAll = document.createElement("a");
                    viewAll.href = "inbox.php";
                    viewAll.className = "view-all";
                    viewAll.textContent = "View All Notifications";
                    notificationList.appendChild(viewAll);
                });
        }).catch(error => console.error("Error fetching notifications:", error));
    }

    // Show/Hide Notification Dropdown
    bell.addEventListener("click", function () {
        dropdown.classList.toggle("active");
    });

    // Fetch notifications on page load
    fetchNotifications();
});