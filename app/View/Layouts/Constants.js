Constants = (function() {
    this.rankList = ['Customer', 'Representative', 'Bronze', 'Silver', 'Gold'];
    return {
        qualifiedGain: 200,
        userTypeCustomer: "Customer",
        userTypeRepresentative: "Representative",
        rankList: rankList,
        rank: function(rankName){
            return this.rankList.indexOf(rankName);
        }
    }
})();