document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("commentForm");
    const commentsList = document.getElementById("commentsList");

    if (!form) return;

    // Reactie versturen
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        const response = await fetch("comments.php", {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            form.reset();
            loadComments(formData.get("product_id"));
        } else {
            alert(data.error);
        }
    });

    // Reacties ophalen
    async function loadComments(productId) {
        const response = await fetch(`comments.php?product_id=${productId}`);
        const comments = await response.json();

        commentsList.innerHTML = "";

        comments.forEach(comment => {
            const div = document.createElement("div");
            div.classList.add("comment");

            div.innerHTML = `
                <strong>${comment.name}</strong>
                <p>${comment.comment}</p>
                <small>${comment.created_at}</small>
            `;

            commentsList.appendChild(div);
        });
    }

    // Eerste load bij openen pagina
    const productId = document.querySelector("input[name='product_id']")?.value;
    if (productId) {
        loadComments(productId);
    }
});