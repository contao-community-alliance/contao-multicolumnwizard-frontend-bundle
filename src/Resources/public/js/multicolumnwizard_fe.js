/**
 * This file is part of contao-community-alliance/contao-multicolumnwizard-frontend-bundle.
 *
 * (c) 2020 Contao Community Alliance.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/contao-multicolumnwizard-frontend
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2020 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/contao-multicolumnwizard-frontend-bundle/blob/master/LICENSE
 *             LGPL-3.0-or-later
 * @filesource
 * @info       compress with https://prepros.io/
 */

(function() {
    var _parent_table         = '';
    var _minRowCountMultiColm = 0;
    var _maxRowCountMultiColm = 0;

    var MultiColmTableName = function(selector, maxCount, minCount) {
        this.container = selector;
        _parent_table  = this.container;

        this.minRowCount = minCount;
        this.maxRowCount = maxCount;

        _minRowCountMultiColm = this.minRowCount;
        _maxRowCountMultiColm = this.maxRowCount;

        var mcwTable = document.querySelector('#' + this.container + ' tbody');
        new Sortable(mcwTable, {
            handle   : '.op-move', // handle's class
            animation: 150,
        });
    };

    // events binding
    MultiColmTableName.prototype._multicolmnBindEvents = function() {
        var _actionElems = document.querySelectorAll('#' + this.container + ' tbody > tr > td > a');

        //bind click event
        for (var i = 0; i < _actionElems.length; i++) {
            var _action = _actionElems[i].getAttribute('data-operations');

            if (_action === 'new') {
                _actionElems[i].addEventListener('click', _create_row_multicolmn);
            }

            if (_action === 'delete') {
                _actionElems[i].addEventListener('click', _delete_row_multicolmn);
            }
        }
    };

    // create new row functionality
    var _create_row_multicolmn = function(e) {
        var _current_node = this;

        _current_node.classList.add('rotate');

        var fieldName = document.querySelector('#' + _parent_table).getAttribute('data-name');
        var rows      = document.querySelectorAll('#' + _parent_table + ' tbody tr');

        if (_maxRowCountMultiColm == 0 || (_maxRowCountMultiColm > 0 && rows.length < _maxRowCountMultiColm)) {
            var maxRowId = 0;

            for (var i = 0; i < rows.length; i++) {
                maxRowId = Math.max(maxRowId, (rows[i].getAttribute('data-rowid')));
            }

            var params = 'action=mcwCreateNewRow&name=' + fieldName + '&maxRowId=' + maxRowId;
            var xhr    = new XMLHttpRequest();
            xhr.open('POST', window.location.href);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var text = xhr.responseText;
                    var json;

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

                    // Text to html.
                    //var _body_contents = _multiColmTable.querySelector('tbody').innerHTML;
                    //var _new_row       = document.querySelector('#'+_parent_table+' tbody').insertAdjacentHTML('beforeend', (json.content.replace('<table>', '').replace('</table>', '')));
                    //this.parentElement.parentElement.after((json.content.replace('<table>', '').replace('</table>', '')));
                    //e.target.parentNode.parentNode.parentNode.after((json.content.replace('<table>', '').replace('</table>', '')));

                    var _html = json.content.replace('<table>', '').replace('</table>', '');

                    function htmlToElement(html)
                    {
                        var template       = document.createElement('template');
                        html               = html.trim(); // Never return a text node of whitespace as the result
                        template.innerHTML = html;

                        return template.content.firstChild;
                    }

                    e.target.parentNode.parentNode.parentNode.after(htmlToElement(_html));

                    // Rebind the events.
                    new MultiColmTableName(
                        _parent_table,
                        _maxRowCountMultiColm,
                        _minRowCountMultiColm,
                    )._multicolmnBindEvents();
                    _current_node.classList.remove('rotate');

                } else {
                    if (xhr.status !== 200) {
                        console.log(xhr.status);
                        _current_node.classList.remove('rotate');
                    }
                }
            };
            xhr.send(encodeURI(params));
        } else {
            return false;
        }
    };

    //deleting row functionality
    var _delete_row_multicolmn = function(e) {
        var rows = document.querySelectorAll('#' + _parent_table + ' tbody tr');

        if (rows.length == 1) {
            return;
        }

        if (_minRowCountMultiColm > 0 && rows.length <= _minRowCountMultiColm) {
            return;
        } else {
            this.parentElement.parentElement.remove();
            return;
        }
    };

    // export to global namespace
    window.MultiColmTableName = function(selector, maxCount, minCount) {
        return new MultiColmTableName(selector, maxCount, minCount);
    };

})();
