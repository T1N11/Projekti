function openModal(movieid, title, duration, releaseYear, rating) {
    
    document.getElementById('show-id').value = movieid;
    document.getElementById('edit-title').value = title;
    document.getElementById('edit-duration').value = duration;
    document.getElementById('edit-releaseyear').value = releaseYear;
    document.getElementById('edit-rating').value = rating;
    // document.getElementById('edit-description').value = description;

    document.getElementById('editModal').style.display = 'block';


}

// Function to close the modal
function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}