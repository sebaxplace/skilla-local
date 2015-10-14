$(document).ready(function(){
	/* This code is executed after the DOM has been completely loaded */

	var tmp;
	
	$('.note').each(function(){
		/* Finding the biggest z-index value of the notes */
		tmp = $(this).css('z-index');
		if(tmp>zIndex) zIndex = tmp;
	})

	/* A helper function for converting a set of elements to draggables: */
	make_draggable($('.note'));
	
	

	/*
	$('.mensaje').click( function() {
	    $(".dett1").toggleClass("aperto");
	    $(".plusicon").toggleClass("plusicon2");
	} );
	*/
	/* Listening for keyup events on fields of the "Add a note" form: */
	$('.pr-body').on('keyup',function(e){
		if(!this.preview)
			this.preview=$('.note');
			
		/* Setting the text of the preview to the contents of the input field, and stripping all the HTML tags: */
		this.preview.find($(this).attr('class').replace('pr-','.')).html($(this).val().replace(/<[^>]+>/ig,''));
	});
	
	
	
	/* The submit button: */
	$('#note-submit').on('click',function(e){
		
		if($('.pr-body').val().length<4)
		{
			alert("il testo è troppo corto!")
			return false;
		}
		
		if($('.pr-author').val().length<1)
		{
			alert("You haven't entered your name!")
			return false;
		}
		
		var xposition = (Math.floor(Math.random() * 1000) + 1);
		var yposition = (Math.floor(Math.random() * 850) + 1);
		var zposition = (Math.floor(Math.random() * 100) + 1);
		
		var finalposition = xposition+'x'+yposition+'x'+zposition;
		$('.xyz').val(finalposition);
		
		var color = $('.color').val();
		var data = {
			'zindex'	: ++zIndex,
			'body'		: $('.pr-body').val(),
			'author'	: $('.pr-author').val(),
			'color'		: color,
			
			'xyz'		: $('.xyz').val(),
			'nome'		: $('.pr-author').val(),
			'messaggio'	: $('.pr-body').val(),
			'color'		: $('.color').val(),
			'posterlab'	: $('.posterlab').val(),
			'tipo'		: $('.tipo').val(),
			'sessione'	: $('.sessione').val(),
			'data'		: $('.fecha').val(),
			'stato'		: $('.stato').val(),
		};
		
		
		/* Sending an AJAX POST request: */
		$.post('../guardar',data,function(msg){
			
			if(parseInt(msg))
			{
				/* msg contains the ID of the note, assigned by MySQL's auto increment: */
				var tmp = $('.previewNote').clone();
				var bodies = $('.pr-body').val();
				var autor = $('.pr-author').val();
				tmp.find('.body').text(bodies);
				tmp.find('.author').text(autor);
				tmp.find('span.data').text(msg).end().css({'z-index':zIndex,top:0,left:0});
				tmp.find('.elimino').attr('onclick', 'eliminar('+msg+')')
				tmp.find('.minimi').attr('onclick', 'minimi('+msg+')')
				tmp.attr("style", 'left:'+xposition+'px;top:'+yposition+'px;z-index:'+zposition)
				$(tmp).attr("id", 'contenedor'+msg);
				$(tmp).removeClass("previewNote");
				tmp.appendTo($('#main'));
				make_draggable(tmp);
				$('.lasted').val(msg);
			}
			
			$(".contentmsg").toggleClass("aperto");
		    $(".plusicon").toggleClass("plusicon2");
		    $(".pr-body").val("");
		});
		
		e.preventDefault();
	})
	
	$('.note-form').on('submit',function(e){e.preventDefault();});
});

var zIndex = 0;

function make_draggable(elements)
{
	/* Elements is a jquery object: */
	
	elements.draggable({
		containment:'parent',
		start:function(e,ui){ ui.helper.css('z-index',++zIndex); },
		stop:function(e,ui){
			
			/* Sending the z-index and positon of the note to update_position.php via AJAX GET: */

			$.post('../mover',{
				  xyz	: ui.position.left+'x'+ui.position.top+'x'+zIndex,
				  
				  
				  id	: parseInt(ui.helper.find('.data').html())
			});
		}
	});
}

function eliminar(id){
	
	var iden = '#contenedor'+id;
	$(iden).addClass("oculto");
	$.post('../elimina',{
		  stato	: '0',
		  
		  
		  id	: id,
	});
}

function minimi(id){
	
	var iden = '#contenedor'+id;
	$(iden).toggleClass("notemini");
	//$(iden).find('.minimi i').attr('class', 'fa fa-expand px24');
	$(iden).find('.minimi i').toggleClass("fa-compress fa-expand");
}
