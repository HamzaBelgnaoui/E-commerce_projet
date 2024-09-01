// Données des produits
const products = [
    { id: 1, name: "T-SHORT running respirant homme", price: 69, image: "img/IMG1.jpg", category: "T-SHORT", description: "T-shirt de running respirant pour homme, idéal pour vos séances d'entraînement." },
    { id: 2, name: "T-SHORT running respirant homme", price: 89, image: "img/IMG2.jpg", category: "T-SHORT", description: "T-shirt de running respirant pour homme, confortable et léger." },
    { id: 3, name: "T-SHORT running respirant homme", price: 79, image: "img/IMG3.jpg", category: "T-SHORT", description: "T-shirt de running respirant pour homme, parfait pour les longues distances." },
    { id: 4, name: "T-SHORT running respirant homme", price: 99, image: "img/IMG4.jpg", category: "T-SHORT", description: "T-shirt de running respirant pour homme, avec technologie anti-transpiration." },
    { id: 5, name: "SHORT 1 homme", price: 49, image: "img/SHORT1.jpg", category: "SHORT", description: "Short de sport pour homme, confortable et flexible." },
    { id: 6, name: "SHORT 2 homme", price: 59, image: "img/SHORT2.jpg", category: "SHORT", description: "Short de sport pour homme, avec poches zippées." },
    { id: 7, name: "SHORT 3 homme", price: 39, image: "img/SHORT3.jpg", category: "SHORT", description: "Short de sport pour homme, léger et respirant." },
    { id: 8, name: "SHORT 4 homme", price: 49, image: "img/SHORT4.jpg", category: "SHORT", description: "Short de sport pour homme, idéal pour la course à pied." },
    { id: 9, name: "JACKET 1 homme", price: 129, image: "img/JACKET1.jpg", category: "JACKET", description: "Veste de sport pour homme, imperméable et coupe-vent." },
    { id: 10, name: "JACKET 2 homme", price: 139, image: "img/JACKET2.jpg", category: "JACKET", description: "Veste de sport pour homme, isolante et légère." }
];;

let cart = [];

// Modifiez la fonction checkout pour afficher le modal de commande
function checkout() {
    if (cart.length === 0) {
        alert("Votre panier est vide. Ajoutez des produits avant de passer à la caisse.");
        return;
    }
    $('#orderModal').modal('show');
}

// Fonction pour passer la commande
function placeOrder(event) {
    event.preventDefault();

    var name = document.getElementById('name').value;
    var email = document.getElementById('email').value;

    var orderData = {
        name: name,
        email: email,
        items: cart
    };

    fetch('place_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(orderData)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                clearCart(); // Vide le panier après une commande réussie
                $('#orderModal').modal('hide'); // Ferme le modal de commande
                updateCart(); // Met à jour l'affichage du panier
            } else {
                alert("Erreur : " + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert("Une erreur est survenue lors de la commande.");
        });
}

// Nouvelle fonction pour vider le panier
function clearCart() {
    cart = [];
    saveCartToLocalStorage();
}
// Assurez-vous d'ajouter un écouteur d'événements au formulaire
document.getElementById('orderForm').addEventListener('submit', placeOrder);



// Fonction pour afficher les produits
function displayProducts(productsToShow = products) {
    const container = document.getElementById('products-container');
    container.innerHTML = '';
    productsToShow.forEach(product => {
        container.innerHTML += `
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img class="card-img-top ${product.category}" src="${product.image}" alt="${product.name}">
                            
                            <div class="card-body">
                                <h5>${product.name}</h5>
                                <p class="card-text">${product.price.toFixed(2)}€</p>
                                <button class="btn btn-primary add-to-cart" onclick="addToCart(${product.id})">Ajouter au panier</button>
                                <button class="btn btn-info" onclick="showProductDetails(${product.id})">Détails</button>
                            </div>
                        </div>
                    </div>
                `;

    });
}



// Fonction pour filtrer les produits
function filterImages(category) {
    const filteredProducts = category === 'ALL' ? products : products.filter(product => product.category === category);
    displayProducts(filteredProducts);
}

