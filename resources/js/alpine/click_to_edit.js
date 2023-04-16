export default () => {
    return {

        isEditing: false,
        toggleEditingState() {
            this.isEditing = !this.isEditing;
        },
        disableEditing() {
        
            this.isEditing = false;
        },
 
    };
};