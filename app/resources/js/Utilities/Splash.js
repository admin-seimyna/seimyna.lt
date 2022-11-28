export default class Splash {
    /**
     * Hide splashscreen
     * @param delay
     */
    hide(delay = 0) {
        if (!navigator.splashscreen) return;
        setTimeout(() => navigator.splashscreen.hide(), delay);
    }

}