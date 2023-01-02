export default class Bus {
    _events = [];

    /**
     * Register event callback
     * @param name
     * @param callback
     */
    on(name, callback) {
        if (typeof this._events[name] === 'undefined') {
            this._events[name] = [];
        }

        this._events[name].push(callback);
    }

    /**
     * Emit event callbacks
     * @param name
     */
    emit(name) {
        if (typeof this._events[name] === 'undefined') {
            console.error(`[bus:emit] event "${name}" not exists`);
            return;
        }

        const args = Array.prototype.slice.call(arguments);
        args.shift();
        this._events[name].forEach((callback) => {
            callback(...args);
        });
    }

    /**
     * Off all event or just one callback
     * @param name
     * @param callback
     */
    off(name, callback) {
        if (typeof this._events[name] === 'undefined') {
            console.error(`[bus:off] event "${name}" not exists`);
            return;
        }

        if (callback) {
            const index = this._events[name].indexOf(callback);
            if (index === -1) return;
            this._events[name].splice(index, 1);
        } else {
            delete this._events[name];
        }
    }
}
