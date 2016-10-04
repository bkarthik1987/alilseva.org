<div class="container register-form sregister-form" id="sregister-form">
	<i class='fa fa-spinner fa-spin'></i>
</div>
</div>
<script type="text/javascript">
var type_skills=$.parseJSON('<?=$skills?>');
var type_services=$.parseJSON('<?=$services?>');
var type_locality=$.parseJSON('<?=$locality?>');
var activites=$.parseJSON('<?=$activites?>');
Alil.script.initialize(type_skills, type_services, type_locality, activites, []);
$(window).bind("load", function() {
	var data = [];
	var htmlOutput = $("#volunteer-register-step1").html();
	$("#sregister-form").html(htmlOutput);
	$("#volunteerUserAjaxForm").validate({
		rules:{
			email_address:{
				required:true,
				remote:{
					url: base_url+api_path+'user/check-email',
					type:"GET"
				}	
			}
		},
		messages:{
			email_address:{
				remote: "Email address already exists for another user"
			}
		}
	});
});
</script>