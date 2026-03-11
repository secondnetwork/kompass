import clickToEdit from './alpine/click_to_edit.js';

Alpine.store('showside', {
  on: false,
  toggle() {
      this.on = ! this.on
  }
})

Alpine.data('click_to_edit', clickToEdit);

Alpine.start()
