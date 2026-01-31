function checkMembership(id){
    fetch('../ajax/check_membership.php?user_id=' + id)
    .then(res => res.text())
    .then(data => {
        document.getElementById('status').innerHTML = data;
    });
}
