$(document).ready(function() {
	$('#frontCat option[value="'+$('#cat').val()+'"]').prop('selected', true);
	$('#frontSort option[value="'+$('#sort').val()+'"]').prop('selected', true);
	if ($('.page').val() <= 1) {
		$('#prev').prop('disabled', true);
		$('#prev').addClass('nopage');
		$('.pageBut[value=1]').addClass('curPage');
	}
	if ($('.page').val() > 1 && $('.page').val() < Math.ceil(parseInt($('#resNum').val())/10)) {
		$('.pageBut[value='+$('.page').val()+']').addClass('curPage');
	}
	if ($('.page').val() >= Math.ceil(parseInt($('#resNum').val())/10)) {
		$('#next').prop('disabled', true);
		$('#next').addClass('nopage');
		$('.pageBut[value='+Math.ceil(parseInt($('#resNum').val())/10)+']').addClass('curPage');
	}
	$('#heyman').val();
	$('.curPage').prop('disabled', true);
	$('#next').click(function() {
		if ($('.page').val() < 1) {
			$('.page').val(1);
		} else {
			$upone = parseInt($('.page').val());
			$upone++;
			$('.page').val($upone);
		}
		$('#chgpage').submit();
	});
	$('#prev').click(function() {
		if ($('.page').val() > Math.ceil(parseInt($('#resNum').val())/10)) {
			$('.page').val(Math.ceil(parseInt($('#resNum').val())/10))
		} else {
			$dnone = parseInt($('.page').val());
			$dnone--;
			$('.page').val($dnone);
		}
		$('#chgpage').submit();
	});
	$('.pageBut').click(function() {
		$('.page').val($(this).val());
		$('#chgpage').submit();
	});
	headheight = $('.headertwo').height();
	threshold = 0;
	oldtop = $(window).scrollTop();
	$(window).on('scroll', _.throttle(function() {
		if ($(window).scrollTop() <= 0) {
			$('.headertwo').css('top', 0);
		} else {
			newtop = $(window).scrollTop();
			diff = newtop - oldtop;
			if (threshold + diff > headheight) {
				threshold = headheight;
			} else if (threshold + diff < 0) {
				threshold = 0;
			} else {
				threshold = threshold + diff;
			};
			$('.headertwo').css('top', (-threshold)+'px');
			oldtop = newtop;
		}
	}, 25));
	$('#menubutton').click(function() {
		if ($('.searchtwo').is(':hidden')) {
			$('.searchtwo').slideDown(200, function() {
				headheight = $('.headertwo').height();
			});
			$('#menubutton').css('background-color', '#00ff66')
		} else if ($('.searchtwo').is(':visible')) {
			$('.searchtwo').slideUp(200, function() {
				headheight = $('.headertwo').height();
			});
			$('#menubutton').css('background-color', '#cdcdcd')
		}
	});
	oldwidth = $(window).width();
	$(window).resize(function() {
		newwidth = $(window).width();
		if (newwidth !== oldwidth) {
			$('#content').css({'paddingBottom': $('#footer').outerHeight(true)});
			oldwidth = newwidth;
		}
	});
	$('#content').css({'paddingTop': $('.headertwo').outerHeight(true), 'paddingBottom': $('#footer').outerHeight(true)});
	var x = 0;
});