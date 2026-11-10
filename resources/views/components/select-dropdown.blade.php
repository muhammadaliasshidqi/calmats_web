@props([
  'name',
  'value' => null,
  'options' => [],
  'label' => 'Pilih opsi',
])

<div
  class="relative"
  x-data="{
    open: false,
    selected: @js((string) $value),
    items: Object.entries(@js($options)).map(([value, label]) => ({ value, label })),
    get selectedLabel() {
      return this.items.find((item) => item.value === this.selected)?.label ?? @js($label);
    },
    toggle() {
      this.open = !this.open;
      if (this.open) {
        this.$nextTick(() => {
          const selected = this.$refs.options?.querySelector('[aria-selected=true]');
          (selected ?? this.$refs.options?.querySelector('button'))?.focus();
        });
      }
    },
    choose(value) {
      this.selected = value;
      this.open = false;
      this.$nextTick(() => this.$refs.trigger.focus());
    },
    moveFocus(event, direction) {
      const options = [...this.$refs.options.querySelectorAll('[role=option]')];
      const current = options.indexOf(event.currentTarget);
      const next = Math.min(options.length - 1, Math.max(0, current + direction));
      options[next]?.focus();
    }
  }"
  x-on:click.outside="open = false"
  x-on:keydown.escape.window="if (open) { open = false; $refs.trigger.focus(); }"
>
  <input type="hidden" name="{{ $name }}" :value="selected">

  <button
    x-ref="trigger"
    type="button"
    class="flex h-12 w-full items-center justify-between gap-4 rounded-full border border-ink/15 bg-paper px-5 text-left text-base font-medium text-ink shadow-[0_8px_24px_rgba(40,31,25,.06)] transition hover:border-accent/60 hover:shadow-[0_12px_30px_rgba(40,31,25,.1)] focus:border-accent focus:ring-2 focus:ring-accent/10"
    x-on:click="toggle()"
    :aria-expanded="open"
    aria-haspopup="listbox"
    aria-controls="{{ $name }}-options"
  >
    <span x-text="selectedLabel"></span>
    <svg class="h-4 w-4 shrink-0 text-accent transition-transform duration-200" :class="open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg>
  </button>

  <div
    id="{{ $name }}-options"
    x-ref="options"
    x-show="open"
    x-cloak
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="translate-y-1 opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-1 opacity-0"
    role="listbox"
    class="absolute inset-x-0 top-full z-40 mt-3 max-h-80 overflow-y-auto rounded-[1.35rem] border border-ink/10 bg-[#fffdf9] p-2 shadow-[0_24px_60px_rgba(40,31,25,.18)]"
  >
    <template x-for="(item, index) in items" :key="item.value">
      <button
        type="button"
        role="option"
        :aria-selected="selected === item.value"
        x-on:click="choose(item.value)"
        x-on:keydown.arrow-down.prevent="moveFocus($event, 1)"
        x-on:keydown.arrow-up.prevent="moveFocus($event, -1)"
        class="flex w-full items-center justify-between gap-4 rounded-[1rem] px-4 py-3 text-left text-base font-medium transition"
        :class="selected === item.value ? 'bg-accent text-paper' : 'text-ink hover:bg-mist/65'"
      >
        <span x-text="item.label"></span>
        <span class="grid h-5 w-5 place-items-center rounded-full border" :class="selected === item.value ? 'border-paper/50 bg-paper/15' : 'border-ink/15'">
          <svg x-show="selected === item.value" class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg>
        </span>
      </button>
    </template>
  </div>
</div>
