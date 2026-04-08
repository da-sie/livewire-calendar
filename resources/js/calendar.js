function livewireCalendarRegisterAlpine() {
    Alpine.data('livewireCalendarDay', (config) => ({
        dragOver: false,

        onDragEnter(e) {
            e.preventDefault();
            this.dragOver = true;
        },

        onDragLeave(e) {
            e.preventDefault();
            this.dragOver = false;
        },

        onDrop(e) {
            e.preventDefault();
            this.dragOver = false;
            const eventId = e.dataTransfer.getData('id');
            this.$wire.onEventDropped(eventId, config.year, config.month, config.day);
        }
    }));
}

if (window.Alpine) {
    livewireCalendarRegisterAlpine();
} else {
    document.addEventListener('alpine:init', livewireCalendarRegisterAlpine);
}
