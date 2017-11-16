/*!
 * Knockout Extensions
 */

// make observables be incremented with custom or default values
ko.observable.fn.increment = function (incValue) {
    this(this() + (incValue || 1));
};