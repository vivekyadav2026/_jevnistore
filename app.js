// Initialize Icons
lucide.createIcons();

// Mobile Menu Toggle
const mobileBtn = document.getElementById('mobile-menu-btn');
const navLinks = document.getElementById('nav-links');

if (mobileBtn && navLinks) {
    mobileBtn.addEventListener('click', () => {
        navLinks.classList.toggle('active');
    });
}

// Product Data
window.products = [
    {
        id: 1,
        title: "Crimson Strapped Cargo",
        vendor: "GENRAGE",
        price: 3999,
        comparePrice: 5999,
        image: "assets/product_pants.png",
        hoverImage: "assets/product_pants.png",
        badges: ["sale"]
    },
    {
        id: 2,
        title: "Oversized Distressed Hoodie",
        vendor: "GENRAGE",
        price: 4500,
        comparePrice: null,
        image: "assets/product_hoodie.png",
        hoverImage: "assets/product_hoodie.png",
        badges: ["new"]
    },
    {
        id: 3,
        title: "Vintage Wash Grunge Tee",
        vendor: "GENRAGE",
        price: 1800,
        comparePrice: 2500,
        image: "assets/product_tshirt.png",
        hoverImage: "assets/product_tshirt.png",
        badges: ["sale"]
    },
    {
        id: 4,
        title: "Cyberpunk Tactical Vest",
        vendor: "GENRAGE",
        price: 3200,
        comparePrice: null,
        image: "assets/product_tshirt.png",
        hoverImage: "assets/product_tshirt.png",
        badges: ["sold-out"]
    },
    {
        id: 5,
        title: "Midnight Parachute Pants",
        vendor: "GENRAGE",
        price: 4100,
        comparePrice: null,
        image: "assets/product_pants.png",
        hoverImage: "assets/product_pants.png",
        badges: []
    },
    {
        id: 6,
        title: "Rage Zip-Up Hoodie",
        vendor: "GENRAGE",
        price: 4800,
        comparePrice: 6000,
        image: "assets/product_hoodie.png",
        hoverImage: "assets/product_hoodie.png",
        badges: ["sale"]
    },
    {
        id: 7,
        title: "Acid Wash Baby Tee",
        vendor: "GENRAGE",
        price: 1500,
        comparePrice: null,
        image: "assets/product_tshirt.png",
        hoverImage: "assets/product_tshirt.png",
        badges: []
    },
    {
        id: 8,
        title: "Heavyweight Boxy Tee",
        vendor: "GENRAGE",
        price: 2200,
        comparePrice: null,
        image: "assets/product_tshirt.png",
        hoverImage: "assets/product_tshirt.png",
        badges: ["new"]
    },
    { id: 9, title: "Leather Duffle Bag", vendor: "GENRAGE", price: 8500, comparePrice: null, image: "assets/bags/acc_1.jpeg", hoverImage: "assets/bags/acc_1.jpeg", badges: ["new"], category: "accessories" },
    { id: 10, title: "Tactical Backpack", vendor: "GENRAGE", price: 6500, comparePrice: null, image: "assets/bags/acc_2.jpeg", hoverImage: "assets/bags/acc_2.jpeg", badges: [], category: "accessories" },
    { id: 11, title: "Silver Cross Pendant", vendor: "GENRAGE", price: 1200, comparePrice: 1800, image: "assets/bags/acc_3.jpeg", hoverImage: "assets/bags/acc_3.jpeg", badges: ["sale"], category: "accessories" },
    { id: 12, title: "Heavy Chain Necklace", vendor: "GENRAGE", price: 1500, comparePrice: null, image: "assets/bags/acc_4.jpeg", hoverImage: "assets/bags/acc_4.jpeg", badges: [], category: "accessories" },
    { id: 13, title: "Gothic Ring Set", vendor: "GENRAGE", price: 900, comparePrice: null, image: "assets/bags/acc_5.jpeg", hoverImage: "assets/bags/acc_5.jpeg", badges: [], category: "accessories" },
    { id: 14, title: "Utility Chest Rig", vendor: "GENRAGE", price: 3500, comparePrice: null, image: "assets/bags/acc_6.jpeg", hoverImage: "assets/bags/acc_6.jpeg", badges: ["new"], category: "accessories" },
    { id: 15, title: "Spiked Choker", vendor: "GENRAGE", price: 800, comparePrice: null, image: "assets/bags/acc_7.jpeg", hoverImage: "assets/bags/acc_7.jpeg", badges: [], category: "accessories" },
    { id: 16, title: "Barbed Wire Bracelet", vendor: "GENRAGE", price: 650, comparePrice: null, image: "assets/bags/acc_8.jpeg", hoverImage: "assets/bags/acc_8.jpeg", badges: [], category: "accessories" },
    { id: 17, title: "D-Ring Belt", vendor: "GENRAGE", price: 1100, comparePrice: null, image: "assets/bags/acc_9.jpeg", hoverImage: "assets/bags/acc_9.jpeg", badges: [], category: "accessories" },
    { id: 18, title: "Onyx Signet Ring", vendor: "GENRAGE", price: 1800, comparePrice: null, image: "assets/bags/acc_10.jpeg", hoverImage: "assets/bags/acc_10.jpeg", badges: [], category: "accessories" }
];

