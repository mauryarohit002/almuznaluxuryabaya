let imageOptions= {};
let observer = new IntersectionObserver((entries, observer) => {
	entries.forEach(entry => {
		if(!entry.isIntersecting) return;
		const image = entry.target;
	    const {src} = image.dataset;
	    if(!src) return;
	    image.src 	= src;
	    observer.unobserve(image);
	});
}, imageOptions);
const lazy_loading = id => {
	const images = document.getElementsByClassName(`${id}`);
	for (let i = 0; i < images.length; i++){
		observer.observe(images.item(i));
	}
}