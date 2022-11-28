export default class Notification {
    /**
     * Get fcm token
     * @returns {Promise<unknown>}
     */
    getToken() {
        return new Promise((resolve, reject) => {
            cordova.plugins.firebase.messaging.getToken().then(resolve);
        });
    }

    /**
     * Registers foreground push notification callback.
     * @return {Promise<number>}
     */
    onMessage(callback) {
        return new Promise((resolve, reject) => {
            cordova.plugins.firebase.messaging.onMessage(resolve);
        });
    }

    /**
     * Registers background push notification callback.
     * @return {Promise<number>}
     */
    onBackgroundMessage() {
        return new Promise((resolve, reject) => {
            cordova.plugins.firebase.messaging.onBackgroundMessage(resolve);
        });
    }

    /**
     * Gets current badge number (if supported).
     * @return {Promise<number>}
     */
    getBadge() {
        return cordova.plugins.firebase.messaging.getBadge()
    }

    /**
     * Ask for permission to recieve push notifications (will trigger prompt on iOS).
     * @param force
     * @return {Promise<unknown>}
     */
    permissionRequest(force = false) {
        return new Promise((resolve, reject) => {
            cordova.plugins.firebase.messaging.requestPermission({
                forceShow: force
            }).then(resolve).catch(reject);
        });
    }

    /**
     * Sets current badge number (if supported).
     * @param value
     */
    setBadge(value){
        cordova.plugins.firebase.messaging.setBadge(value);
    }

    /**
     * Clear all notifications from system notification bar.
     */
    clear() {
        cordova.plugins.firebase.messaging.clearNotifications();
    }
}