$(document).ready(function () {
    let token = localStorage.getItem("token");

    if (!token) {
        window.location.href = "login.html";
    }

    $("#searchForm").submit(function (event) {
        event.preventDefault();
        let query = $("#searchQuery").val();

        $.ajax({
            url: "api/articles/search.php",
            type: "GET",
            data: { q: query },
            headers: { "Authorization": "Bearer " + token },
            success: function (response) {
                let articles = response.articles;
                $("#searchResults").empty();
                articles.forEach(article => {
                    $("#searchResults").append(`
                        <li>
                            ${article.title} 
                            <a href="${article.url}" target="_blank">Read</a> 
                            <button class="add-favorite" data-id="${article.id}" data-title="${article.title}" data-url="${article.url}">Favorite</button>
                        </li>
                    `);
                });
            }
        });
    });

    $(document).on("click", ".add-favorite", function () {
        let articleId = $(this).data("id");
        let title = $(this).data("title");
        let url = $(this).data("url");

        $.ajax({
            url: "api/favorites/add.php",
            type: "POST",
            headers: { "Authorization": "Bearer " + token },
            data: { article_id: articleId, title: title, url: url },
            success: function () {
                alert("Article added to favorites!");
            }
        });
    });

    $("#logout").click(function () {
        localStorage.removeItem("token");
        window.location.href = "login.html";
    });
});