const formatPrice = (price) => `₹${price.toLocaleString()}`;

// Render Products function
window.createProductCard = function(product) {
    let badgesHtml = '';
    if (product.badges.includes('sale')) {
        badgesHtml += `<span class="badge sale">SALE</span>`;
    }
    if (product.badges.includes('sold-out')) {
        badgesHtml += `<span class="badge sold-out" style="background:#333; color:#fff;">SOLD OUT</span>`;
    } else if (product.badges.includes('new')) {
        badgesHtml += `<span class="badge new">NEW</span>`;
    }

    let priceHtml = '';
    if (product.comparePrice) {
        priceHtml = `
            <span class="price-compare">${formatPrice(product.comparePrice)}</span>
            <span class="price-sale">${formatPrice(product.price)}</span>
        `;
    } else {
        priceHtml = `<span class="price-regular">${formatPrice(product.price)}</span>`;
    }

    // Link to product.html but trap quickview click
    return `
        <div class="product-card">
            <div class="product-img-wrapper">
                <div class="product-badges">${badgesHtml}</div>
                <img src="${product.image}" alt="${product.title}" class="product-img">
                <img src="${product.hoverImage}" alt="${product.title} Back" class="product-img-hover">
                <button class="quickview-trigger" onclick="window.openQuickView(${product.id}, event)" aria-label="Quick View"><i data-lucide="plus"></i></button>
            </div>
            <a href="product.html" class="product-info" style="display:block;">
                <div class="product-vendor" style="font-size:0.8rem; color:var(--text-secondary); margin-bottom:5px;">${product.vendor}</div>
                <h3 class="product-title">${product.title}</h3>
                <div class="product-price">${priceHtml}</div>
            </a>
        </div>
    `;
}

// Render logic
document.addEventListener('DOMContentLoaded', () => {
    const latestGrid = document.getElementById('latest-products');
    const allApparelGrid = document.getElementById('all-apparel');

    if (latestGrid && window.products) {
        latestGrid.innerHTML = window.products.slice(0, 4).map(window.createProductCard).join('');
    }
    
    if (allApparelGrid && window.products) {
        allApparelGrid.innerHTML = window.products.map(window.createProductCard).join('');
    }

    const accGrid = document.getElementById('accessories-grid');
    if (accGrid && window.products) {
        const accProducts = window.products.filter(p => p.category === 'accessories');
        accGrid.innerHTML = accProducts.map(window.createProductCard).join('');
    }

    const bagsGrid = document.getElementById('bags-grid');
    if (bagsGrid && window.products) {
        const bagsProducts = window.products.filter(p => p.category === 'bags');
        bagsGrid.innerHTML = bagsProducts.map(window.createProductCard).join('');
    }

    updateCartUI();
    
    // Re-initialize icons after DOM injection
    if (window.lucide) {
        lucide.createIcons();
    }
});

// CART LOGIC
let cart = JSON.parse(localStorage.getItem('genrage_cart')) || [];

const cartToggleBtn = document.getElementById('cart-toggle-btn');
const cartCloseBtn = document.getElementById('cart-close-btn');
const cartOverlay = document.getElementById('cart-overlay');
const cartPanel = document.getElementById('cart-panel');

function toggleCart() {
    const isOpen = cartPanel.classList.contains('active');
    if (isOpen) {
        cartPanel.classList.remove('active');
        cartOverlay.classList.remove('active');
        document.body.classList.remove('cart-open');
    } else {
        cartPanel.classList.add('active');
        cartOverlay.classList.add('active');
        document.body.classList.add('cart-open');
    }
}

if(cartToggleBtn) cartToggleBtn.addEventListener('click', toggleCart);
if(cartCloseBtn) cartCloseBtn.addEventListener('click', toggleCart);
if(cartOverlay) cartOverlay.addEventListener('click', toggleCart);

// Size selector toggle in product page
const sizeBtns = document.querySelectorAll('.size-btn');
let selectedSize = 'M';
sizeBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        sizeBtns.forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        selectedSize = btn.innerText;
    });
});

window.addToCart = function(productId) {
    const product = window.products.find(p => p.id === productId);
    if (!product) return;

    const existingItem = cart.find(item => item.id === productId && item.size === selectedSize);
    if (existingItem) {
        existingItem.qty += 1;
    } else {
        cart.push({ ...product, size: selectedSize, qty: 1 });
    }
    
    saveCart();
    updateCartUI();
    toggleCart(); // Open cart when added
};

window.removeFromCart = function(index) {
    cart.splice(index, 1);
    saveCart();
    updateCartUI();
};

window.updateQty = function(index, change) {
    if (cart[index].qty + change > 0) {
        cart[index].qty += change;
        saveCart();
        updateCartUI();
    }
};

function saveCart() {
    localStorage.setItem('genrage_cart', JSON.stringify(cart));
}

