$(document).ready(function(){
});
let third_ctx = document.getElementById('third-chart').getContext('2d');
let third_chart = new Chart(third_ctx, {
    type: 'line',
    data: {
        labels:[],
        datasets: [],
    },
    options:{
        legend:{
            display:true
        },
        elements:{
            line:{
                tension : 0,
            }
        },
        animation: false,
    }
});
const third = data => {
    let borderColors = ['#3e95cd', '#8e5ea2', '#3cba9f', '#e8c3b9'];
    let labels = [];
    let sr_no  = 0;
    for (const [mode, temp] of Object.entries(data)) {
        let amts = [];
        for (const [month_year, amt] of Object.entries(temp)) {
            amts.push(amt);
            if(labels.indexOf(month_year) === -1){
                labels.push(month_year)
            }
        }
        third_chart.data.datasets[sr_no] = {
            label : mode,
            data : amts,
            fill: false,
            borderColor: borderColors[sr_no],
        };
        sr_no++;
    }
    third_chart.data.labels = labels;
    third_chart.update();
}
