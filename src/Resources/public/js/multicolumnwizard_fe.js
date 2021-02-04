/**
 * This file is part of contao-community-alliance/contao-multicolumnwizard-frontend-bundle.
 *
 * (c) 2020-2021 Contao Community Alliance.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/contao-multicolumnwizard-frontend
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2020-2021 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/contao-multicolumnwizard-frontend-bundle/blob/master/LICENSE
 *             LGPL-3.0-or-later
 * @filesource
 * @info       compress with https://prepros.io/
 */

(function() {
    this.MultiColmTableName = function() {
        this.minRowCount = 0;
        this.maxRowCount = 0;
        this.selector = '';
        this.eventArr = [];

        // Define option defaults
        let defaults = {
            selector: '',
            minRow  : 0,
            maxRow  : 0,
        };

        if (arguments[0] && typeof arguments[0] === 'object') {
            this.options = extendDefaults(defaults, arguments[0]);
        }

        this.selector = this.options.selector;
        this.minRowCount = this.options.minRow;
        this.maxRowCount = this.options.maxRow;

        let mcwTable = document.querySelector('#' + this.selector + ' tbody');
        new Sortable(mcwTable, {
            handle   : '.op-move', // handle's class
            animation: 150,
        });

        initializeEvents(this);
        checkMaxMinRow(this);
    };

    MultiColmTableName.prototype.add = function(e) {
        e.preventDefault();

        let _current_node = e.target.parentNode;

        _current_node.classList.add('rotate');

        let fieldName = document.querySelector('#' + this.selector).getAttribute('data-name');
        let rows = document.querySelectorAll('#' + this.selector + ' tbody tr');
        let _ = this;

        if (_.maxRowCount == 0 || (_.maxRowCount > 0 && rows.length < _.maxRowCount)) {
            let maxRowId = 0;

            for (let i = 0; i < rows.length; i++) {
                maxRowId = Math.max(maxRowId, (rows[i].getAttribute('data-rowid')));
            }

            let params = 'action=mcwCreateNewRow&name=' + fieldName + '&maxRowId=' + maxRowId;
            let xhr = new XMLHttpRequest();
            xhr.open('POST', window.location.href);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    let text = xhr.responseText;
                    let json;

                    // Support both plain text and JSON responses
                    try {
                        json = JSON.parse(text);
                    } catch (e) {
                        json = {'content': text};
                    }

                    // Empty response
                    if (json === null) {
                        json = {'content': ''};
                    } else {
                        if (typeof (json) != 'object') {
                            json = {'content': text};
                        }
                    }

                    let _html = json.content.replace('<table>', '').replace('</table>', '');

                    function htmlToElement(html)
                    {
                        let template = document.createElement('template');
                        html = html.trim(); // Never return a text node of whitespace as the result
                        template.innerHTML = html;

                        return template.content.firstChild;
                    }

                    e.target.parentNode.parentNode.parentNode.after(htmlToElement(_html));

                    // Rebind the events.
                    removeEvents(_);
                    initializeEvents(_);
                    _current_node.classList.remove('rotate');
                    checkMaxMinRow(_);

                } else {
                    if (xhr.status !== 200) {
                        console.log(xhr.status);
                        _current_node.classList.remove('rotate');
                    }
                }
            };
            xhr.send(encodeURI(params));
            return;
        } else {
            _current_node.classList.remove('rotate');
            checkMaxMinRow(_);
            return false;
        }
    };

    MultiColmTableName.prototype.delete = function(e) {
        e.preventDefault();

        let rows = document.querySelectorAll('#' + this.selector + ' tbody tr');

        if (rows.length == 1) {
            return;
        }

        if (this.minRowCount > 0 && rows.length <= this.minRowCount) {
            return;
        } else {
            e.target.parentNode.parentNode.parentNode.remove();
            checkMaxMinRow(this);

            return;
        }
    };

    // Utility method to extend defaults with user options
    function extendDefaults(source, properties)
    {
        let property;
        for (property in properties) {
            if (properties.hasOwnProperty(property)) {
                source[property] = properties[property];
            }
        }
        return source;
    }

    function initializeEvents(_prop)
    {
        let _ = _prop;
        let _actionElems = document.querySelectorAll('#' + _.selector + ' tbody > tr > td > a');

        //bind click event
        for (let i = 0; i < _actionElems.length; i++) {
            let _action = _actionElems[i].getAttribute('data-operations');

            let eventObj = {
                addEvent   : null,
                deleteEvent: null,
                refElem    : null,
            }

            if (_action === 'new') {
                let __add = _.add.bind(_);
                eventObj.addEvent = __add;
                eventObj.refElem = _actionElems[i];
                _actionElems[i].addEventListener('click', __add);
            }

            if (_action === 'delete') {
                let __delete = _.delete.bind(_);
                eventObj.deleteEvent = __delete;
                _actionElems[i].addEventListener('click', __delete);
            }

            if (eventObj.refElem != null) {
                _.eventArr.push(eventObj);
            }
        }
    }

    function removeEvents(_prop)
    {
        let _ = _prop;

        for (let i = 0; i < _.eventArr.length; i++) {
            _.eventArr[i].refElem.removeEventListener('click', _.eventArr[i].addEvent);
            _.eventArr[i].refElem.removeEventListener('click', _.eventArr[i].deleteEvent);
        }
    }

    function checkMaxMinRow(_prop) {
        let _ = _prop;
        let rows = document.querySelectorAll('#' + _.selector + ' tbody tr');
        let _actionElems = document.querySelectorAll('#' + _.selector + ' tbody > tr > td > a');

        var _maxRowCount = _.maxRowCount;
        var _minRowCount = _.minRowCount;

        _actionElems.forEach(function(elem){
            var _action = elem.getAttribute('data-operations');

            if(_action != undefined && _action.length != 0) {
                if(_action.trim() == 'new') {
                    if (_maxRowCount == rows.length) {
                        elem.classList.add('disabled');
                    } else {
                        elem.classList.remove('disabled');
                    }
                }

                if(_action.trim() == 'delete') {
                    if (_minRowCount == rows.length) {
                        elem.classList.add('disabled');
                    } else {
                        elem.classList.remove('disabled');
                    }
                }
            }
        });
    };
}());
