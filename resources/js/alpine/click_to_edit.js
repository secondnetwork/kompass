export default (wireMethod = null) => {
    return {

        isEditing: false,
        wireMethod: wireMethod,
        
        toggleEditingState() {
            this.isEditing = !this.isEditing;

            if (this.isEditing) {
                this.$nextTick(() => {
                    this.$refs.input.focus();
                });
            }
        },
        
        disableEditing() {
            if (this.isEditing && this.wireMethod) {
                this.$wire.call(this.wireMethod);
            }
            this.isEditing = false;
        },
        
        handleClickAway() {
            if (this.isEditing) {
                this.disableEditing();
            }
        },

    };
};