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
        },

        onKeydown(e) {
            if ((e.key === 'Enter' || e.key === ' ') && config.dayClickEnabled) {
                e.preventDefault();
                this.$wire.onDayClick(config.year, config.month, config.day);
                return;
            }

            const moves = { ArrowLeft: -1, ArrowRight: 1, ArrowUp: -7, ArrowDown: 7 };
            if (moves[e.key]) {
                e.preventDefault();
                const grid = this.$el.closest('[role=grid]');
                const cells = [...grid.querySelectorAll('[role=gridcell]')];
                const idx = cells.indexOf(this.$el);
                const target = cells[idx + moves[e.key]];
                if (target) target.focus();
            }
        }
    }));
}

if (window.Alpine) {
    livewireCalendarRegisterAlpine();
} else {
    document.addEventListener('alpine:init', livewireCalendarRegisterAlpine);
}
