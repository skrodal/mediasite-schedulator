<section class="wizard_page hidden">
	<div class="row">
	    <div class="col-lg-12">
	        <div class="text-muted">
	            <h3><?php echo "<i class='". $PAGES_CONFIG["template"]["icon"] . "'></i> " .  $PAGES_CONFIG["template"]["title"]; ?>
	                <small class="text-size-medium">Schedule/player mal</small>
	            </h3>
	        </div>

	        <div id="datatable_templates" class="box">
	            <div class="box-header">
	                <h3 class="box-title">Velg hvilken template som skal brukes for schedule/player</h3>
	            </div>

	            <div class="box-body">
	                <div class="callout callout-info">
	                    <h4>Viktig</h4>
	                    <p>
	                        Templates hentes fra Mediasite server i mappe <code>/UNINETT/Schedulator-Templates</code>:
	                    </p>

		                <img class="img-thumbnail" src="images/mediasite_templates_path.png">

	                    <p>
	                        Du m&aring; selv s&oslash;rge for at denne mappa eksisterer og lagre aktuelle templates i denne.
	                    </p>
	                </div>

	                <table id="table_templates" class="table table-bordered table-striped dataTable centerfirst" width="100%">
	                    <thead>
	                    <tr>
	                        <th>Velg</th>
	                        <th>Tittel</th>
	                        <th>Beskrivelse</th>
	                        <!--<th>Id</th>-->
	                    </tr>
	                    </thead>

	                    <tfoot>
	                    <tr>
	                        <th>Velg</th>
	                        <th>Tittel</th>
	                        <th>Beskrivelse</th>
	                        <!--<th>Id</th>-->
	                    </tr>
	                    </tfoot>
	                </table>
	            </div>

	            <div class="box-footer">
	                <ul class="pager">
	                    <li class="previous"><a href="#">Forrige</a></li>
	                    <li id="template_next" class="next disabled"><a href="#">Neste</a></li>
	                </ul>
	            </div>
	        </div>
	    </div>
	</div>
</section>