$(document).ready(function(){
});
let fourth_ctx = document.getElementById('fourth-chart').getContext('2d');
let fourth_chart = new Chart(fourth_ctx, {
    type: 'bar',
    data: {
        labels:[],
        datasets:[
            {
                label:'MOST SOLD ITEM (GENDER WISE)',
                data:[],
            }
        ],
    },
    options: {
        legend:{
            display:false
        }
    },
});
const fourth = data => {
    let backgroundColor = ['#3e95cd', '#8e5ea2', '#3cba9f', '#e8c3b9'];
    let labels = [];
    let values = [];
    if(data && data.length != 0){
        $.each(data, (index, value) => {
            labels.push(value.name)
            values.push(value.qty)
        })
    }
    fourth_chart.data.labels = labels;
    fourth_chart.data.datasets[0].data = values;
    fourth_chart.data.datasets[0].backgroundColor = backgroundColor;
    fourth_chart.update();
}