const fs = require('fs');
const path = require('path');



class in18Compiler {
    languages;
    path;

    constructor(languages = ['en'], path) {
        this.languages = languages;
        this.path = path;
    }

    apply(compiler) {
        compiler.hooks.done.tap('in18Compiler', () => {
            const output = {};
            this.languages.forEach((lang) => {
                output[lang] = {};
                fs.readdirSync(path.join(__dirname, this.path + lang)).forEach((file) => {
                    const key = file.replace('.json', '');
                    output[lang][key] = require(this.path + lang + '/' + file);
                });
                fs.writeFileSync(path.join(__dirname, this.path + '/messages.json'), JSON.stringify(output));
            });
        });
    }
}

module.exports = in18Compiler;