function updateCartUI() {
    const cartCountElements = document.querySelectorAll('.cart-count');
    const totalCount = cart.reduce((sum, item) => sum + item.qty, 0);
    cartCountElements.forEach(el => el.innerText = totalCount);

    const container = document.getElementById('cart-items-container');
    const subtotalEl = document.getElementById('cart-subtotal');
    
    if (!container) return;

    if (cart.length === 0) {
        container.innerHTML = '<div class="cart-empty">YOUR BAG IS EMPTY.</div>';
        if(subtotalEl) subtotalEl.innerText = '₹0';
        return;
    }

    let subtotal = 0;
    container.innerHTML = cart.map((item, index) => {
        subtotal += item.price * item.qty;
        return `
            <div class="cart-item">
                <img src="${item.image}" class="cart-item-img" alt="${item.title}">
                <div class="cart-item-info">
                    <div>
                        <div class="cart-item-title">${item.title}</div>
                        <div class="cart-item-size">Size: ${item.size}</div>
                        <div class="cart-item-price">${formatPrice(item.price)}</div>
                    </div>
                    <div class="cart-item-actions">
                        <div style="display:flex; align-items:center;">
                            <button class="qty-btn" onclick="updateQty(${index}, -1)">-</button>
                            <span class="qty-display">${item.qty}</span>
                            <button class="qty-btn" onclick="updateQty(${index}, 1)">+</button>
                        </div>
                        <button class="remove-btn" onclick="removeFromCart(${index})">Remove</button>
                    </div>
                </div>
            </div>
        `;
    }).join('');

    if(subtotalEl) subtotalEl.innerText = formatPrice(subtotal);
}

// QUICK VIEW LOGIC (Gen Z Brutalist Modal)
function ensureQuickViewModal() {
    if (document.getElementById('quickview-overlay')) return;
    const modalHtml = `
        <div class="quickview-overlay" id="quickview-overlay">
            <div class="quickview-modal">
                <button class="quickview-close" id="quickview-close"><i data-lucide="x"></i></button>
                <div class="quickview-img-col">
                    <img id="qv-img" src="" alt="Product">
                </div>
                <div class="quickview-info-col">
                    <div class="qv-vendor" id="qv-vendor">GENRAGE</div>
                    <h2 class="qv-title glitch-text" id="qv-title" data-text="Product">Product</h2>
                    <div class="qv-price" id="qv-price">₹0</div>
                    
                    <div class="variant-selector">
                        <div class="variant-label">Size: <span id="qv-size-label" style="color:var(--text-primary);">M</span></div>
                        <div class="size-selector" id="qv-size-selector">
                            <button class="size-btn">S</button>
                            <button class="size-btn selected">M</button>
                            <button class="size-btn">L</button>
                            <button class="size-btn">XL</button>
                        </div>
                    </div>
                    
                    <button class="btn btn-block quickview-btn" id="qv-add-btn">ADD TO BAG</button>
                    <a href="product.html" class="btn btn-outline btn-block quickview-btn" style="margin-top: 10px;">VIEW FULL DETAILS</a>
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    lucide.createIcons();

    document.getElementById('quickview-close').addEventListener('click', closeQuickView);
    document.getElementById('quickview-overlay').addEventListener('click', (e) => {
        if (e.target.id === 'quickview-overlay') closeQuickView();
    });

    // Size selector logic for QV
    const sizeBtns = document.querySelectorAll('#qv-size-selector .size-btn');
    sizeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            sizeBtns.forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            document.getElementById('qv-size-label').innerText = btn.innerText;
        });
    });
}

window.openQuickView = function(productId, event) {
    if (event) event.preventDefault();
    ensureQuickViewModal();
    const product = window.products.find(p => p.id === productId);
    if (!product) return;

    document.getElementById('qv-img').src = product.image;
    document.getElementById('qv-vendor').innerText = product.vendor;
    document.getElementById('qv-title').innerText = product.title;
    document.getElementById('qv-title').setAttribute('data-text', product.title);
    
    let priceHtml = '';
    if (product.comparePrice) {
        priceHtml = `<span class="price-compare" style="font-size:1.2rem; color:var(--text-secondary); text-decoration:line-through; margin-right:10px;">${formatPrice(product.comparePrice)}</span>${formatPrice(product.price)}`;
    } else {
        priceHtml = formatPrice(product.price);
    }
    document.getElementById('qv-price').innerHTML = priceHtml;

    const addBtn = document.getElementById('qv-add-btn');
    addBtn.onclick = () => {
        const selectedSizeBtn = document.querySelector('#qv-size-selector .size-btn.selected');
        const size = selectedSizeBtn ? selectedSizeBtn.innerText : 'M';
        
        const originalSize = selectedSize;
        selectedSize = size;
        window.addToCart(productId);
        selectedSize = originalSize;
        
        closeQuickView();
    };

    document.getElementById('quickview-overlay').classList.add('active');
};

window.closeQuickView = function() {
    const overlay = document.getElementById('quickview-overlay');
    if (overlay) overlay.classList.remove('active');
};
