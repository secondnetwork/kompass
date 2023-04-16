import Alpine from 'alpinejs'

import collapse from '@alpinejs/collapse'

Alpine.plugin(collapse)

window.Alpine = Alpine;
document.addEventListener('alpine:init', () => {
    Alpine.store('accordion', {
      tab: 0
    });

    Alpine.data('accordion', (idx) => ({
      init() {
        this.idx = idx;
      },
      idx: -1,
      handleClick() {
        this.$store.accordion.tab = this.$store.accordion.tab === this.idx ? 0 : this.idx;
      },
      handleAc() {
        return this.$store.accordion.tab === this.idx ? 'bg-gray-200' : '';
      },
      handleRotate() {
        return this.$store.accordion.tab === this.idx ? 'rotate-180' : '';
      },
      handleToggle() {
        return this.$store.accordion.tab === this.idx ? `max-height: ${this.$refs.tab.scrollHeight}px` : '';
      }
    }));
  })
Alpine.start()