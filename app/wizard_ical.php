<section class="wizard_page hidden">
	<div class="row">
		<div class="col-lg-12">
			<div class="text-muted">
				<h3><?php echo "<i class='" . $PAGES_CONFIG["ical"]["icon"] . "'></i> " . $PAGES_CONFIG["ical"]["title"]; ?>
					<small class="text-size-medium"> url til .ics fil</small>
				</h3>
			</div>

			<div id="get_calendar" class="box">
				<div class="box-header">
					<h3 class="box-title">Lim inn lenke til iCalender og hent innhold</h3>
				</div>

				<div class="box-body">
					<p class="label label-default">URL til .ics fil:</p>
					<div class="input-group">
						<input id="input_calendar_url" type="text" class="form-control"

						value="">

	                        <span class="input-group-btn">
	                            <button id="btn_get_calendar" class="btn btn-primary" type="button">
		                            <i class="ion ion-ios7-cloud-download"></i> Hent
	                            </button>
	                        </span>
					</div>
				</div>

				<div class="box-footer">
					<ul class="pager">
						<li class="previous"><a href="#">Forrige</a></li>
						<li id="ical_next" class="next disabled"><a href="#">Neste</a></li>
					</ul>
				</div>
				<div class="ajax overlay hidden"></div>
				<div class="ajax text-center hidden">
					<br><span class="ion ion-loading-c text-size-xx-large"></span>
				</div>
			</div>
		</div>
	</div>
</section>

