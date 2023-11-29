function resnav() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}
function validateReg() {
    var username = document.getElementById('username').value;
    var email = document.getElementById('email').value; 
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirmPassword').value;

    var nameRegex = /^[a-zA-z\s]+$/;
    if(!nameRegex.test(username)) {
        alert('Please enter a valid name!');
        return false;
    }

    var emailRegex = /^[a-zA-z.-_]+@+[a-z]+\.+[a-z]{2,3}$/;
    if(!emailRegex.test(email)) {
        alert("Please enter a valid email address!");
        return false;
    }

    if(password.length < 6) {
        alert('Password must be at least 6 characters!');
        return false;
    }

    if (confirmPassword !== password) {
        alert('Passwords do not match');
        return false;
    }
}