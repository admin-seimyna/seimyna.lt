export default class BackButton {
    events = []; // events list
    emitter; // function witch emits on backbutton event

    /**
     * Constructor
     */
    constructor() {
        this.emitter = this._emit.bind(this);
    }

    /**
     * Emit latest callback
     * @private
     */
    _emit() {
        const evt = this.events[this.events.length - 1];
        evt.callback();
        this.off(evt.key);
    }

    /**
     * Register callback
     * @param callback
     */
    on(key, callback) {
        if (!this.events.length) {
            document.addEventListener('backbutton', this.emitter, false);
        }
        this.events.push({
            key,
            callback
        });
    }

    /**
     * Unregister callback
     * @param callback
     */
    off(key) {
        const index = this.events.findIndex(evt => evt.key === key);
        if (index === -1) return;
        this.events.splice(index, 1);
        if (!this.events.length) {
            document.removeEventListener('backbutton', this.emitter);
        }
    }
}
