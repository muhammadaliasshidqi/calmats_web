import Alpine from 'alpinejs';

window.Alpine = Alpine;

const storedCart = (() => {
  try {
    return JSON.parse(localStorage.getItem('calmats-cart')) ?? [];
  } catch {
    return [];
  }
})();

const normalizeItem = (item) => ({
  ...item,
  color: item.color ?? 'Default',
  size: item.size ?? 'One size',
  quantity: Math.max(1, Number(item.quantity) || 1),
});

const normalizedCart = Array.isArray(storedCart) ? storedCart.map(normalizeItem) : [];
const isAuthenticated = document.querySelector('meta[name="calmats-authenticated"]')?.content === '1';
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

Alpine.store('cart', {
  items: normalizedCart,
  syncTimer: null,

  async init() {
    if (!isAuthenticated) return;

    try {
      const response = await fetch('/cart/data', {
        headers: { Accept: 'application/json' },
        credentials: 'same-origin',
      });

      if (!response.ok) return;

      const payload = await response.json();
      const merged = new Map();

      [...this.items, ...(payload.items ?? [])].map(normalizeItem).forEach((item) => {
        const key = `${item.slug}::${item.color}::${item.size}`;
        const current = merged.get(key);
        merged.set(key, current
          ? { ...current, ...item, quantity: Math.max(current.quantity, item.quantity) }
          : item);
      });

      this.items = [...merged.values()];
      this.persist();
    } catch {
      // Cart lokal tetap dapat digunakan ketika sinkronisasi tidak tersedia.
    }
  },

  get count() {
    return this.items.reduce((total, item) => total + item.quantity, 0);
  },

  get total() {
    return this.items.reduce((total, item) => total + (item.price * item.quantity), 0);
  },

  add(product, size = 'One size', color = 'Default') {
    const stockLimit = product.stock === null || product.stock === undefined
      ? 20
      : Math.max(0, Number(product.stock));

    if (stockLimit < 1) return;

    const existing = this.items.find((item) => item.slug === product.slug && item.size === size && item.color === color);

    if (existing) {
      existing.quantity = Math.min(existing.quantity + 1, stockLimit);
      existing.stock = product.stock;
    } else {
      this.items.push({ ...product, size, color, quantity: 1 });
    }

    this.persist();
  },

  update(slug, size, color, quantity) {
    const item = this.items.find((entry) => entry.slug === slug && entry.size === size && entry.color === color);
    if (!item) return;

    const stockLimit = item.stock === null || item.stock === undefined ? 20 : Math.max(1, Number(item.stock));
    item.quantity = Math.min(Math.max(1, Number(quantity) || 1), stockLimit, 20);
    this.persist();
  },

  remove(slug, size, color) {
    this.items = this.items.filter((item) => !(item.slug === slug && item.size === size && item.color === color));
    this.persist();
  },

  clear() {
    this.items = [];
    this.persist();
  },

  persist() {
    localStorage.setItem('calmats-cart', JSON.stringify(this.items));

    if (!isAuthenticated) return;

    clearTimeout(this.syncTimer);
    this.syncTimer = setTimeout(() => this.sync(), 250);
  },

  async sync() {
    try {
      const response = await fetch('/cart/data', {
        method: 'PUT',
        credentials: 'same-origin',
        headers: {
          Accept: 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({
          items: this.items.map(({ slug, size, color, quantity }) => ({ slug, size, color, quantity })),
        }),
      });

      if (!response.ok) return;

      const payload = await response.json();
      this.items = (payload.items ?? []).map(normalizeItem);
      localStorage.setItem('calmats-cart', JSON.stringify(this.items));
    } catch {
      // Perubahan tersimpan lokal dan akan dicoba kembali pada interaksi berikutnya.
    }
  },
});

Alpine.data('cartPage', (whatsappUrl = null) => ({
  whatsappUrl,
  handoffNotice: '',
  customer: {
    name: '',
    phone: '',
    address: '',
    note: '',
  },

  format(value) {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      maximumFractionDigits: 0,
    }).format(value);
  },

  imageUrl(value) {
    if (!value) return '';
    if (/^https?:\/\//.test(value) || value.startsWith('/')) return value;
    return value.startsWith('images/') ? `/${value}` : `/storage/${value}`;
  },

  orderMessage() {
    const cart = Alpine.store('cart');
    const itemLines = cart.items.map((item, index) => [
      `${index + 1}. ${item.name}`,
      `   ${item.color} / ${item.size} / ${item.quantity}x`,
      `   ${this.format(item.price * item.quantity)}`,
    ].join('\n'));

    return [
      'Halo CALMATS, saya ingin memesan:',
      '',
      ...itemLines,
      '',
      `Subtotal: ${this.format(cart.total)}`,
      'Ongkir: dikonfirmasi melalui WhatsApp',
      '',
      `Nama: ${this.customer.name}`,
      `No. WhatsApp: ${this.customer.phone}`,
      `Alamat: ${this.customer.address}`,
      this.customer.note ? `Catatan: ${this.customer.note}` : null,
    ].filter(Boolean).join('\n');
  },

  async copyOrder(message) {
    try {
      await navigator.clipboard.writeText(message);
    } catch {
      const field = document.createElement('textarea');
      field.value = message;
      field.style.position = 'fixed';
      field.style.opacity = '0';
      document.body.appendChild(field);
      field.select();
      document.execCommand('copy');
      field.remove();
    }

    this.handoffNotice = 'Ringkasan pesanan sudah disalin. Kirimkan ke admin CALMATS melalui kanal yang tersedia.';
  },

  async handoff() {
    const cart = Alpine.store('cart');
    if (cart.items.length === 0) return;

    const message = this.orderMessage();

    if (!this.whatsappUrl) {
      await this.copyOrder(message);
      return;
    }

    const separator = this.whatsappUrl.includes('?') ? '&' : '?';
    const target = `${this.whatsappUrl}${separator}text=${encodeURIComponent(message)}`;
    const opened = window.open(target, '_blank', 'noopener,noreferrer');

    if (!opened) {
      await this.copyOrder(message);
      this.handoffNotice = 'WhatsApp tidak dapat dibuka. Ringkasan pesanan sudah disalin sebagai fallback.';
      return;
    }

    this.handoffNotice = 'WhatsApp dibuka di tab baru. Periksa kembali pesan sebelum mengirim.';
  },
}));

Alpine.start();

const io = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add('is-in');
      io.unobserve(entry.target);
    }
  });
}, { threshold: 0.12 });

document.querySelectorAll('.reveal').forEach((element) => io.observe(element));
