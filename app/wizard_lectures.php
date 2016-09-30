<section class="wizard_page hidden">
	<div class="row">
		<div class="col-lg-12">
			<div class="text-muted">
				<h3><?php echo "<i class='" . $PAGES_CONFIG["lectures"]["icon"] . "'></i> " . $PAGES_CONFIG["lectures"]["title"]; ?>
					<small class="text-size-medium"></small>
				</h3>
			</div>

			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Velg hvilke forelesninger som skal scheduleres</h3>
				</div>

				<div id="datatable_lectures" class="box-body">
					<div class="callout callout-info">
						<h4>
							Du har valgt <span id="lecture_select_count" class="badge bg-orange">0</span> forelesninger
						</h4>
						<p>
							Tips! Om du har en miks av ulike emner i tabellen kan snars&oslash;k v&aelig;re din venn :-)
						</p>
					</div>

					<table id="table_lectures" class="table table-bordered table-striped dataTable centerfirst"
					       width="100%">
						<thead>
						<tr>
							<th><input id="select_all_lectures_top" type="checkbox" name="check_all_lectures"/> Velg alle
							</th>
							<th>Tittel</th>
							<th>Beskrivelse</th>
							<th>Start</th>
							<th>Slutt</th>
							<th>Sted</th>
						</tr>
						</thead>

						<tfoot>
						<tr>
							<th><input id="select_all_lectures_bottom" type="checkbox" name="check_all_lectures"/> Velg alle
							</th>
							<th>Tittel</th>
							<th>Beskrivelse</th>
							<th>Start</th>
							<th>Slutt</th>
							<th>Sted</th>
						</tr>
						</tfoot>
					</table>
				</div>

				<div class="box-footer">
					<ul class="pager">
						<li class="previous"><a href="#">Forrige</a></li>
						<li id="subjects_next" class="next disabled"><a href="#">Neste</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>