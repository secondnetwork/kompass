Alpine.store('showside', {
  on: false,
  toggle() {
      this.on = ! this.on
  }
})

Alpine.start()
