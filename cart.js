
document.addEventListener("DOMContentLoaded", () => {
    updateCartBadge();
    loadCart();
});


function loadCart() {
    const cart = JSON.parse(localStorage.getItem("cart")) || {};
    const cartItemsContainer = document.getElementById("cart-items");
    const cartBox = document.getElementById("cart-items-container");
    const emptyMessage = document.getElementById("empty-cart-message");
    const grandTotalEl = document.getElementById("grand-total");

    cartItemsContainer.innerHTML = "";
    let grandTotal = 0;
    let hasItems = false;

    for (let id in cart) {
        hasItems = true;
        const item = cart[id];
        const subtotal = item.price * item.quantity;
        grandTotal += subtotal;

        cartItemsContainer.innerHTML += `
            <tr>
                <td>${item.name}</td>
                <td>$${item.price.toFixed(2)}</td>
                <td>${item.quantity}</td>
                <td>$${subtotal.toFixed(2)}</td>
                <td>
                    <button class="btn-primary" onclick="removeItem(${id})">
                        Remove
                    </button>
                </td>
            </tr>
        `;
    }

    if (hasItems) {
        cartBox.style.display = "block";
        emptyMessage.style.display = "none";
        grandTotalEl.textContent = `$${grandTotal.toFixed(2)}`;
    } else {
        cartBox.style.display = "none";
        emptyMessage.style.display = "block";
    }
}

function removeItem(id) {
    let cart = JSON.parse(localStorage.getItem("cart")) || {};

    delete cart[id];

    localStorage.setItem("cart", JSON.stringify(cart));
    showNotification("Item removed 🗑️");

    updateCartBadge();
    loadCart();
}

function updateCartBadge() {
    const cart = JSON.parse(localStorage.getItem("cart")) || {};
    let count = 0;

    for (let id in cart) {
        count += cart[id].quantity;
    }

    document.getElementById("cart-badge").textContent = count;
}


function checkout() {
    showNotification("Checkout successful 🎉");
    localStorage.removeItem("cart");
    updateCartBadge();
    loadCart();
}


function showNotification(message) {
    const notification = document.getElementById("notification");
    notification.textContent = message;
    notification.classList.add("show");

    setTimeout(() => {
        notification.classList.remove("show");
    }, 2000);
}
