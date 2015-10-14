function caricaPosizioni(){
	var data = "posId="+$('#posterlabs').val();
	
	$.ajax({
		type:'POST',
		url:"<?php echo $this->basePath('/admin/contenuti/carica') ?>",
		data:data,
		success: funtion(html){
			$('#posizioni').html(html);
		}
	});
}