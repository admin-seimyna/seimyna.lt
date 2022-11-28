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
        StatusBar.backgroundColorByHexString(color);
        StatusBar.styleDefault();
    }

    /**
     * Hide status bar
     */
    hide() {
        StatusBar.hide();
    }

    /**
     * Show status bar
     */
    show() {
        StatusBar.show();
    }

    /**
     * Set overlay
     */
    overlay(status) {
        StatusBar.overlaysWebView(status);
    }
}
