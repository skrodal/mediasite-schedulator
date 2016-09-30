var error_classes = ["bg-red", "bg-orange", "bg-yellow", "bg-danger", "bg-purple"];

/**
 * Authenticate with Mediasite's API (requires an API user/pass/key)
 *
 * @author Simon Skrødal
 */
$("#login_form").submit(function (event) {
	event.preventDefault();

	$.post("api/mediasite_api_auth.php",
		{
			username: $("input[name='username']").val(),
			password: $("input[name='password']").val(),
			api_key: $("input[name='apikey']").val(),
			api_url: api_url,
			service_url: service_url
		},
		function (data) {
			console.log(data);

			if (data.status == false) {
				$("#login_header").html('<i class="ion ion-alert-circled"></i> Ingen Adgang');
				// Animate error box...
				$("#login_header").removeClass(function (index, css) {
					return (css.match(/(^|\s)bg-\S+/g) || []).join(' ');
				}).addClass(error_classes[~~(Math.random() * error_classes.length)]);
				$("#login_header").fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
			} else {
				// All good, auth-session is confirmed and stored, reload and enter the service
				location.reload();
			}

		}, "json"
	);

	return false;
});