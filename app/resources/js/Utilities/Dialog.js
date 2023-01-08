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
     * Prompt
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
                [cancelButtonText, okButtonText]
            );
        });
    }

    /**
     * Logout prompt
     * @return {Promise<unknown>}
     */
    logout() {
        return new Promise((resolve, reject) => {
            this.confirm(
                this.t('auth.prompt.title.logout'),
                this.t('auth.prompt.message.logout'),
                this.t('common.button.yes'),
                this.t('common.button.no')
            ).then((result) => {
                result === 2 ? resolve() : reject();
            });
        });
    }

    /**
     * Confirm
     * @param title
     * @param message
     * @param okButtonText
     * @param cancelButtonText
     * @return {Promise<unknown>}
     */
    confirm(title, message, okButtonText, cancelButtonText) {
        return new Promise((resolve, reject) => {
            navigator.notification.confirm(
                message,
                resolve,
                title,
                [cancelButtonText, okButtonText]
            );
        });
    }

    /**
     * Default app confirmation
     * @param title
     * @param message
     * @return {Promise<unknown>}
     */
    defaultConfirm(title, message) {
        return new Promise((resolve, reject) => {
            this.confirm(
                title || this.t('common.confirmation.title.default'),
                message || this.t('common.confirmation.message.default'),
                this.t('common.button.yes'),
                this.t('common.button.no')
            ).then((result) => {
                result === 2 ? resolve() : reject();
            });
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
