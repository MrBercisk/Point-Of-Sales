<button
    x-data="{
        isFullscreen: false,

        init() {
            this.isFullscreen = localStorage.getItem('fullscreen') === 'true';

            document.addEventListener('fullscreenchange', () => {
                this.isFullscreen = !!document.fullscreenElement;
                localStorage.setItem('fullscreen', this.isFullscreen);
            });
        },

        toggle() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        }
    }"
    x-init="init()"
    x-on:click="toggle()"
    class="fi-icon-btn p-2 mr-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"
>
    <span x-show="!isFullscreen">⛶</span>
    <span x-show="isFullscreen">🡼</span>
</button>