$(document).ready(function(){
});
let second_ctx = document.getElementById('second-chart').getContext('2d');
let second_chart = new Chart(second_ctx, {
    type: 'bar',
    data: {
        labels:[],
        datasets:[
            {
                label:'MONTHLY PROFIT',
                data:[],
            }
        ],
    },
    options: {
        legend:{
            display:false
        },
        scales:{
            yAxes:[{
                ticks:{
                    beginAtZero: true,
                }
            }]
        }
    },
});
const second = data => {
    let backgroundColor = ['#3cba9f', '#c45850'];
    let labels  = [];
    let profits = [];
    let bg_color= [];
    data.forEach(index => {
        let color = index.profit_loss < 0 ? backgroundColor[1] : backgroundColor[0];
        labels.push(index.month_year);   
        profits.push(index.profit_loss.toFixed(2));   
        bg_color.push(color);   
    })
    second_chart.data.labels = labels;
    second_chart.data.datasets[0].data = profits;
    second_chart.data.datasets[0].backgroundColor = bg_color;
    second_chart.update();
}