!function(r){"use strict";function e(){this.$body=r("body"),this.charts=[]}e.prototype.initCharts=function(){window.Apex={chart:{parentHeightOffset:0,toolbar:{show:!1}},grid:{padding:{left:0,right:0}},colors:["#3e60d5","#47ad77","#fa5c7c","#ffbc00"]};var e=["#3e60d5","#47ad77","#fa5c7c","#ffbc00"],t=r("#revenue-chart").data("colors"),a={series:[{name:"Revenue",data:[440,505,414,526,227,413,201]},{name:"Sales",data:[320,258,368,458,201,365,389]},{name:"Profit",data:[320,458,369,520,180,369,160]}],chart:{height:377,type:"bar"},plotOptions:{bar:{columnWidth:"60%"}},stroke:{show:!0,width:2,colors:["transparent"]},dataLabels:{enabled:!1},colors:e=t?t.split(","):e,xaxis:{categories:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]},yaxis:{title:{text:"$ (thousands)"}},legend:{offsetY:7},grid:{padding:{bottom:20}},fill:{opacity:1},tooltip:{y:{formatter:function(e){return"$ "+e+" thousands"}}}},e=(new ApexCharts(document.querySelector("#revenue-chart"),a).render(),["#3e60d5","#47ad77","#fa5c7c","#ffbc00"]),a={series:[{name:"Mobile",data:[25,15,25,36,32,42,45]},{name:"Desktop",data:[20,10,20,31,27,37,40]}],chart:{height:250,type:"line",toolbar:{show:!1}},colors:e=(t=r("#yearly-sales-chart").data("colors"))?t.split(","):e,stroke:{curve:"smooth",width:[3,3]},markers:{size:3},xaxis:{categories:["2017","2018","2019","2020","2021","2022","2023"]},legend:{show:!1}},a=(new ApexCharts(document.querySelector("#yearly-sales-chart"),a).render(),Apex.grid={padding:{right:0,left:0}},{series:[44,55,13,43],chart:{width:80,type:"pie"},legend:{show:!(Apex.dataLabels={enabled:!1})},colors:["#1a2942","#f13c6e","#3bc0c3","#d1d7d973"],labels:["Team A","Team B","Team C","Team D"]});new ApexCharts(document.querySelector("#us-share-chart"),a).render()},e.prototype.init=function(){this.initCharts()},r.Dashboard=new e,r.Dashboard.Constructor=e}(window.jQuery),function(t){"use strict";t(document).ready(function(e){t.Dashboard.init()})}(window.jQuery);
(function($){
    'use strict';

    function Dashboard() {
        this.$body = $('body');
    }

    Dashboard.prototype.initCharts = function() {
        // configure Apex global options
        window.Apex = window.Apex || {};
        Apex.chart = Apex.chart || {};
        Apex.chart.parentHeightOffset = 0;
        Apex.chart.toolbar = { show: false };
        Apex.grid = Apex.grid || {};
        Apex.grid.padding = { left:0, right:0 };

        // Revenue chart (bar)
        var revenueEl = document.querySelector('#revenue-chart');
        if (revenueEl && typeof ApexCharts !== 'undefined') {
            var colors = ['#3e60d5','#47ad77','#fa5c7c','#ffbc00'];
            var dataColors = $(revenueEl).data('colors');
            if (dataColors) colors = dataColors.split(',');

            var options = {
                series: [
                    { name: 'Revenue', data: [440,505,414,526,227,413,201] },
                    { name: 'Sales', data: [320,258,368,458,201,365,389] },
                    { name: 'Profit', data: [320,458,369,520,180,369,160] }
                ],
                chart: { height: 377, type: 'bar' },
                plotOptions: { bar: { columnWidth: '60%' } },
                stroke: { show: true, width: 2, colors: ['transparent'] },
                dataLabels: { enabled: false },
                colors: colors,
                xaxis: { categories: ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] },
                yaxis: { title: { text: '$ (thousands)' } },
                legend: { offsetY: 7 },
                grid: { padding: { bottom: 20 } },
                fill: { opacity: 1 },
                tooltip: { y: { formatter: function (val) { return '$ ' + val + ' thousands'; } } }
            };

            try {
                new ApexCharts(revenueEl, options).render();
            } catch (e) {
                console.error('Revenue chart init failed', e);
            }
        }

        // Yearly sales chart (line)
        var yearlyEl = document.querySelector('#yearly-sales-chart');
        if (yearlyEl && typeof ApexCharts !== 'undefined') {
            var colorsY = ['#3e60d5','#47ad77','#fa5c7c','#ffbc00'];
            var dataColorsY = $(yearlyEl).data('colors');
            if (dataColorsY) colorsY = dataColorsY.split(',');

            var optionsY = {
                series: [
                    { name: 'Mobile', data: [25,15,25,36,32,42,45] },
                    { name: 'Desktop', data: [20,10,20,31,27,37,40] }
                ],
                chart: { height: 250, type: 'line', toolbar: { show: false } },
                colors: colorsY,
                stroke: { curve: 'smooth', width: [3,3] },
                markers: { size: 3 },
                xaxis: { categories: ['2017','2018','2019','2020','2021','2022','2023'] },
                legend: { show: false }
            };

            try {
                new ApexCharts(yearlyEl, optionsY).render();
            } catch (e) {
                console.error('Yearly sales chart init failed', e);
            }
        }

        // US share pie
        var usShareEl = document.querySelector('#us-share-chart');
        if (usShareEl && typeof ApexCharts !== 'undefined') {
            var optionsP = {
                series: [44,55,13,43],
                chart: { width: 80, type: 'pie' },
                legend: { show: false },
                colors: ['#1a2942','#f13c6e','#3bc0c3','#d1d7d973'],
                labels: ['Team A','Team B','Team C','Team D']
            };
            try {
                new ApexCharts(usShareEl, optionsP).render();
            } catch (e) {
                console.error('US share chart init failed', e);
            }
        }
    };

    Dashboard.prototype.init = function() {
        this.initCharts();
    };

    // expose to global
    $.Dashboard = new Dashboard();
    $.Dashboard.Constructor = Dashboard;

    $(document).ready(function(){
        $.Dashboard.init();
    });

    // Profile image preview (guarded)
    var urlProfil = document.getElementById('url_profil');
    if (urlProfil) {
        urlProfil.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    var img = document.getElementById('imagePreview');
                    if (img) img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

})(window.jQuery);

// Optional: guarded search input (uncomment if used)
// var searchInput = document.getElementById('search-input');
// if (searchInput) {
//     searchInput.addEventListener('keyup', function() {
//         var input = this.value.toLowerCase();
//         var rows = document.querySelectorAll('#table-body tr');
//         rows.forEach(function(row) {
//             var text = row.textContent.toLowerCase();
//             row.style.display = text.includes(input) ? '' : 'none';
//         });
//     });
// }