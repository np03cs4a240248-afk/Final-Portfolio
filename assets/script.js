document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById("search");
    const tableBody = document.getElementById("bookTable");

    if (!searchInput || !tableBody) return;

    searchInput.addEventListener("keyup", function () {
        const query = this.value.trim();

        fetch("search.php?q=" + encodeURIComponent(query))
            .then(res => res.text())
            .then(data => {
                tableBody.innerHTML = data;
            })
            .catch(err => console.error(err));
    });

});
