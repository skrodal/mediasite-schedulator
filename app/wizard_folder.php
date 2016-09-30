<section class="wizard_page hidden">
	<div class="row">
	    <div class="col-lg-12">
	        <div class="text-muted">
	            <h3><?php echo "<i class='". $PAGES_CONFIG["folder"]["icon"] . "'></i> " .  $PAGES_CONFIG["folder"]["title"]; ?>
	                <small class="text-size-medium"></small>
	            </h3>
	        </div>

	        <div id="folder_container" class="box">
	            <div class="box-header">
	                <h3 class="box-title">Velg hvilken mappe valgte forelesninger skal lagres under</h3>
	            </div>

	            <div class="box-body">
	                <i id="root_folder_anchor" class='ion ion-ios7-folder-outline'></i>&nbsp;&nbsp;<strong>Mediasite</strong>
	                <div id="folder_listing" style="padding-left: 20px;">
	                    <div class="ajaxloader"><span class="ion ion-loading-c"></span> Henter mapper - venligst vent...</div>
	                    <!-- AJAX -->
	                </div>
		     		<div class="callout callout-info">
			            <h4><i class="ion ion-ios7-information"></i>&nbsp;<span id="folder_select_info">Velg en mappe</span></h4>
				        <p>Schedule-fil(er) og resulterende presentasjoner vil lagres her.</p>
		            </div>
	            </div>

	            <div class="box-footer">
	                <ul class="pager">
	                    <li class="previous"><a href="#">Forrige</a></li>
	                    <li id="folder_next" class="next disabled"><a href="#">Neste</a></li>
	                </ul>
	            </div>
	        </div>
	    </div>
	</div>
</section>