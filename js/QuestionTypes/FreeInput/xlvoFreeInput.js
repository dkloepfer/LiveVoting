/**
 * Class xlvoFreeInput
 * @type {{}}
 */
var xlvoFreeInput = {
    init: function (json) {
        var config = JSON.parse(json);
        var replacer = new RegExp('amp;', 'g');
        config.base_url = config.base_url.replace(replacer, '');
        this.config = config;
        this.ready = true;
    },
    config: {},
    base_url: '',
    run: function () {
    }
};
