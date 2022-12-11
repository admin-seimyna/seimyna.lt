export default class Dialog {
    t;

    constructor(t) {
        this.t = t;
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
     * Default app alert
     * @param message
     * @return {Promise<*>}
     */
    defaultAlert(message)
    {
        return this.alert(
            this.t('common.title.alert'),
            message,
            this.t('common.button.close')
        );
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
