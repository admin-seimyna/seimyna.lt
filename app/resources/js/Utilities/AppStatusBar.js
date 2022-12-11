export default class AppStatusBar {
    defaultColor; // default status bar color
    style; // default status bar style

    constructor(color, style) {
        this.defaultColor = color;
        this.style = style;
    }

    /**
     * Change color to default
     */
    setDefaultColor() {
        this.setColor(this.defaultColor);
        this.setStyle(this.style);
    }

    /**
     * Set status bar style
     * @param style
     */
    setStyle(style) {
        if (typeof StatusBar === 'undefined') return;
        switch (style) {
            case 'lightcontent': StatusBar.styleLightContent(); break;
            case 'blacktranslucent': StatusBar.styleBlackTranslucent(); break;
            case 'blackopaque': StatusBar.styleBlackOpaque(); break;
        }
    }

    /**
     * Set status bar color
     * @param color
     */
    setColor(color) {
        if (typeof StatusBar === 'undefined') return;
        StatusBar.backgroundColorByHexString(color);
        StatusBar.styleDefault();
    }

    /**
     * Hide status bar
     */
    hide() {
        if (typeof StatusBar === 'undefined') return;
        StatusBar.hide();
    }

    /**
     * Show status bar
     */
    show() {
        if (typeof StatusBar === 'undefined') return;
        StatusBar.show();
    }

    /**
     * Set overlay
     */
    overlay(status) {
        if (typeof StatusBar === 'undefined') return;
        StatusBar.overlaysWebView(status);
    }
}
