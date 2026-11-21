import Alpine from 'alpinejs';

window.Alpine = Alpine;

const isAuthenticated = document.querySelector('meta[name="calmats-authenticated"]')?.content === '1';
const accountId = document.querySelector('meta[name="calmats-user-id"]')?.content ?? '';
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
const guestCartKey = 'calmats-cart-guest';
const cartKey = isAuthenticated && accountId ? `calmats-cart-user-${accountId}` : guestCartKey;

const readCart = (key) => {
  try {
    const value = JSON.parse(localStorage.getItem(key));
    return Array.isArray(value) ? value : [];
  } catch {
    return [];
  }
};

const legacyCart = readCart('calmats-cart');
const storedCart = readCart(cartKey).length > 0
  ? readCart(cartKey)
  : (!isAuthenticated ? legacyCart : []);
const pendingGuestCart = isAuthenticated
  ? (readCart(guestCartKey).length > 0 ? readCart(guestCartKey) : legacyCart)
  : [];

const normalizeSlug = (value) => String(value ?? '')
  .split('/')
  .filter(Boolean)
  .at(-1) ?? '';

const normalizeItem = (item) => ({
  ...item,
  slug: normalizeSlug(item.slug),
  color: item.color ?? 'Default',
  size: item.size ?? 'One size',
  quantity: Math.max(1, Number(item.quantity) || 1),
});

const normalizedCart = Array.isArray(storedCart) ? storedCart.map(normalizeItem) : [];

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

      [...(payload.items ?? []), ...this.items].map(normalizeItem).forEach((item) => {
        const key = `${item.slug}::${item.color}::${item.size}`;
        const current = merged.get(key);
        merged.set(key, current
          ? { ...current, ...item, quantity: Math.max(current.quantity, item.quantity) }
          : item);
      });

      pendingGuestCart.map(normalizeItem).forEach((item) => {
        const key = `${item.slug}::${item.color}::${item.size}`;
        const current = merged.get(key);
        const stockLimit = current?.stock ?? item.stock ?? 20;
        merged.set(key, current
          ? { ...current, quantity: Math.min(current.quantity + item.quantity, stockLimit, 20) }
          : item);
      });

      this.items = [...merged.values()];
      localStorage.setItem(cartKey, JSON.stringify(this.items));

      if (await this.sync()) {
        localStorage.removeItem(guestCartKey);
        localStorage.removeItem('calmats-cart');
      }
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

  add(product, size = 'One size', color = 'Default', quantity = 1) {
    product = { ...product, slug: normalizeSlug(product.slug) };
    quantity = Math.max(1, Number(quantity) || 1);

    const stockLimit = product.stock === null || product.stock === undefined
      ? 20
      : Math.max(0, Number(product.stock));

    if (stockLimit < 1) return;

    const existing = this.items.find((item) => item.slug === product.slug && item.size === size && item.color === color);

    if (existing) {
      existing.quantity = Math.min(existing.quantity + quantity, stockLimit);
      existing.stock = product.stock;
      existing.image = product.image;
      existing.price = product.price;
    } else {
      this.items.push({ ...product, size, color, quantity: Math.min(quantity, stockLimit) });
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
    localStorage.setItem(cartKey, JSON.stringify(this.items));

    if (!isAuthenticated) localStorage.removeItem('calmats-cart');

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

      if (!response.ok) return false;

      const payload = await response.json();
      this.items = (payload.items ?? []).map(normalizeItem);
      localStorage.setItem(cartKey, JSON.stringify(this.items));

      return true;
    } catch {
      // Perubahan tersimpan lokal dan akan dicoba kembali pada interaksi berikutnya.
      return false;
    }
  },
});

Alpine.data('cartPage', (whatsappUrl = null, account = {}) => ({
  whatsappUrl,
  handoffNotice: '',
  validating: false,
  canRetryWhatsapp: false,
  customer: {
    name: account.name ?? '',
    email: account.email ?? '',
    phone: '',
    address: '',
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
      this.customer.email ? `Email: ${this.customer.email}` : null,
      `No. WhatsApp: ${this.customer.phone}`,
      `Alamat: ${this.customer.address}`,
      'Status: permintaan pesanan — menunggu konfirmasi admin CALMATS',
    ].filter(Boolean).join('\n');
  },

  async validateCart() {
    const cart = Alpine.store('cart');
    const response = await fetch('/cart/validate', {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
      },
      body: JSON.stringify({
        items: cart.items.map(({ slug, size, color, quantity, price }) => ({ slug, size, color, quantity, price })),
      }),
    });

    if (!response.ok) throw new Error('Validasi keranjang gagal.');

    return response.json();
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

    this.validating = true;
    this.handoffNotice = '';
    this.canRetryWhatsapp = false;

    try {
      const validation = await this.validateCart();

      if (!validation.can_checkout) {
        this.handoffNotice = validation.messages.join(' ');
        return;
      }

      if (validation.changed) {
        cart.items = validation.items.map(normalizeItem);
        cart.persist();
        this.handoffNotice = `${validation.messages.join(' ')} Periksa perubahan, lalu lanjutkan kembali.`;
        return;
      }
    } catch {
      this.handoffNotice = 'Keranjang belum dapat divalidasi ke server. Coba lagi sebelum membuka WhatsApp.';
      return;
    } finally {
      this.validating = false;
    }

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
      this.canRetryWhatsapp = true;
      return;
    }

    this.canRetryWhatsapp = false;
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
