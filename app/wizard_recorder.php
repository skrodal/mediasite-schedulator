<section class="wizard_page hidden">
	<div class="row">
	    <div class="col-lg-12">
	        <div class="text-muted">
	            <h3><?php echo "<i class='". $PAGES_CONFIG["recorder"]["icon"] . "'></i> " .  $PAGES_CONFIG["recorder"]["title"]; ?>
	                <small class="text-size-medium"></small>
	            </h3>
	        </div>

	        <div class="box">
	            <div class="box-header">
	                <h3 class="box-title">Knytt recorder til rom</h3>
	            </div>

	            <div id="recorders-select-container" class="box-body">
					<p>
						Velg recorder for rom og hvilken funksjon som skal settes p&aring; recorderen.
					</p>

					<div id="recorder_select_div" class="row">
						<!-- AUTO -->
					</div>
					<br/>
	            </div>

	            <div class="box-footer">
	                <ul class="pager">
	                    <li class="previous"><a href="#">Forrige</a></li>
	                    <li id="recorder_next" class="next disabled"><a href="#">Neste</a></li>
	                </ul>
	            </div>
	        </div>
	    </div>
	</div>
</section>