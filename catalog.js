

const categoryList = document.getElementById("category-list");
const productsGrid = document.getElementById("products-grid");
const sectionTitle = document.getElementById("section-title");

async function loadCategories() {
    try {
        const res = await fetch("api/get_categories.php", {
            credentials: "include"
        });

        if (res.status === 401) {
            window.location.href = "login.html";
            return;
        }

        const json = await res.json();

        categoryList.innerHTML = "";

        const allLi = document.createElement("li");
        allLi.textContent = "All";
        allLi.classList.add("active");
        allLi.onclick = () => {
            loadProducts();
            setActive(allLi);
        };
        categoryList.appendChild(allLi);

      
        json.data.forEach(cat => {
            const li = document.createElement("li");
            li.textContent = cat.name;

            li.onclick = () => {
                loadProducts(cat.id, cat.name);
                setActive(li);
            };

            categoryList.appendChild(li);
        });

    } catch (error) {
        console.error("Failed to load categories", error);
    }
}


async function loadProducts(categoryId = null, title = "All Products") {
    try {
        sectionTitle.textContent = title;
        productsGrid.innerHTML = "";

        let url = "api/get_products.php";
        if (categoryId) {
            url += `?category_id=${categoryId}`;
        }

        const res = await fetch(url, {
            credentials: "include"
        });

        if (res.status === 401) {
            window.location.href = "login.html";
            return;
        }

        const json = await res.json();

        if (!json.success) {
            console.error(json.message);
            return;
        }

        if (json.data.length === 0) {
            productsGrid.innerHTML = "<p>No products found.</p>";
            return;
        }

        json.data.forEach(p => {
            const card = document.createElement("div");
            card.className = "product-card";

            card.innerHTML = `
                <div class="product-image">
                    <img src="${p.image_url || 'assets/default.png'}" alt="${p.name}">
                </div>
                <h3>${p.name}</h3>
                <p class="product-category">${p.category_name}</p>
                <p class="product-description">${p.description}</p>
                <p class="product-price">$${p.price.toFixed(2)}</p>

                <button class="btn-add-to-cart"
                    ${p.stock === 0 ? "disabled" : ""}
                    onclick="addToCart(${p.id})">
                    ${p.stock === 0 ? "Out of Stock" : "Add to Cart"}
                </button>
            `;

            productsGrid.appendChild(card);
        });

    } catch (error) {
        console.error("Failed to load products", error);
    }
}


function setActive(activeItem) {
    document.querySelectorAll(".category-list li")
        .forEach(li => li.classList.remove("active"));
    activeItem.classList.add("active");
}


async function addToCart(productId) {
    const res = await fetch("api/cart.php?action=add", {
        method: "POST",
        credentials: "include",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ product_id: productId, quantity: 1 })
    });

    const json = await res.json();
    if (json.success) {
        showNotification(json.message);
        loadCart(); 
    }
}
async function updateCartBadge() {
    try {
        const res = await fetch("api/cart.php?action=view", {
            credentials: "include"
        });
        const data = await res.json();

        let count = 0;
        if (data.success) {
            data.data.forEach(item => count += item.quantity);
        }

        document.getElementById("cart-badge").textContent = count;
    } catch (err) {
        console.error(err);
        document.getElementById("cart-badge").textContent = 0;
    }
}
function showNotification(message) {
    const notification = document.getElementById("notification");
    notification.textContent = message;
    notification.classList.add("show");

    setTimeout(() => {
        notification.classList.remove("show");
    }, 2000);
}

document.addEventListener("DOMContentLoaded", () => {
    loadCategories();
    loadProducts();
});
