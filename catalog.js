
const categoryList = document.getElementById("category-list");
const productsGrid = document.getElementById("products-grid");
const sectionTitle = document.getElementById("section-title");





function loadCategories() {
  categoryList.innerHTML = "";

  // All
  const allLi = document.createElement("li");
  allLi.textContent = "All";
  allLi.onclick = () => showProducts();
  categoryList.appendChild(allLi);

  
  categories.forEach(cat => {
    const li = document.createElement("li");
    li.textContent = cat.name;
    li.onclick = () => showProducts(cat.id, cat.name);
    categoryList.appendChild(li);
  });
  function setActive(activeItem) {
  document.querySelectorAll(".category-list li")
    .forEach(li => li.classList.remove("active"));
  activeItem.classList.add("active");
}
}


function showProducts(categoryId = null, title = "All Products") {
  productsGrid.innerHTML = "";
  sectionTitle.textContent = title;

  const filteredProducts = categoryId
    ? products.filter(p => p.categoryId === categoryId)
    : products;

  filteredProducts.forEach(p => {
    const card = document.createElement("div");
    card.className = "product-card";

    card.innerHTML = `
      
      <h3>${p.name}</h3>
      <p class="product-price">$${p.price}</p>
     <button class="btn-add-to-cart " onclick="addToCart(${p.id})"
       >Add to Cart</button>

    `;

    productsGrid.appendChild(card);
  });
}
function addToCart(productId) {
    let cart = JSON.parse(localStorage.getItem("cart")) || {};
    const product = products.find(p => p.id === productId);

    if (!product) {
        console.error("Product not found", productId);
        return;
    }

    if (cart[productId]) {
        cart[productId].quantity++;
    } else {
        cart[productId] = {
            id: product.id,
            name: product.name,
            price: product.price,
            quantity: 1
        };
    }

    localStorage.setItem("cart", JSON.stringify(cart));
}





loadCategories();
showProducts();
