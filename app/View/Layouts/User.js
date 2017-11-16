function User(name, date, type){
        self = this;
        self.type = ko.observable(type || Constants.userTypeCustomer);
        self.paidAsRank = ko.observable(self.type() == Constants.userTypeRepresentative ? 1 : 0);
        self.paidAsRankStr = ko.computed(() => Constants.rankList[this.paidAsRank()]);
        self.periodHistory = ko.observableArray([]);

        self.currentRank = ko.observable(this.paidAsRank());

        self.currentRankStr = ko.computed(() => Constants.rankList[this.currentRank()]);
        self.name = name;
        self.day = date.day;
        self.month = date.month;
        self.year = date.year;
        self.purchases = ko.observableArray([]);

        self.starterKitPurchased = ko.observable();
        
        self.active = ko.computed(() =>
            // todo dates logic
            this.starterKitPurchased() != null
            //this.starterKitPurchased().date >= currentDate.AddYear(-1) 
        );
        
        self.personalPurchaseSalesVolume = ko.computed(() => {
            let sum = 0;
            this.purchases().forEach(function(purchase) {
                sum += purchase.cost();
            });
            return sum;
        });

        self.childs = ko.observableArray([]);

        self.isCustomer = function(){
            return this.type() == Constants.userTypeCustomer;
        }
        self.isRepresentative = function(){
            return !this.isCustomer();
        }
        self.isQualified=function(){
            if(this.isCustomer()){
                return this.personalPurchaseSalesVolume() >= Constants.qualifiedGain;
            }
            else {
                return this.personalSalesVolume() >= Constants.qualifiedGain;
            }
        }
        self.purchase=function(cost, dateInfo){
            this.purchases.push(new Purchase(parseInt(cost), dateInfo.day, dateInfo.month, dateInfo.year));
        }
        self.purchaseStarterKit=function(dateInfo){
            if(this.isCustomer()){
                this.type(Constants.userTypeRepresentative);
                this.paidAsRank(1);
                this.recalculateRank();
            }
            this.starterKitPurchased(dateInfo);
        }
        self.purchase=function(cost, dateInfo){
            this.purchases.push(new Purchase(parseInt(cost), dateInfo.day, dateInfo.month, dateInfo.year));
        }
        /* Representative methods */
        self.addUser=function(name, isCustomer, dateInfo){
            if(!!isCustomer){
                var user = new User(name, dateInfo);
                this.childs.push(user);
            } else {
                var user = new User(name, dateInfo, Constants.userTypeRepresentative);
                user.purchaseStarterKit(dateInfo);
                this.childs.push(user);
            }
        }
        self.personalSalesVolume=function() {
            return this.personalPurchaseSalesVolume() + this.personalCustomerSalesVolume();
        }
        self.customers = function(){
            return ko.utils.arrayFilter(this.childs(), function(child) {
                return child.isCustomer()
            });
        }
        self.representatives = function(){
            return ko.utils.arrayFilter(this.childs(), function(child) {
                return child.isRepresentative()
            });
        }
        self.personalCustomerSalesVolume=function(){
            let sum = 0;
            this.customers().forEach(function(customer) {
                sum += customer.personalPurchaseSalesVolume();
            });
            return sum;
        }
        self.downlineSalesVolume=function(){
            let representativesPersonalSalesVolume = 0
            this.representatives().forEach(function(representative) {
                representativesPersonalSalesVolume += representative.downlineSalesVolume();
            });
            return this.personalSalesVolume() + representativesPersonalSalesVolume;
        }
        // get adjustedDownlineSalesVolume(){
        //     return 0;
        // }
        self.level1SalesVolume=function(){
            let sum = 0;
            this.childs().forEach(function(child) {
                sum += child.personalPurchaseSalesVolume();
            });
            return sum;
        }
        // get level130DaySalesVolume(){
        //     let customersPersonalPurchaseSalesVolume = 0;
        //     for(let customer in this.customers) {
        //         if (customer.day > 30)
        //             continue;
        //         customersPersonalPurchaseSalesVolume += customer.personalPurchaseSalesVolume;
        //     }
        //     let representativesPersonalPurchaseSalesVolume = 0;
        //     for(let representative in this.representatves) {
        //         if (representative.day > 30)
        //             continue;
        //         representativesPersonalPurchaseSalesVolume += representative.personalPurchaseSalesVolume;
        //     }
        //     return customersPersonalPurchaseSalesVolume + representativesPersonalPurchaseSalesVolume;
        // }
        self.calculatePeriodResult=function(){
            if(this.isCustomer()){
                this.paidAsRank(Constants.rank('Customer'));
            }
            if(this.isRepresentative()){
                this.paidAsRank(Constants.rank('Representative'));
            }

            if(this.childs().length){
                var legs = [];
                this.childs().forEach(function(child) {
                    child.calculatePeriodResult();

                    var leg = {
                        isQualified: child.isQualified(),
                        paidAsRank: child.paidAsRank()
                    };
                    this.push(leg);
                }, legs);
                
                // check if BRONZE rank is reached
                var bronzeRankReached = true;
                bronzeRankReached &= this.isRepresentative();
                bronzeRankReached &= this.active();
                bronzeRankReached &= this.personalSalesVolume() >= 200; // similar to be qualfied: this.isQualified()
                bronzeRankReached &= legs.filter(l => l.isQualified).length >= 1; // have 1 qualified leg
                if(bronzeRankReached){
                    this.paidAsRank(Constants.rank('Bronze'));
                }

                // check if SILVER rank is reached
                var silverRankReached = true;
                silverRankReached &= bronzeRankReached; // if rep reache BRONZE in this month he coul reach SILVER too
                silverRankReached &= this.active();
                silverRankReached &= this.personalSalesVolume() >= 200; // similar to be qualfied: this.isQualified()
                silverRankReached &= legs.filter(l => l.isQualified).length >= 2; // have 2 qualified legs
                silverRankReached &= this.downlineSalesVolume() >= 1000;
                if(silverRankReached){
                    this.paidAsRank(Constants.rank('Silver'));
                }

                // check if GOLD rank is reached
                var goldRankReached = true;
                goldRankReached &= silverRankReached; // if rep reache SILVER in this month he coul reach GOLD too
                goldRankReached &= this.active();
                goldRankReached &= this.personalSalesVolume() >= 200; // similar to be qualfied: this.isQualified()
                goldRankReached &= legs.filter(l => l.isQualified).length >= 3; // have 3 qualified legs
                goldRankReached &= legs.filter(l => l.isQualified && l.paidAsRank >= Constants.rank('Bronze')).length >= 1; // one of which is a paid-as Bronze or higher leg
                goldRankReached &= this.downlineSalesVolume() >= 3000;
                if(goldRankReached){
                    this.paidAsRank(Constants.rank('Gold'));
                }

                

            }
        }
        
        self.recalculateRank = function() {
            if(this.periodHistory().length == 0){
                self.currentRank(this.paidAsRank());
                return;
            }
            // check if there was rank failure - this needed if rank should be lower then current when failed in last 6 months
            //var rankWasFailedInLast6Months = this.periodHistory().filter(r => r.rankFailed).length > 0;
            // by default we use just highest paid as rank for last 6 months
            // get last 6 entries
            var startPos = this.periodHistory().length <= 6 ? 0 : this.periodHistory().length - 6;
            var last6MonthsRankList = this.periodHistory().slice(startPos).map(h => h.paidAsRank);
            // sort them to get highest paid as rank
            last6MonthsRankList = last6MonthsRankList.sort();
            // highest value is the current rank now
            var highestRank = last6MonthsRankList[last6MonthsRankList.length - 1];
            this.currentRank(highestRank);
        };

        self.savePeriodResult = function(dateInfo){
            if(this.childs().length){
                this.childs().forEach(function(child) {
                    child.savePeriodResult();
                });
            }
            // save period result history
            var periodInfo = new UserPeriodResult();
            periodInfo.setPaidAsRank(this.paidAsRank());
            // check if current rank is failed
            var currentRankFailed = this.paidAsRank() < this.currentRank();
            periodInfo.setRankFailed(currentRankFailed);
            periodInfo.setPeriodInfo(dateInfo);
            this.periodHistory.push(periodInfo);
            //recalculate current rank
            this.recalculateRank();
            // clear current period purchases
            this.purchases([]);
        }
        /* END of Representative methods*/
}