function statChart1(t){var o={type:"doughnut",data:{datasets:[{data:[600,1400],backgroundColor:[colors.success,colors.gray300],hoverBackgroundColor:[colors.success,colors.gray300],borderWidth:0}]},options:{cutoutPercentage:85,responsive:!0,maintainAspectRatio:!1,animation:{duration:2500},scales:{xAxes:[{display:!1}],yAxes:[{display:!1}]},legend:{display:!1},tooltips:{enabled:!1}}};new Chart(document.getElementById(t).getContext("2d"),o)}function statChart2(t){var o={type:"doughnut",data:{datasets:[{data:[450,50],backgroundColor:[colors.info,colors.gray300],hoverBackgroundColor:[colors.info,colors.gray300],borderWidth:0}]},options:{cutoutPercentage:85,responsive:!0,maintainAspectRatio:!1,animation:{duration:2500},scales:{xAxes:[{display:!1}],yAxes:[{display:!1}]},legend:{display:!1},tooltips:{enabled:!1}}};new Chart(document.getElementById(t).getContext("2d"),o)}function statChart3(t){var o={type:"doughnut",data:{datasets:[{data:[32,8],backgroundColor:[colors.warning,colors.gray300],hoverBackgroundColor:[colors.warning,colors.gray300],borderWidth:0}]},options:{cutoutPercentage:85,responsive:!0,maintainAspectRatio:!1,animation:{duration:2500},scales:{xAxes:[{display:!1}],yAxes:[{display:!1}]},legend:{display:!1},tooltips:{enabled:!1}}};new Chart(document.getElementById(t).getContext("2d"),o)}function statChart4(t){var o={type:"doughnut",data:{datasets:[{data:[240,10],backgroundColor:[colors.pink,colors.gray300],hoverBackgroundColor:[colors.pink,colors.gray300],borderWidth:0}]},options:{cutoutPercentage:85,responsive:!0,maintainAspectRatio:!1,animation:{duration:2500},scales:{xAxes:[{display:!1}],yAxes:[{display:!1}]},legend:{display:!1},tooltips:{enabled:!1}}};new Chart(document.getElementById(t).getContext("2d"),o)}function liveGraph(t){var r=[],a=300;function o(){for(r.shift();r.length<a;){var t=(0<r.length?r[r.length-1]:50)+10*Math.random()-5;t=t<0?0:100<t?100:t,r.push(t)}for(var o=[],e=0;e<r.length;++e)o.push([e,r[e]]);return o}var e=200,s=$.plot($(t),[o()],{series:{lines:{show:!0,fill:!0},shadowSize:0},yaxis:{min:0,max:100,ticks:10},xaxis:{show:!0},grid:{hoverable:!0,clickable:!0,tickColor:colors.gridColor,borderWidth:0,borderColor:colors.gridColor},colors:[colors.orange],tooltip:{show:!0,cssClass:"tooltip-flot",content:function(t,o,e){return"<span class='mr-1'>X: "+parseFloat(o).toFixed(0)+"</span> <span>Y:"+parseFloat(e).toFixed(0)+"</span>"}},tooltipOpts:{defaultTheme:!1}});!function t(){s.setData([o()]),s.draw(),setTimeout(t,e)}()}function memoryChart(t){var r=[],a=300;function o(){for(r.shift();r.length<a;){var t=(0<r.length?r[r.length-1]:50)+10*Math.random()-5;t=t<0?0:100<t?100:t,r.push(t)}for(var o=[],e=0;e<r.length;++e)o.push([e,r[e]]);return o}var e=200,s=$.plot($(t),[o()],{series:{lines:{show:!0,fill:!0},shadowSize:0},yaxis:{min:0,max:100,ticks:10},xaxis:{show:!0},grid:{hoverable:!0,clickable:!0,tickColor:colors.gridColor,borderWidth:0,borderColor:colors.gridColor},colors:[colors.info],tooltip:{show:!0,cssClass:"tooltip-flot",content:function(t,o,e){return"<span class='mr-1'>X: "+parseFloat(o).toFixed(0)+"</span> <span>Y:"+parseFloat(e).toFixed(0)+"</span>"}},tooltipOpts:{defaultTheme:!1}});!function t(){s.setData([o()]),s.draw(),setTimeout(t,e)}()}function viewChart(){var t={chart:{type:"line",height:45,width:100,sparkline:{enabled:!0},parentHeightOffset:0,toolbar:{show:!1}},colors:[colors.white],markers:{size:0},tooltip:{theme:"dark",fixed:{enabled:!1},x:{show:!1},y:{title:{formatter:function(t){return""}}},marker:{show:!1}},stroke:{width:2,curve:"smooth"},series:[{data:[24,92,77,90,91,78,28,49,23,81,15,97,94,16,99,61,38,34,48,3,5,21,27,4,33,40,46,47,48,18]}]};new ApexCharts(document.querySelector("#view-chart"),t).render()}function depthChart(){var t={chart:{type:"line",height:45,width:100,sparkline:{enabled:!0},parentHeightOffset:0,toolbar:{show:!1}},colors:[colors.white],markers:{size:0},tooltip:{theme:"dark",fixed:{enabled:!1},x:{show:!1},y:{title:{formatter:function(t){return""}}},marker:{show:!1}},stroke:{width:2,curve:"smooth"},series:[{data:[14,20,40,70,31,50,90,42,23,81,15,97,94,46,99,61,38,104,98,13,25,25,37,14,23,120,20,42,44,60]}]};new ApexCharts(document.querySelector("#depth-chart"),t).render()}function monthlyStats(){var t={chart:{type:"bar",height:250,redrawOnParentResize:!0,toolbar:{show:!1}},markers:{size:0},dataLabels:{enabled:!1},stroke:{show:!1,width:1,colors:["transparent"]},grid:{show:!0,xaxis:{lines:{show:!0}},yaxis:{lines:{show:!0}}},xaxis:{type:"datetime",categories:["2019-01-01","2019-02-01","2019-03-01","2019-04-01","2019-05-01","2019-06-01","2019-07-01","2019-08-01","2019-09-01","2019-10-01","2019-11-01","2019-12-01"]},yaxis:{axisBorder:{show:!0,color:colors.gray300}},series:[{name:"Previous Month",data:[14,20,40,70,31,50,90,42,23,81,15,97,94,46,99,61,38,104,98,13,25,-25,-37,-14,-23,-120,20,42,44,60]},{name:"Current Month",data:[-14,-20,-40,-70,-31,-50,-90,-42,-23,-81,-15,-97,-94,-46,-99,-61,-38,-104,-98,-13,-25,-25,-37,-14,-23,-120,40,50,54,70]}],legend:{fontFamily:"Nunito Sans, sans-serif",labels:{colors:[colors.gray700,colors.gray700]}},colors:[colors.primary,colors.success],fill:{opacity:1}};new ApexCharts(document.querySelector("#month-stats"),t).render()}function deviceType(){var t={series:[1500,2e3,5500,2500],chart:{height:240,type:"radialBar"},plotOptions:{radialBar:{offsetY:0,startAngle:0,endAngle:270,track:{background:colors.gray400},hollow:{margin:0,size:"30%",background:"transparent",image:void 0},dataLabels:{name:{show:!1},value:{show:!1}}}},colors:[colors.primary,colors.purple,colors.orange,colors.teal],labels:["High Resolution","Smartphones","Desktop","Tablet"],legend:{show:!0,floating:!0,fontSize:"13px",position:"left",offsetX:-10,offsetY:-10,labels:{useSeriesColors:!0},markers:{size:0},formatter:function(t,o){return t+":  "+o.w.globals.series[o.seriesIndex]},itemMargin:{vertical:0,horixontal:0}},responsive:[{breakpoint:480,options:{legend:{show:!1}}}]};new ApexCharts(document.querySelector("#device-type"),t).render()}$((jQuery,statChart1("statistics-chart-1"),statChart2("statistics-chart-2"),statChart3("statistics-chart-3"),statChart4("statistics-chart-4"),liveGraph("#realtime-server-load"),memoryChart("#memory-usage"),viewChart(),depthChart(),monthlyStats(),void deviceType()));