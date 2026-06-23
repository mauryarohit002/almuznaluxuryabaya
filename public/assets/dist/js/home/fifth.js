$(document).ready(function(){
});
let fifth_ctx = document.getElementById('fifth-chart').getContext('2d');
let fifth_chart = new Chart(fifth_ctx, {
    type: 'bar',
    data: {
        labels:[],
        datasets:[
            {
                label:'MOST SOLD ITEM (CATEGORY WISE)',
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
const fifth = data => {
    let backgroundColor = ['#3e95cd', '#8e5ea2', '#3cba9f', '#e8c3b9'];
    let labels = [];
    let values = [];
    if(data && data.length != 0){
        $.each(data, (index, value) => {
            labels.push(value.name)
            values.push(value.qty)
        })
    }
    fifth_chart.data.labels = labels;
    fifth_chart.data.datasets[0].data = values;
    fifth_chart.data.datasets[0].backgroundColor = backgroundColor;
    fifth_chart.update();
}