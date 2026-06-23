$(document).ready(function(){
});
const transitionDuration = 900;
const displays = document.querySelectorAll('.note-display');
displays.forEach(display => {
	let note = $('.percent__int').html();
	strokeTransition(display, note);
});

function strokeTransition(display, note) {
	let progress = display.querySelector('.circle__progress--fill');
	let radius = progress.r.baseVal.value;
	let circumference = 2 * Math.PI * radius;
	let offset = circumference * (10 - note) / 1000;

	progress.style.setProperty('--initialStroke', circumference);
	progress.style.setProperty('--transitionDuration', `${transitionDuration}ms`);

	setTimeout(() => progress.style.strokeDashoffset = offset, 100);
}
const first = data =>{
	if(data['pur_qty']){
        $('#pur_qty').html(data['pur_qty'])
    }
    if(data['pret_qty']){
        $('#pret_qty').html(data['pret_qty'])
    }
    if(data['sale_qty']){
        $('#sale_qty').html(data['sale_qty'])
    }
    if(data['sret_qty']){
        $('#sret_qty').html(data['sret_qty'])
    }
}

