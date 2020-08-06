'use strict';

exports.__esModule = true;

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _domCreateElementQuerySelector = require('dom-create-element-query-selector');

var _domCreateElementQuerySelector2 = _interopRequireDefault(_domCreateElementQuerySelector);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

exports.default = function () {
    for (var _len = arguments.length, params = Array(_len), _key = 0; _key < _len; _key++) {
        params[_key] = arguments[_key];
    }

    return new IsLoading(params);
};

var formElements = ['form', 'input', 'textarea', 'label', 'fieldset', 'select', 'button'];

var optionsDefault = {
    'type': 'switch', // switch | replace | full-overlay | overlay
    'text': 'loading', // Text to display in the loader
    'disableSource': true, // true | false
    'disableList': []
};

var IsLoading = function () {
    function IsLoading(params) {
        _classCallCheck(this, IsLoading);

        var options = {};
        if (params.length === 0 || params.length === 1 && !params[0].nodeType) {
            this._target = null;
            options = _extends({}, params[0], { type: 'full-overlay' });
        } else {
            this._target = params[0];
            options = params[1];
        }
        this._options = _extends({}, optionsDefault, options);
        this._fullOverlayId = 'is-loading-full-overlay';
    }

    IsLoading.prototype.loading = function loading() {
        switch (this._options.type) {
            case 'replace':
                this._onReplaceType();break;
            case 'full-overlay':
                this._onFullOverlayType();break;
            case 'overlay':
                this._onElementOverlayType();break;
            default:
                this._onSwitchType();break;
        }
    };

    IsLoading.prototype.restoreContent = function restoreContent() {
        var content = this._target.getAttribute('data-is-loading-content');
        if (this.isTargetValue) {
            this._target.value = content;
        } else {
            this._target.textContent = content;
        }
    };

    IsLoading.prototype._onSwitchType = function _onSwitchType() {
        this._toggleElements(false);
        this._target.setAttribute('data-is-loading-content', this.targetContent);
        this.targetContent = this._options.text;
    };

    IsLoading.prototype._onReplaceType = function _onReplaceType() {
        this._toggleElements(false);
        this._target.setAttribute('data-is-loading-content', this.targetContent);
        this._target.innerHTML = '';
        this._target.appendChild((0, _domCreateElementQuerySelector2.default)('span.is-loading.is-loading-target', this._options.text));
    };

    IsLoading.prototype._onElementOverlayType = function _onElementOverlayType() {
        this._toggleElements(false);
        var overlayWrapperClass = '.is-loading-element-overlay';

        if (this._prop('position') === 'static') {
            this._target.setAttribute('data-is-loading-position', 'static');
            this._target.classList.add('is-loading-element-overlay-target');
        }

        if (!this._target.querySelector(overlayWrapperClass)) {
            var overlay = (0, _domCreateElementQuerySelector2.default)(overlayWrapperClass, (0, _domCreateElementQuerySelector2.default)('.is-loading-text-wrapper', this._options.text));
            overlay.style.borderRadius = this._prop('border-radius');
            this._target.appendChild(overlay);
        }
    };

    IsLoading.prototype._onFullOverlayType = function _onFullOverlayType() {
        this._toggleElements(false);
        this._showFullOverlay();
    };

    IsLoading.prototype._showFullOverlay = function _showFullOverlay() {
        var overlay = document.querySelector(this._fullOverlayId);

        if (!overlay) {
            overlay = (0, _domCreateElementQuerySelector2.default)('#' + this._fullOverlayId, (0, _domCreateElementQuerySelector2.default)('.is-loading-text-wrapper', this._options.text));
            document.querySelector('body').appendChild(overlay);
        }
    };

    IsLoading.prototype._prop = function _prop(prop) {
        return window.getComputedStyle(this._target).getPropertyValue(prop);
    };

    IsLoading.prototype._toggleElements = function _toggleElements() {
        var status = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;

        var list = [].concat(this._options.disableList);
        if (this._target && this._options.disableSource === true) {
            list.unshift(this._target);
        }
        list.forEach(function (item) {
            if (formElements.includes(item.tagName.toLowerCase())) {
                if (status === true) {
                    item.removeAttribute('disabled');
                } else {
                    item.setAttribute('disabled', 'disabled');
                }
            }
            if (status === true) {
                item.classList.remove('disabled');
            } else {
                item.classList.add('disabled');
            }
        });
    };

    IsLoading.prototype.remove = function remove() {
        this._toggleElements(true);
        if (this._options.type === 'switch') {
            this.restoreContent();
        }
        if (this._target) {
            this._target.removeAttribute('data-is-loading-content');
        }
        if (this._options.type === 'full-overlay') {
            var overlay = document.getElementById(this._fullOverlayId);
            if (overlay) {
                document.querySelector('body').removeChild(overlay);
            }
        }
        if (this._target && this._target.getAttribute('data-is-loading-position')) {
            this._target.classList.remove('is-loading-element-overlay-target');
        }
    };

    _createClass(IsLoading, [{
        key: 'targetContent',
        get: function get() {
            if (this.isTargetValue) {
                return this._target.value;
            } else {
                return this._target.textContent;
            }
        },
        set: function set(val) {
            if (this.isTargetValue) {
                this._target.value = val;
            } else {
                this._target.textContent = val;
            }
        }
    }, {
        key: 'isTargetValue',
        get: function get() {
            var node = this._target.nodeName.toLowerCase();
            var type = this._target.attributes.type;

            return node === 'input' && type && (type.value.toLowerCase() === 'button' || type.value.toLowerCase() === 'submit');
        }
    }]);

    return IsLoading;
}();

module.exports = exports['default'];