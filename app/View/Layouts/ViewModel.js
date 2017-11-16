function ViewModel() {
        this.userId = 1;

        this.year = ko.observable(2017);
        this.months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        this.month = ko.observable(0);
        this.day = ko.observable(0);
    

        this.hierarchy = ko.observableArray();


    this.currentDateInfo=function(){
        return { day: this.day(), month: this.month(), year: this.year() };
    }

    this.nextDay = function(){
        if(this.day() < 31 - 1)
            this.day.increment();
        else{
            this.day(0);
            this.nextMonth();
        }
    }

    this.nextMonth = function(){
        if(this.month() < this.months.length - 1)
            this.month.increment();
        else{
            this.month(0);
            this.nextYear();
        }
    }

    this.nextYear = function(){
        this.year.increment();
    }

    self.calculate = function(){
        this.hierarchy().forEach(function(user) {
            user.calculatePeriodResult();
        });
    }

    self.closePeriod=function() {
        // save results
        this.hierarchy().forEach(function(user) {
            user.savePeriodResult(this);
        }, this.currentDateInfo());
        // go to new period
        this.day(0);
        this.nextMonth();
    }

    this.generateUserId=function(){
        return this.userId++;
    }

    this.generateUserName=function(userName){
        return (userName || "User") + this.generateUserId();
    }

    this.addUser=function(isCustomer){
        var user = !!isCustomer
            ? new User(this.generateUserName("Customer"), this.currentDateInfo())
            : new User(this.generateUserName("Representative"), this.currentDateInfo(), Constants.userTypeRepresentative);
        this.hierarchy.push(user);
    }
}