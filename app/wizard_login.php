

<div class="row">
	<div class="col-lg-12">
		<?php
			// User from a non-subscribing org...
			if(!$auth->is_org_subscriber()) {
				?>
				<div class="box box-solid bg-red">
					<div class="box-header">
						<h3 class="box-title">STOPP!</h3>
					</div>
					<div class="box-body">
						<p>
							Feide sier at du kommer fra <strong><?php echo $auth->feide_org(); ?></strong>. Iht. v&aring;re
							interne
							systemer abonnerer ikke din organisasjon p&aring; Mediasite fra UNINETT.
						</p>

						<p>
							Dersom dette er feil, vennligst ta kontakt med oss og gj&oslash;r oss oppmerksom p&aring;
							dette: <a href="mailto:support@ecampus.no">support@ecampus.no</a>.
						</p>

						<p>
							Mvh, <br>
							UNINETT eCampus
						</p>
					</div>
				</div>
			<?php
			} else {
				?>
				<div class="form-box" id="login-box" style="margin-top: 90px;">
					<div>
						<p>
							<?php echo $auth->feide_firstname(); ?>, vennligst logg p&aring; med
							<code><?php echo $auth->feide_org(); ?></code> sin
							Mediasite API Bruker.
						</p>
					</div>
					<div id="login_header" class="header bg-dark-gray"><i class="ion ion-person"></i> Mediasite API
						Bruker
					</div>
					<form id="login_form">
						<div class="body bg-gray">
							<div class="form-group">
								<input type="text" name="username" class="form-control" placeholder="Brukernavn"
								       value="Schedulator"/>
							</div>
							<div class="form-group">
								<input type="password" name="password" class="form-control" placeholder="Passord"/>
							</div>
							<div class="form-group input-group">
								<input type="text" name="apikey" class="form-control" placeholder="API N&oslash;kkel"
								       value="d73c0466-749a-4702-ab48-b8970ace89f0"/>
								<span class="input-group-btn">
                                    <button class="btn btn-info btn-flat" type="button" data-toggle="modal"
                                            data-target="#auth_help">
                                        <i class="ion ion-help-circled"></i>
                                    </button>
                                </span>
							</div>

							<!-- http://mediasite.uninett.no/mediasite/api/Docs/ApiKeyRegistration.aspx -->
						</div>
						<div class="footer">
							<input type="submit" class="btn bg-red btn-block" value="Logg inn">
						</div>
					</form>
					<div class="ajax overlay"></div>
					<div class="ajax loading-img"></div>
				</div>
			<?php } ?>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="auth_help" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
							aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Hjelp</h4>
				</div>
				<div class="modal-body">
					<p class="lead">Mediasite API Bruker</p>

					<p>
						Opprettes i Mediasite Management portal (Security->Users->Add New):
					</p>

					<p><code>http://mediasite.<strong>{ORG}</strong>.no/Mediasite/Manage</code></p>

					<p>
						Det gir mening å gi et forklarende navn, eks. "MediasiteAPI", og brukeren må tilhøre gruppe
						MediasiteAdministrators.
					</p>

					<p class="lead">Mediasite API N&oslash;kkel</p>

					<p>
						I tillegg til en API-bruker kreves en API n&oslash;kkel. Mediasite legger tilrette for
						selvbetjening av opprettelse av API n&oslash;kler her:
					</p>

					<p><code>http://mediasite.<strong>{ORG}</strong>.no/mediasite/api/Docs/ApiKeyRegistration.aspx</code></p>

					<p>Gi n&oslash;kkelen et navn (eks. "Mediasite Schedulator") og ta godt vare p&aring; n&oslash;kkelen
						for fremtidig bruk.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Lukk</button>
				</div>
			</div>
		</div>
	</div>