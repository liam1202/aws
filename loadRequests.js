document.addEventListener("DOMContentLoaded", function() {
    fetch('load_requests.php')
        .then(response => response.text())
        .then(html => {
            document.getElementById('requestsContainer').innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading the requests:', error);
            document.getElementById('requestsContainer').innerHTML = 'Failed to load requests.';
        });
        
});