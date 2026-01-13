document.addEventListener("DOMContentLoaded", () => {
    updateCartBadge();
    loadCart();
});

async function loadCart() {
    const cartItemsContainer = document.getElementById("cart-items");
    const cartBox = document.getElementById("cart-items-container");
    const emptyMessage = document.getElementById("empty-cart-message");
    const grandTotalEl = document.getElementById("grand-total");

    try {
        const res = await fetch("api/cart.php?action=view", {
            credentials: "include"
        });
        const data = await res.json();

        cartItemsContainer.innerHTML = "";

        if (data.success && data.data.length > 0) {
            cartBox.style.display = "block";
            emptyMessage.style.display = "none";

            data.data.forEach(item => {
                cartItemsContainer.innerHTML += `
                    <tr>
                        <td>${item.name}</td>
                        <td>$${item.price.toFixed(2)}</td>
                        <td>${item.quantity}</td>
                        <td>$${item.subtotal.toFixed(2)}</td>
                        <td>
                            <button class="btn-primary" onclick="removeItem(${item.id})">
                                Remove
                            </button>
                        </td>
                    </tr>
                `;
            });

            grandTotalEl.textContent = `$${data.grand_total.toFixed(2)}`;
        } else {
            cartBox.style.display = "none";
            emptyMessage.style.display = "block";
            grandTotalEl.textContent = `$0.00`;
        }
    } catch (err) {
        console.error(err);
        showNotification("Failed to load cart");
    }
}

async function addToCart(productId) {
    try {
        const res = await fetch("api/cart.php?action=add", {
            method: "POST",
            credentials: "include",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        });

        const data = await res.json();
        if (data.success) {
            showNotification(data.message);
            updateCartBadge();
            loadCart();
        } else {
            showNotification(data.message);
        }
    } catch (err) {
        console.error(err);
        showNotification("Failed to add item");
    }
}


async function removeItem(productId) {
    try {
        const res = await fetch("api/cart.php?action=remove", {
            method: "POST",
            credentials: "include",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ product_id: productId })
        });

        const data = await res.json();
        if (data.success) {
            showNotification(data.message);
            updateCartBadge();
            loadCart();
        } else {
            showNotification(data.message);
        }
    } catch (err) {
        console.error(err);
        showNotification("Failed to remove item");
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


async function checkout() {
    try {
        
        const res = await fetch("api/cart.php?action=clear", {
            method: "POST",
            credentials: "include"
        });

        const data = await res.json();
        if (data.success) {
            showNotification("Checkout successful 🎉");
            updateCartBadge();
            loadCart();
        } else {
            showNotification("Checkout failed");
        }
    } catch (err) {
        console.error(err);
        showNotification("Checkout failed");
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
