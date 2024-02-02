function openModal(movieid, title, duration, releaseYear, rating) {
    
    document.getElementById('show-id').value = movieid;
    document.getElementById('edit-title').value = title;
    document.getElementById('edit-duration').value = duration;
    document.getElementById('edit-releaseyear').value = releaseYear;
    document.getElementById('edit-rating').value = rating;
    // document.getElementById('edit-description').value = description;

    document.getElementById('editModal').style.display = 'block';


}

function openMessages() {
    document.getElementById('messages').style.display = 'block';
}

function closeMessages() {
    document.getElementById('messages').style.display = 'none';

}

function openUsers() {
    document.getElementById('users').style.display = 'block';
}

function closeUsers() {
    document.getElementById('users').style.display = 'none';

}

function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

function openMessage(message) {
    var decodedMessage = decodeURIComponent(message);
    document.getElementById("messageContent").innerText = decodedMessage;
    document.getElementById("messageModal").style.display = "block";
}

function closeMessageModal() {
    document.getElementById("messageModal").style.display = "none";
}