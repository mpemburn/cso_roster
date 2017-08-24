/**
 * UrlQuery.js
 *
 * Get URL query variables or URL parts
 *
 * Usage:
 * var query = Object.create(UrlQuery); // Create instance
 * With no arg, getUrlPart gets part. Negative numbers get parts in reverse order
 * var last = query.getUrlPart();
 * Get variable from query string (e.g., ?my_var=foo)
 * var myVar = query.getVar('my_var');
 *
 */
var UrlQuery = {
    getVar: function (varName) {
        var vars = this.getUrlVars();
        return (vars[varName]);
    },
    getUrlVars: function () {
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for (var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    },
    getUrlPart: function(index) {
        index = index || - 1;
        var parts = window.location.href.split('/');
        return (index < 0) ? parts.slice(index)[0] : (parts[index]);
    }
}
