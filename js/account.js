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

    var nameRegex = /^[a-zA-Z0-9\s]+$/;
    var emailRegex = /^[a-zA-z.-_]+@+[a-z]+\.+[a-z]{2,3}$/;

    var errorMessage = "";

    if(!nameRegex.test(username)) {
        errorMessage += 'Please enter a username!\n';
    }

    if(!emailRegex.test(email)) {
        errorMessage += 'Please enter a valid email!\n';
    }

    if(password.length < 6) {
        errorMessage += 'Password must be longer than 6!\n';
    }

    if (confirmPassword !== password) {
        errorMessage += 'Passwords do not match!\n';
    }
    if(errorMessage !== "") {
        document.getElementById('error-message').innerText= errorMessage;
        return false;
    }

    document.getElementById('error-message').innerText="";
    return true;
}

function validateLog() {
    var email = document.getElementById('email').value;

    var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$/;

    var errorMessage = "";

    if (!emailRegex.test(email)) {
        errorMessage += 'Please enter a valid email!\n';
        document.getElementById('error-message').innerText = errorMessage;
        return false;
    }

    document.getElementById('error-message').innerText = "";
    return true;
}