// Fonction pour ajouter un produit au panier
function addToCart(productId) {
    const product = products.find(p => p.id === productId);
    if (product) {
        cart.push({ ...product, quantity: 1 });
        updateCart();
        animateAddToCart();
    }
}

// Fonction pour mettre à jour l'affichage du panier
function updateCart() {
    const cartItems = document.getElementById('cart-items');
    const totalPrice = document.getElementById('total-price');
    cartItems.innerHTML = '';
    let total = 0;

    cart.forEach((item, index) => {
        total += item.price * item.quantity;
        cartItems.innerHTML += `
                    <div class="cart-item">
                        <img src="${item.image}" alt="${item.name}">
                        <div>
                            ${item.name} - ${item.price.toFixed(2)}€ x ${item.quantity}
                            <br>
                            <button onclick="changeQuantity(${index}, -1)">-</button>
                            <button onclick="changeQuantity(${index}, 1)">+</button>
                        </div>
                        <span class="remove-item" onclick="removeFromCart(${index})">❌</span>
                    </div>
                `;
    });

    totalPrice.textContent = `${total.toFixed(2)}€`;
    saveCartToLocalStorage();
}

// Fonction pour changer la quantité d'un produit dans le panier
function changeQuantity(index, change) {
    cart[index].quantity += change;
    if (cart[index].quantity < 1) {
        cart.splice(index, 1);
    }
    updateCart();
}

// Fonction pour retirer un produit du panier
function removeFromCart(index) {
    cart.splice(index, 1);
    updateCart();
}

// Fonction pour sauvegarder le panier dans le localStorage
function saveCartToLocalStorage() {
    try {
        localStorage.setItem('cart', JSON.stringify(cart));
    } catch (error) {
        console.error('Erreur lors de la sauvegarde du panier:', error);
    }
}

// Fonction pour charger le panier depuis le localStorage
function loadCartFromLocalStorage() {
    try {
        const savedCart = localStorage.getItem('cart');
        if (savedCart) {
            cart = JSON.parse(savedCart);
            updateCart();
        }
    } catch (error) {
        console.error('Erreur lors du chargement du panier:', error);
    }
}

// Fonction pour animer l'ajout au panier
function animateAddToCart() {
    const cartIcon = document.querySelector('.cart');
    cartIcon.classList.add('add-to-cart-animation');
    setTimeout(() => {
        cartIcon.classList.remove('add-to-cart-animation');
    }, 500);
}

// Fonction pour afficher les détails d'un produit
function showProductDetails(productId) {
    const product = products.find(p => p.id === productId);
    if (product) {
        const modalBody = document.getElementById('productModalBody');
        modalBody.innerHTML = `
                    <img src="${product.image}" alt="${product.name}" class="img-fluid mb-3">
                    <h3>${product.name}</h3>
                    <p>${product.description}</p>
                    <p><strong>Prix:</strong> ${product.price.toFixed(2)}€</p>
                `;
        const addToCartButton = document.getElementById('addToCartModal');
        addToCartButton.onclick = () => {
            addToCart(product.id);
            $('#productModal').modal('hide');
        };
        $('#productModal').modal('show');
    }
}

// Fonction de recherche de produits (suite)
function searchProducts() {
    const searchTerm = document.querySelector('.search-input').value.toLowerCase();
    const filteredProducts = products.filter(product =>
        product.name.toLowerCase().includes(searchTerm) ||
        product.description.toLowerCase().includes(searchTerm)
    );
    displayProducts(filteredProducts);
}

// Initialisation de la page
function initPage() {
    displayProducts();
    loadCartFromLocalStorage();

    // Ajout d'un écouteur d'événements pour la recherche
    document.querySelector('.search-input').addEventListener('keyup', function (event) {
        if (event.key === 'Enter') {
            searchProducts();
        }
    });
}

// Appel de la fonction d'initialisation au chargement de la page
window.onload = initPage;