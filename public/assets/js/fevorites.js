let token = localStorage.getItem("token");
    if (!token) {
        window.location.href = "login.html";
    }
    let currentPage = 1;
    const limit = 5;
    
    function loadFavorites(page = 1) {
        $.ajax({
            url: `api/favorites/get.php?page=${page}&limit=${limit}`,
            type: "GET",
            headers: { "Authorization": "Bearer " + token },
            dataType: "json",
            success: function (response) {
                if (Array.isArray(response.data) && response.data.length > 0) {
                    $("#favoritesList").empty();
                    response.data.forEach(article => {
                        $("#favoritesList").append(`
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="flex-grow-1">${article.title}</span>
                                <div class="d-flex gap-2">
                                    <a href="${article.url}" target="_blank" class="btn btn-info btn-sm">Read</a> 
                                    <button class="btn btn-danger btn-sm remove" data-id="${article.id}">Remove</button>
                                </div>
                            </li>
                        `);
                    });
                    updatePagination(response.total, page);
                } else {
                    $("#favoritesList").html("<li class='list-group-item'>No favorites found.</li>");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error loading favorites:", error);
                alert("Failed to load favorites. Please try again.");
            }
        });
    }
    

    function updatePagination(total, page) {
        const totalPages = Math.ceil(total / limit);
        let paginationHtml = "";
        for (let i = 1; i <= totalPages; i++) {
            paginationHtml += `<li class="page-item ${i === page ? 'active' : ''}"><a class="page-link" href="#" onclick="loadFavorites(${i})">${i}</a></li>`;
        }
        $("#pagination ul").html(paginationHtml);
    }

        loadFavorites();

    // Search button click event
    $("#searchBtn").click(function () {
        let query = $("#searchQuery").val();
        if (query) {
            searchArticles(query);
        } else {
            alert("Please enter a search term.");
        }
    });

    // Function to search for articles with pagination
    function searchArticles(query, page = 1) {
        $.ajax({
            url: "api/articles/search.php",
            headers: { "Authorization": "Bearer " + token },
            type: "GET",
            data: { query: query, page: page },
            success: function (response) {
                console.log(response);
                if (response.articles) {
                    displayArticles(response.articles);
                } else {
                    alert("Error fetching articles");
                }
            },
            error: function () {
                alert("Error fetching articles");
            }
        });
    }

    // Function to display articles
    function displayArticles(data) {
        var results = $("#results");
        results.empty();

        if (!data || !Array.isArray(data) || data.length === 0) {
            results.append("<p>No articles found.</p>");
            return;
        }

        var articlesHtml = data.map(article => `
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">${escapeHtml(article.abstract)}</h5>
                <p class="card-text">${escapeHtml(article.lead_paragraph)}</p>
                <a href="${escapeHtml(article.web_url)}" target="_blank" class="btn btn-primary">Read More</a>
                <button class="btn btn-secondary addToFavorites" data-title="${escapeHtml(article.abstract)}" data-article_id="${article._id}" data-id="${article.web_url}">Add to Favorites</button>
            </div>
        </div>
    </div>
`).join("");

        results.append(`<div class="row">${articlesHtml}</div>`);
    }

    // Function to escape HTML to prevent XSS attacks
    function escapeHtml(str) {
        return str.replace(/[&<>"']/g, function (match) {
            return {
                "&": "&amp;",
                "<": "&lt;",
                ">": "&gt;",
                '"': "&quot;",
                "'": "&#039;"
            }[match];
        });
    }
    // Add to Favorites
    $(document).on("click", ".addToFavorites", function () {
        let web_url = $(this).data("id");
        let articleId = $(this).data("article_id");
        let title = $(this).data("title");

        $.ajax({
            url: "/api/favorites/add.php",
            headers: { "Authorization": "Bearer " + token },
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                article_id: articleId,
                web_url: web_url,
                title: title
            }),
            success: function () {
                loadFavorites();
                alert("Added to favorites");
            }
        });
    });

    // Remove article from favorites
    $(document).on("click", ".remove", function () {
        let favoriteId = $(this).data("id");
        $.ajax({
            url: "api/favorites/remove.php",
            type: "POST",
            headers: { "Authorization": "Bearer " + token },
            data: JSON.stringify({ favorite_Id: favoriteId }),
            contentType: "application/json",
            success: function () {
                loadFavorites();
                alert("Article removed from favorites!");

            },
            error: function () {
                alert("Failed to remove article. Try again.");
            }
        });
    });

    // Logout button click event
    $("#logout").click(function () {
        localStorage.removeItem("token");
        window.location.href = "login.html";
    });