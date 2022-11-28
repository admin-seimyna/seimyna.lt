export default class Dialog {
    constructor() {
    }

    /**
     * Alert
     * @param title
     * @param message
     * @param buttonText
     * @return {Promise<unknown>}
     */
    alert(title, message, buttonText) {
        return new Promise((resolve, reject) => {
            navigator.notification.alert(
                message,
                resolve,
                title,
                buttonText
            );
        });

    }

    /**
     * Propt
     * @param title
     * @param message
     * @param okButtonText
     * @param cancelButtonText
     * @return {Promise<unknown>}
     */
    prompt(title, message, okButtonText, cancelButtonText) {
        return new Promise((resolve, reject) => {
            navigator.notification.prompt(
                message,
                resolve,
                title,
                [okButtonText, cancelButtonText]
            );
        });
    }

    /**
     * Beep
     * @param times
     */
    beep(times = 1) {
        navigator.notification.beep(times);
    }
}