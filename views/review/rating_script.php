var ratingValueColorMap = {
    "1.0": "#CB202D",
    "1.5": "#DE1D0F",
    "2.0": "#FF7800",
    "2.5": "#FFBA00",
    "3.0": "#EDD614",
    "3.5": "#9ACD32",
    "4.0": "#5BA829",
    "4.5": "#3F7E00",
    "5.0": "#305D02"
};

var rating;

$(document).ready(function () {
   var num = new Number('<?php echo isset($user_rate->points)?$user_rate->points:''; ?>');
   var n=num.toPrecision(2);
   
   rating = n;
    $('.bar-rating').barrating('show', {
  		initialRating:n,                    
		showValues:false,
		showSelectedRating:false,
		onSelect: onSelect
  	});
  	attachListener();
    setNormalState();
    
    $('i.clear_rating').on('click',function () {
		if(!confirm("Your Review also removed about this user. Are you sure remove your rating?"))
			return true;
		var rating_id=$("#rating_id").val();
		$(".input_review").val(0);
		var req_data = {};
		
		req_data.rating=0;
		req_data.rating_id=rating_id;
		<?php if(isset($event_id)){ ?>
			req_data.source_id="<?=$event_id?>";
			req_data.type="event";
		<?php }else{ ?>
			req_data.source_id="<?=$user_id?>";
			req_data.type="user";
		<?php } ?>
		
		Alil.util.apiGet("<?= $submit_to ?>",req_data,function(response){
			if (response.status == 'SUCCESS'){				    
				createBarRater('bar-rating1', "0");
				createBarRater('bar-rating2', "0");
				$('.rating-loading').addClass('hidden');
				$('.clear').hide();
				$(".rate-number").text("");
				$(".rating_close_ico").addClass("hidden");
				$(".input_review").val('');
				attachListener();
			}
		});  
   
	});
});

function onSelect(value, text) {
	var brlogin="<?php echo $this->session->userdata('user_id') && $this->session->userdata('active_user_loggedin')?'true':'false' ?>";
	if(brlogin=='true'){
		rating = value;
		var id = $(this).parent().prev().attr('id');
		var otherId = id == 'bar-rating1' ? 'bar-rating2' : 'bar-rating1';
		createBarRater(otherId, value);
		$('.rating-loading').removeClass('hidden');
		$('.clear').hide();
		var rating_id=$("#rating_id").val();
		var req_data = {};
		
		req_data.rating=value;
		req_data.rating_id=rating_id;
		<?php if(isset($event_id)){ ?>
			req_data.source_id="<?=$event_id?>";
			req_data.type="event";
		<?php }else{ ?>
			req_data.source_id="<?=$user_id?>";
			req_data.type="user";
		<?php } ?>
		
		
		Alil.util.apiGet("<?= $submit_to ?>",req_data,function(response){
			if (response.status == 'SUCCESS') {
				$(".input_ratingID").val(response.id);
				$("#rating_id").val(response.id);
				$("#user_points").val(value);
				$(".rate-number").text(response.points);
			}
			else {
				$(".input_ratingID").val(0);
				$("#rating_id").val('');
				$(".rate-number").text(value);
			};
			$('.rating-loading').addClass('hidden');
			$('.clear').show();
			$(".rating_close_ico").removeClass("hidden");
			$(".input_review").val(value);
			$(".user-rate-number .rate-number").text(value);
			
			attachListener();
		});	
		
	}
	else{
		var redirect=window.location.pathname;
		$(".afterLoginRedirect").val(redirect);
		$("#siginModal").modal();
		return false;
	}
}

function attachListener() {
    $('.br-widget').children('a').mouseover(function () {
        $('.br-widget').children('a').css('background', '#e3e3e3');
        var activeBars = $('.br-active');
        activeBars.each(function (i, bar) {
            var val = $(bar).attr('data-rating-value');
            $(bar).css('background', ratingValueColorMap[val]);
        });
    });

    $('.br-widget').children('a').mouseleave(function () {
        setNormalState();
    })
}

function setNormalState() {
    $('.br-widget').children('a').css('background', '#e3e3e3');
    $('.br-current').css('background', ratingValueColorMap[rating]);
    $('.br-current').prevAll().css('background', ratingValueColorMap[rating]);
    $('.br-current').nextAll('a').css('background', '#e3e3e3');
}


function createBarRater(id, value) {
    $('#' + id).barrating('destroy');
    $('#' + id).barrating('show', {
        initialRating: value,
        onSelect: onSelect,
        showValues: false,
        showSelectedRating: false
    });
    attachListener();
}
