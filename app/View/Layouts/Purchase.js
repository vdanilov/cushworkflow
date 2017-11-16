function Purchase (cost, day, month, year) {
    this.cost = ko.observable(cost);
    this.day = day;
    this.month = month;
    this.year = year;
}