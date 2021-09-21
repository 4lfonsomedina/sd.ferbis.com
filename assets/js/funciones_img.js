$(document).ready(function() {
	$(document).on("change",".p_imagen",function(){
		//cargar archivo
		alert();
		var file = this.files;
		//crear imagen
		var img = document.getElementById("img_temp");
		img.src = window.URL.createObjectURL(file);
		var ctx = canvas.getContext("2d");
		ctx.drawImage(img, 0, 0,250,250);
	})
	$(document).on("click",".file_camera",function(){
		$(this).parent('td').find('input').click();
	})

});