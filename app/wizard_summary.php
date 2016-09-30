<section class="wizard_page hidden">
	<div class="row">
	    <div class="col-lg-12">
	        <div class="text-muted">
	            <h3><?php echo "<i class='". $PAGES_CONFIG["summary"]["icon"] . "'></i> " .  $PAGES_CONFIG["summary"]["title"]; ?>
	                <small class="text-size-medium"> godkjenn</small>
	            </h3>
	        </div>

	        <div id="metadata_container" class="box">
	            <div class="box-header">
	                <h3 class="box-title">Dobbeltsjekk og godkjenn</h3>
	            </div>

	            <div class="box-body">
		            <p>
			            Dobbeltsjekk alle felter.
			            Klikk knappen 'SCHEDULÉR' lenger ned om du er forn&oslash;yd. Er du ikke kan du
			            g&aring; tilbake og endre.
		            </p>


	                <div class="form-group">
						<label for="summary_schedule_title">Tittel:</label>
	                    <input id="summary_schedule_title" type="text" disabled class="form-control" placeholder="Tittel mangler">
	                </div>

	                <div class="form-group">
						<label for="summary_schedule_description">Beskrivelse:</label>
	                    <input id="summary_schedule_description" type="text" disabled class="form-control" placeholder="Beskrivelse mangler">
	                </div>

	                <div class="form-group">
						<label for="summary_subject_code">Fagkode:</label>
	                    <input id="summary_subject_code" type="text" disabled class="form-control" placeholder="Fagkode mangler">
	                </div>

	                <div class="form-group">
	                    <label for="summary_subject_presenters">Presenter(s):</label>
	                    <div id="summary_subject_presenters">
						<!-- Auto -->
	                    </div>
	                </div>

	                <div class="form-group">
	                    <label for="summary_template">Template:</label>
	                    <input id="summary_template" type="text" disabled class="form-control" placeholder="Template mangler">
	                </div>

	                <div class="form-group">
	                    <label for="summary_folder">Folder:</label>
	                    <input id="summary_folder" type="text" disabled class="form-control" placeholder="Folder mangler">
	                </div>

	                <div class="form-group">
						<label for="summary_recorder">Recorder valg:</label>
		                <table id="summary_recorder" class="table table-bordered table-striped" width="100%">
			                <thead>
				                <tr>
				                    <th>Rom</th>
				                    <th>Recorder</th>
				                    <th>Operation</th>
				                </tr>
			                </thead>

			                <tbody>

			                </tbody>
		                </table>
	                </div>


		            <label for="summary_table_lectures">Valgte kalenderinnslag:</label>
	                <table id="summary_table_lectures" class="table table-bordered table-striped dataTable" width="100%">
	                    <thead>
		                    <tr>
			                    <th>Tittel</th>
			                    <th>Beskrivelse</th>
			                    <th>Start</th>
			                    <th>Slutt</th>
			                    <th>Sted</th>
		                    </tr>
	                    </thead>

	                    <tfoot>
	                        <tr>
			                    <th>Tittel</th>
			                    <th>Beskrivelse</th>
			                    <th>Start</th>
			                    <th>Slutt</th>
			                    <th>Sted</th>
		                    </tr>
	                    </tfoot>
	                </table>

	                <div class="text-center">
	                    <a id="submit_schedule" class="btn btn-lg btn-success" data-toggle="modal" data-target=".schedule_response_modal">
	                        <i class="ion ion-upload"></i> SCHEDULÉR
	                    </a>
	                </div>
	            </div>

	            <div class="box-footer">
	                <ul class="pager">
	                    <li class="previous"><a href="#">Forrige</a></li>
	                </ul>
	            </div>
	        </div>
	    </div>

		<!-- API Response Modal -->
		<div id="schedule_response_modal" class="modal fade schedule_response_modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-lg">
				<div id="api_response" class="modal-content">
					<div id="schedule_modal_box" class="box box-solid bg-dark-gray" style="margin-bottom: 0px;">
						<div class="box-header">
							<h3 class="box-title">Svar fra Mediasite:</h3>
						</div>
						<div class="box-body">
							<div class="ajaxloader">
								<h1 class="uninett-fontColor-white"><span class="ion ion-loading-b"></span> Schedulerer...</h1>
								<p class="lead uninett-fontColor-white">Snakker med Mediasite, vennligst vent p&aring; svar...</p>
							</div>

							<div id="api_response_json">

							</div>
						</div><!-- /.box-body -->
					</div>
				</div>
			</div>
		</div>
	</div>
</section>