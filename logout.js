function logout() {
    var confirmLogOut = confirm("Are you sure you want to logout?");

    if(confirmLogOut) {
        window.location.href = "logout.php";
    }
}