import Alpine from 'alpinejs';

window.Alpine = Alpine;

const storedCart = (() => {
  try {
    return JSON.parse(localStorage.getItem('calmats-cart')) ?? [];
  } catch {
    return [];
  }
})();

Alpine.store('cart', {
  items: Array.isArray(storedCart) ? storedCart : [],

  get count() {
    return this.items.reduce((total, item) => total + item.quantity, 0);
  },

  get total() {
    return this.items.reduce((total, item) => total + (item.price * item.quantity), 0);
  },

  add(product, size = 'One size') {
    const existing = this.items.find((item) => item.slug === product.slug && item.size === size);

    if (existing) {
      existing.quantity += 1;
    } else {
      this.items.push({ ...product, size, quantity: 1 });
    }

    this.persist();
  },

  update(slug, size, quantity) {
    const item = this.items.find((entry) => entry.slug === slug && entry.size === size);
    if (!item) return;

    item.quantity = Math.max(1, Number(quantity) || 1);
    this.persist();
  },

  remove(slug, size) {
    this.items = this.items.filter((item) => !(item.slug === slug && item.size === size));
    this.persist();
  },

  clear() {
    this.items = [];
    this.persist();
  },

  persist() {
    localStorage.setItem('calmats-cart', JSON.stringify(this.items));
  },
});

Alpine.start();

// Reveal ringan saat scroll
const io = new IntersectionObserver((entries) => {
  entries.forEach((e) => { if (e.isIntersecting) { e.target.classList.add('is-in'); io.unobserve(e.target); } });
}, { threshold: 0.12 });
document.querySelectorAll('.reveal').forEach((el) => io.observe(el));
