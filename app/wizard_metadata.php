<section class="wizard_page content hidden">
	<div class="row">
		<div class="col-lg-12">
			 <div class="text-muted">
				<h3><?php echo "<i class='" . $PAGES_CONFIG["metadata"]["icon"] . "'></i> " . $PAGES_CONFIG["metadata"]["title"]; ?>
					<small class="text-size-medium"> tilleggsinfo</small>
				</h3>
			</div>

			<div id="metadata_container" class="box">
				<div class="box-header">
					<h3 class="box-title">Legg til metadata for schedule</h3>
				</div>

				<div class="box-body">
					<p>
						Metadata kan legges til direkte fra kalender og/eller manuelt. Hvis tilgjengelig kan metadata kan hentes via
						kalender for &aring; spare deg tid. Metadata som velges/skrives inn gjelder for hele schedule'n, dvs. alle
						opptak.
					</p>
					<p>
						Metadata er strengt tatt ikke p&aring;krevd, men anbefales selvf&oslash;lgelig.
					</p>

					<div id="meta-fill-tabs-select" class="nav-tabs-custom">
						<ul class="nav nav-tabs" role="tablist">
							<li class="active bold"><a id="auto" href="#meta-auto-fill" role="tab" data-toggle="tab">Automagisk</a></li>
							<li class="bold"><a id="manual" href="#meta-manual-fill" role="tab" data-toggle="tab">Manuell</a></li>
						</ul>
						<div class="tab-content" style="background-color: #FBFBFB;">
							<!-------------------- ############### AUTO TAB ############### -------------------->
							<div id="meta-auto-fill" class="tab-pane active fade in" role="tabpanel">
								<p>
									Vi tar utgangspunkt i <strong>ett</strong> av dine valgte kalenderinnslag, nemlig dette:
								</p>

								<code id="cal_entry_preview">
									<!-- AUTO -->
								</code><br><br>

								<p>
									Fors&oslash;k nedenfor &aring; matche feltene med type informasjon. Du kan deretter
									<a onclick="$('#meta-fill-tabs-select #manual').trigger('click')">tilf&oslash;ye eller redigere
									manuelt</a>.
								</p>

								<div style="margin-left: 10px;">
									<div id="schedule_auto_title_container" class="form-group">
										<label for="schedule_auto_title">Tittel</label>
										<select id="schedule_auto_title" data-controls="schedule_title" class="form-control schedule_auto_select">
											<!-- AUTO -->
										</select>
				                    </div>

									<div id="schedule_auto_description_container" class="form-group">
										<label for="schedule_auto_description">Beskrivelse</label>
										<select id="schedule_auto_description" data-controls="schedule_description" class="form-control schedule_auto_select">
											<!-- AUTO -->
										</select>
				                    </div>

									<div id="schedule_auto_code_container" class="form-group">
				                        <label for="subject_auto_code">Fagkode ("Tag")</label>
										<select id="subject_auto_code" data-controls="subject_code" class="form-control schedule_auto_select">
											<!-- AUTO -->
										</select>
				                    </div>

									<div id="schedule_auto_presenter_container" class="form-group">
										<label for="subject_auto_presenter_name">Foreleser</label>
										<select id="subject_auto_presenter_name" data-controls="subject_presenter" class="form-control schedule_auto_select">
											<!-- AUTO -->
										</select>
										<br>
										<div id="selected_presenter_list_container" class="callout callout-info hidden">
										    <h4>Fant <span id="presenter_count" class="badge bg-orange">2</span> forelesere for dette faget:</h4>
											<ul id="presenter_ul">
												<!-- AUTO -->
											</ul>
										    <p>Disse vil legges til i Schedule.</p>
										</div>
				                    </div>
								</div>

								<a onclick="$('#meta-fill-tabs-select #manual').trigger('click')" class="pull-right">
									Ta en titt og dobbeltsjekk/oppdater manuelt.
								</a>
								<span class="clearfix"></span>
							</div>
							<!-------------------- ############### MANUAL TAB ############### -------------------->
							<div id="meta-manual-fill" class="tab-pane fade" role="tabpanel">
								<h4>Fag</h4>
								<div style="margin-left: 10px;">
									<div class="form-group">
				                      <label for="schedule_title">Tittel</label>
				                      <input id="schedule_title" type="text" class="form-control" placeholder="Tittel">
				                    </div>

									<div class="form-group">
				                      <label for="schedule_description">Beskrivelse</label>
				                      <input id="schedule_description" type="text" class="form-control" placeholder="Beskrivelse">
				                    </div>

									<div class="form-group">
				                      <label for="subject_code">Fagkode ("Tag")</label>
				                      <input id="subject_code" type="text" class="form-control" placeholder="Fagkode">
				                    </div>
								</div>

								<h4>Foreleser(e) ("Presenter")</h4>
								<div style="margin-left: 10px;">
									<div id="presenter_list_auto_select_container" class="hidden">
									</div>

									<div id="presenter_manual_input_container">
										<div class="form-group">
					                      <label for="subject_presenter_fname">Fornavn</label>
					                      <input id="subject_presenter_fname" type="text" class="form-control" placeholder="Fornavn">
					                    </div>

										<div class="form-group">
					                      <label for="subject_presenter_mname">Mellomnavn</label>
					                      <input id="subject_presenter_mname" type="text" class="form-control" placeholder="Mellomnavn">
					                    </div>

										<div class="form-group">
					                      <label for="subject_presenter_lname">Etternavn</label>
					                      <input id="subject_presenter_lname" type="text" class="form-control" placeholder="Etternavn">
					                    </div>
									</div>
								</div>
							</div>
						</div>
	                </div>
				</div>

				<div class="box-footer">
					<ul class="pager">
						<li class="previous"><a href="#">Forrige</a></li>
						<li id="metadata_next" class="next"><a href="#">Neste</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>


