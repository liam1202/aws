function updateRequest(requestId, status) {
    fetch('update_request.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `requestId=${requestId}&status=${status}`
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        location.reload(); // Reload the page to update the list of requests
    })
    .catch(error => console.error('Error:', error));
}