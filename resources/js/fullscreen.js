document.addEventListener('alpine:init', () => {
    Alpine.data('fullscreenToggle', () => ({
        isFullscreen: false,

        init() {
            this.isFullscreen = !!document.fullscreenElement;

            document.addEventListener('fullscreenchange', () => {
                this.isFullscreen = !!document.fullscreenElement;
            });
        },

        toggle() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        },
    }));
});