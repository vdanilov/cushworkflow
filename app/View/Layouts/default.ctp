<!doctype html>
    <head>
        <style>
            .bold {
                font-weight: bold;
            }
            .italic {
                font-style: italic;
            }
            .underline {
                text-decoration: underline;
            }
        </style>
        <script type='text/javascript' src='knockout-3.4.3.js'></script>
        <script type='text/javascript' src='knockout.extensions.js'></script>
        <script type='text/javascript' src='Constants.js'></script>
        <script type='text/javascript' src='Purchase.js'></script>
        <script type='text/javascript' src='User.js'></script>
        <script type='text/javascript' src='UserPeriodResult.js'></script>
        <script type='text/javascript' src='ViewModel.js'></script>
        <script>
            setTimeout(function(){
                var model = new ViewModel();
                ko.applyBindings(model);
            }, 10);
        </script>
    </head>
    <body>
        <div>
            <span>Current Date</span>
            <span class="bold" data-bind="text: day() + 1"></span>
            <span class="bold" data-bind="text: months[month()]"></span>
            <span class="bold" data-bind="text: year"></span>
            <button data-bind="click: nextDay">Next day</button>
            <button data-bind="click: calculate">Calculate</button>
            <button data-bind="click: closePeriod">Close Period</button>
        </div>
        <div>
            <button data-bind="click: addUser">Add User</button>
        </div>
        <div style="padding: 5px;">
            <!-- Top level -->
            <div data-bind="foreach: hierarchy">
                <div data-bind="style: { borderWidth: isRepresentative() ? '1px' : '1px' }" style="border: solid black; display: inline-block; padding: 5px; vertical-align: top;">
                    <span data-bind="text: name" class="bold"></span>
                    <button data-bind="visible: isCustomer() || !active(), text: isCustomer() ? 'Purchase Starter Kit' : 'Renew Subscription', click: function() { purchaseStarterKit($root.currentDateInfo()) }"></button>
                    <!-- Info -->
                    <div style="border-left: 1px solid black; padding-left: 5px;">
                        <div style="display: block;" data-bind="text: paidAsRankStr()+'('+currentRankStr()+')'" class="underline"></div>
                        <div data-bind="foreach: periodHistory">
                            <span data-bind="text: Constants.rankList[paidAsRank], style: { fontWeight: $index() == $parent.periodHistory().length - 1 ? 'bold' : 'normal' }"></span>
                        </div>
                        <div style="display: block;" data-bind="text: isQualified() ? 'Quilified' : 'Not qualified'" class="italic"></div>
                        <label class="italic">Personal Purchase Sales Volume </label>
                        <span data-bind="text: personalPurchaseSalesVolume"></span>
                        <div style="display: block;" data-bind="if: isRepresentative()">
                            <label class="italic">Personal Sales Volume  </label><span data-bind="text: personalSalesVolume()"></span>
                        </div>
                        <div style="display: block;" data-bind="if: isRepresentative()">
                            <label class="italic">Personal Customer Sales Volume  </label><span data-bind="text: personalCustomerSalesVolume()"></span>
                        </div>
                        <div style="display: block;" data-bind="if: isRepresentative()">
                            <label class="italic">Downline Sales Volume </label><span data-bind="text: downlineSalesVolume()"></span>
                        </div>
                        <div style="display: block;" data-bind="if: isRepresentative()">
                            <label class="italic">Level 1 Sales Volume </label><span data-bind="text: level1SalesVolume()"></span>
                        </div>
                    </div>
                    <!-- END of Info -->
                    <!-- Purchase -->
                    <div>
                        <!-- <input type="text"  /> -->
                        <span>Purchase</span>
                        <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">5</button>
                        <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">10</button>
                        <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">20</button>
                        <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">40</button>
                        <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">50</button>
                        <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">100</button>
                    </div>
                    <!-- END of Purchase -->
                    <!-- Build network -->
                    <div data-bind="if: isRepresentative()" style="margin:5px 0;">
                        <button data-bind="click: function() { addUser($root.generateUserName('Customer'), true, $root.currentDateInfo())}">Add Customer</button>
                        <button data-bind="click: function() { addUser($root.generateUserName('Representative'), false, $root.currentDateInfo())}">Add Representative</button>
                    </div>
                    <!-- END of Build network -->
                    <!-- First level -->
                    <div data-bind="foreach: childs">
                        <div data-bind="style: { borderWidth: isRepresentative() ? '1px' : '1px' }" style="border: solid black; display: inline-block; padding: 5px; vertical-align: top;">
                            <span data-bind="text: name" class="bold"></span>
                            <button data-bind="visible: isCustomer() || !active(), text: isCustomer() ? 'Purchase Starter Kit' : 'Renew Subscription', click: function() { purchaseStarterKit($root.currentDateInfo()) }"></button>
                            <!-- Info -->
                            <div style="border-left: 1px solid black; padding-left: 5px;">
                                <div style="display: block;" data-bind="text: paidAsRankStr" class="underline"></div>
                                <div data-bind="foreach: periodHistory">
                                    <span data-bind="text: Constants.rankList[paidAsRank], style: { fontWeight: $index() == $parent.periodHistory().length - 1 ? 'bold' : 'normal' }"></span>
                                </div>
                                <div style="display: block;" data-bind="text: isQualified() ? 'Quilified' : 'Not qualified'" class="italic"></div>
                                <label class="italic">Personal Purchase Sales Volume </label>
                                <span data-bind="text: personalPurchaseSalesVolume"></span>
                                <div style="display: block;" data-bind="if: isRepresentative()">
                                    <label class="italic">Personal Sales Volume  </label><span data-bind="text: personalSalesVolume()"></span>
                                </div>
                                <div style="display: block;" data-bind="if: isRepresentative()">
                                    <label class="italic">Personal Customer Sales Volume  </label><span data-bind="text: personalCustomerSalesVolume()"></span>
                                </div>
                                <div style="display: block;" data-bind="if: isRepresentative()">
                                    <label class="italic">Downline Sales Volume </label><span data-bind="text: downlineSalesVolume()"></span>
                                </div>
                                <div style="display: block;" data-bind="if: isRepresentative()">
                                    <label class="italic">Level 1 Sales Volume </label><span data-bind="text: level1SalesVolume()"></span>
                                </div>
                            </div>
                            <!-- END of Info -->
                            <!-- Purchase -->
                            <div>
                                <!-- <input type="text"  /> -->
                                <span>Purchase</span>
                                <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">5</button>
                                <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">10</button>
                                <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">20</button>
                                <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">40</button>
                                <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">50</button>
                                <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">100</button>
                            </div>
                            <!-- END of Purchase -->
                            <!-- Build network -->
                            <div data-bind="if: isRepresentative()" style="margin:5px 0;">
                                <button data-bind="click: function() { addUser($root.generateUserName('Customer'), true, $root.currentDateInfo())}">Add Customer</button>
                                <button data-bind="click: function() { addUser($root.generateUserName('Representative'), false, $root.currentDateInfo())}">Add Representative</button>
                            </div>
                            <!-- END of Build network -->
                            <!-- Second level -->
                            <div data-bind="foreach: childs">
                                <div data-bind="style: { borderWidth: isRepresentative() ? '1px' : '1px' }" style="border: solid black; display: inline-block; padding: 5px; vertical-align: top;">
                                    <span data-bind="text: name" class="bold"></span>
                                    <button data-bind="visible: isCustomer() || !active(), text: isCustomer() ? 'Purchase Starter Kit' : 'Renew Subscription', click: function() { purchaseStarterKit($root.currentDateInfo()) }"></button>
                                    <!-- Info -->
                                    <div style="border-left: 1px solid black; padding-left: 5px;">
                                        <div style="display: block;" data-bind="text: paidAsRankStr" class="underline"></div>
                                        <div data-bind="foreach: periodHistory">
                                            <span data-bind="text: Constants.rankList[paidAsRank], style: { fontWeight: $index() == $parent.periodHistory().length - 1 ? 'bold' : 'normal' }"></span>
                                        </div>
                                        <div style="display: block;" data-bind="text: isQualified() ? 'Quilified' : 'Not qualified'" class="italic"></div>
                                        <label class="italic">Personal Purchase Sales Volume </label>
                                        <span data-bind="text: personalPurchaseSalesVolume"></span>
                                        <div style="display: block;" data-bind="if: isRepresentative()">
                                            <label class="italic">Personal Sales Volume  </label><span data-bind="text: personalSalesVolume()"></span>
                                        </div>
                                        <div style="display: block;" data-bind="if: isRepresentative()">
                                            <label class="italic">Personal Customer Sales Volume  </label><span data-bind="text: personalCustomerSalesVolume()"></span>
                                        </div>
                                        <div style="display: block;" data-bind="if: isRepresentative()">
                                            <label class="italic">Downline Sales Volume </label><span data-bind="text: downlineSalesVolume()"></span>
                                        </div>
                                        <div style="display: block;" data-bind="if: isRepresentative()">
                                            <label class="italic">Level 1 Sales Volume </label><span data-bind="text: level1SalesVolume()"></span>
                                        </div>
                                    </div>
                                    <!-- END of Info -->
                                    <!-- Purchase -->
                                    <div>
                                        <!-- <input type="text"  /> -->
                                        <span>Purchase</span>
                                        <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">5</button>
                                        <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">10</button>
                                        <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">20</button>
                                        <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">40</button>
                                        <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">50</button>
                                        <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">100</button>
                                    </div>
                                    <!-- END of Purchase -->
                                    <!-- Build network -->
                                    <div data-bind="if: isRepresentative()" style="margin:5px 0;">
                                        <button data-bind="click: function() { addUser($root.generateUserName('Customer'), true, $root.currentDateInfo())}">Add Customer</button>
                                        <button data-bind="click: function() { addUser($root.generateUserName('Representative'), false, $root.currentDateInfo())}">Add Representative</button>
                                    </div>
                                    <!-- END of Build network -->
                                    <!-- Third level -->
                                    <div data-bind="foreach: childs">
                                        <div data-bind="style: { borderWidth: isRepresentative() ? '1px' : '1px' }" style="border: solid black; display: inline-block; padding: 5px; vertical-align: top;">
                                            <span data-bind="text: name" class="bold"></span>
                                            <button data-bind="visible: isCustomer() || !active(), text: isCustomer() ? 'Purchase Starter Kit' : 'Renew Subscription', click: function() { purchaseStarterKit($root.currentDateInfo()) }"></button>
                                            <!-- Info -->
                                            <div style="border-left: 1px solid black; padding-left: 5px;">
                                                <div style="display: block;" data-bind="text: paidAsRankStr" class="underline"></div>
                                                <div data-bind="foreach: periodHistory">
                                                    <span data-bind="text: Constants.rankList[paidAsRank], style: { fontWeight: $index() == $parent.periodHistory().length - 1 ? 'bold' : 'normal' }"></span>
                                                </div>
                                                <div style="display: block;" data-bind="text: isQualified() ? 'Quilified' : 'Not qualified'" class="italic"></div>
                                                <label class="italic">Personal Purchase Sales Volume </label>
                                                <span data-bind="text: personalPurchaseSalesVolume"></span>
                                                <div style="display: block;" data-bind="if: isRepresentative()">
                                                    <label class="italic">Personal Sales Volume  </label><span data-bind="text: personalSalesVolume()"></span>
                                                </div>
                                                <div style="display: block;" data-bind="if: isRepresentative()">
                                                    <label class="italic">Personal Customer Sales Volume  </label><span data-bind="text: personalCustomerSalesVolume()"></span>
                                                </div>
                                                <div style="display: block;" data-bind="if: isRepresentative()">
                                                    <label class="italic">Downline Sales Volume </label><span data-bind="text: downlineSalesVolume()"></span>
                                                </div>
                                                <div style="display: block;" data-bind="if: isRepresentative()">
                                                    <label class="italic">Level 1 Sales Volume </label><span data-bind="text: level1SalesVolume()"></span>
                                                </div>
                                            </div>
                                            <!-- END of Info -->
                                            <!-- Purchase -->
                                            <div>
                                                <!-- <input type="text"  /> -->
                                                <span>Purchase</span>
                                                <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">5</button>
                                                <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">10</button>
                                                <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">20</button>
                                                <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">40</button>
                                                <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">50</button>
                                                <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">100</button>
                                            </div>
                                            <!-- END of Purchase -->
                                            <!-- Build network -->
                                            <div data-bind="if: isRepresentative()" style="margin:5px 0;">
                                                <button data-bind="click: function() { addUser($root.generateUserName('Customer'), true, $root.currentDateInfo())}">Add Customer</button>
                                                <button data-bind="click: function() { addUser($root.generateUserName('Representative'), false, $root.currentDateInfo())}">Add Representative</button>
                                            </div>
                                            <!-- END of Build network -->
                                            <!-- Fourth level -->
                                            <div data-bind="foreach: childs">
                                                <div data-bind="style: { borderWidth: isRepresentative() ? '1px' : '1px' }" style="border: solid black; display: inline-block; padding: 5px; vertical-align: top;">
                                                    <span data-bind="text: name" class="bold"></span>
                                                    <button data-bind="visible: isCustomer() || !active(), text: isCustomer() ? 'Purchase Starter Kit' : 'Renew Subscription', click: function() { purchaseStarterKit($root.currentDateInfo()) }"></button>
                                                    <!-- Info -->
                                                    <div style="border-left: 1px solid black; padding-left: 5px;">
                                                        <div style="display: block;" data-bind="text: paidAsRankStr" class="underline"></div>
                                                        <div data-bind="foreach: periodHistory">
                                                            <span data-bind="text: Constants.rankList[paidAsRank], style: { fontWeight: $index() == $parent.periodHistory().length - 1 ? 'bold' : 'normal' }"></span>
                                                        </div>
                                                        <div style="display: block;" data-bind="text: isQualified() ? 'Quilified' : 'Not qualified'" class="italic"></div>
                                                        <label class="italic">Personal Purchase Sales Volume </label>
                                                        <span data-bind="text: personalPurchaseSalesVolume"></span>
                                                        <div style="display: block;" data-bind="if: isRepresentative()">
                                                            <label class="italic">Personal Sales Volume  </label><span data-bind="text: personalSalesVolume()"></span>
                                                        </div>
                                                        <div style="display: block;" data-bind="if: isRepresentative()">
                                                            <label class="italic">Personal Customer Sales Volume  </label><span data-bind="text: personalCustomerSalesVolume()"></span>
                                                        </div>
                                                        <div style="display: block;" data-bind="if: isRepresentative()">
                                                            <label class="italic">Downline Sales Volume </label><span data-bind="text: downlineSalesVolume()"></span>
                                                        </div>
                                                        <div style="display: block;" data-bind="if: isRepresentative()">
                                                            <label class="italic">Level 1 Sales Volume </label><span data-bind="text: level1SalesVolume()"></span>
                                                        </div>
                                                    </div>
                                                    <!-- END of Info -->
                                                    <!-- Purchase -->
                                                    <div>
                                                        <!-- <input type="text"  /> -->
                                                        <span>Purchase</span>
                                                        <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">5</button>
                                                        <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">10</button>
                                                        <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">20</button>
                                                        <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">40</button>
                                                        <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">50</button>
                                                        <button data-bind="click: function() { purchase($element.innerText, $root.currentDateInfo()) }">100</button>
                                                    </div>
                                                    <!-- END of Purchase -->
                                                    <!-- Build network -->
                                                    <div data-bind="if: isRepresentative()" style="margin:5px 0;">
                                                        <button data-bind="click: function() { addUser($root.generateUserName('Customer'), true, $root.currentDateInfo())}">Add Customer</button>
                                                        <button data-bind="click: function() { addUser($root.generateUserName('Representative'), false, $root.currentDateInfo())}">Add Representative</button>
                                                    </div>
                                                    <!-- END of Build network -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </body>
</html>