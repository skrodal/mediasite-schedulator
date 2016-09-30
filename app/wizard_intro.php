<section class="wizard_page">
	<div class="row">
	    <div class="col-lg-12">
	        <div class="jumbotron">
	            <ul class="pager pull-right">
	                <li class="text-size-xx-large"><a class="bg-orange ">START</a></li>
	            </ul>
	            <h1><i class="ion ion-wand"></i> Veiviser</h1>

	            <p class="lead">Mediasite Schedulator hjelper deg med import fra iCalendar timeplan (.ics)<sup>&dagger;</sup> til Mediasite schedules.</p>

	            <blockquote class="blockquote-reverse">
	                <div class="page-header">
	                    <h2 class="text-muted">" Sched·uler " <span class="text-size-large">/ˈʃɛdjuːlə/</span></h2>
	                </div>
	                <footer><i>Nordlenning for "ka farsken?!"</i></footer>
	            </blockquote>
		        <div class="footer text-light-gray"><sup>&dagger;</sup> Kun testet med TimeEdit eksport</div>
	        </div>
	    </div>
	</div>

	<div class="row">
		<div class="col-lg-6">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Hvordan</h3>
				</div>
				<div class="box-body">
					<p>
						Schedulatoren leser .ics<sup>&dagger;</sup> filer som du serverer via en URL.
						Dersom kalenderfeeden inneholder forelesninger for flere emner/rom kan du
						spesifisere hvilke du ønsker &aring; schedulere og matche ulike recordere
						til ulike rom.
					</p>

					<p>
						Metadata kan leses ut av kalenderfeeden og f&oslash;res inn manuelt. For automatisk uttrekk
						er det viktig at feltene <code>SUMMARY</code>, <code>DESCRIPTION</code> og <code>LOCATION</code>
						benyttes i kalenderfeeden, eks:

					</p>

					<div class="well" style="font-family: 'Courier New', Courier, monospace">
						BEGIN:VEVENT <br/>
						&nbsp;&nbsp;&nbsp;DTSTART:20150219T090000Z<br/>
						&nbsp;&nbsp;&nbsp;DTEND:20150219T103000Z<br/>
						&nbsp;&nbsp;&nbsp;UID:245885--432227791-0@timeedit.com<br/>
						&nbsp;&nbsp;&nbsp;DTSTAMP:20150218T000930Z<br/>
						&nbsp;&nbsp;&nbsp;LAST-MODIFIED:20150218T000930Z<br/>
						&nbsp;&nbsp;&nbsp;<code>SUMMARY:ØKONOMI-101_VÅR_1\nB&oslash;r B&oslash;rson Jr.</code><br/>
						&nbsp;&nbsp;&nbsp;<code>LOCATION:KRAMBUA</code><br/>
						&nbsp;&nbsp;&nbsp;<code>DESCRIPTION:Hvordan bli generalmillionær på aksjespekulasjoner?</code><br/>
						END:VEVENT <br/>
					</div>

					<p>
						Hvilken informasjon som ligger i feltene <code>SUMMARY</code> og <code>DESCRIPTION</code> avhenger av
						hvordan .ics eksport i timeplansystemet er satt opp. Det er relativt enkelt &aring; endre p&aring; dette,
						s&aring; spander en kaffe p&aring; administrator for timeplansystemet dersom dere savner noe.
					</p>

					<p>
						Dersom klasserom/sted skal hentes ut forventer Schedulatoren at dette ligger i feltet <code>LOCATION</code>.
					</p>

					<p>
						<strong>Schedulatoren kan lese ut felter separert med komma (<code>\,</code> | <code>,</code>) eller nylinje (<code>\r\n</code> | <code>\r</code> | <code>\n</code>).</strong>
					</p>
				</div>

				<div class="box-footer">
					<sup>&dagger;</sup>Kun testet med .ics fra TimeEdit, men burde jo fungere med lignende feeds fra andre systemer.
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Navigering</h3>
				</div>
				<div class="box-body">
			        <p class="text-muted">
				        Nettleseren sine frem/tilbake knapper vil &oslash;delegge din scheduleringsopplevelse!
				        Bruk heller disse knappene p&aring; bunnen av hver side for &aring; bevege deg frem og tilbake i veiviseren:
			        </p>

			        <div class="pager" style="width: 150px;">
						<li class="previous disabled"><a>Forrige</a></li>
						<li class="next disabled"><a>Neste</a></li>
					</div>
				</div>

				<div class="box-footer">
					...
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">Forutsetninger: </h3>
				</div>
				<div class="box-body">
					<ul>
						<li>Du m&aring; ha URL til en (TimeEdit) kalender .ics fil</li>
						<li>
							Du m&aring; ha en folderstruktur i Mediasite som f&oslash;lger:
							<ul>
								<li>
									Mediasite
									<ul>
										<li>
											UNINETT
											<ul>
												<li>Schedulator-Templates</li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li>I folder <code>Schedulator-Templates</code> m&aring; du ha lagt til en eller flere Templates som du kan velge mellom. En slik Template styrer "default" innstillinger for en Schedule/Presentasjon.</li>
					</ul>
				</div>
				<div class="box-footer">
					<span class="ion ion-ios7-email-outline"></span> Sp&oslash;rsm&aring;l/ris/ros kan sendes til <a href="mailto:support@ecampus.no?subject=Mediasite Schedulator">support@ecampus.no</a>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="box box-info">
				<div class="box-header">
					<h3 class="box-title">Sesjonsinfo: </h3>
				</div>
				<div class="box-body">
					<p><strong>Tjeneste URI:</strong> <code><?php echo $auth->service_url(); ?></code> <sup>&dagger;</sup></p>
					<p><strong>API URI:</strong> <code><?php echo $auth->api_url(); ?></code> <sup>&dagger;</sup></p>
					<p><strong>API Bruker:</strong> <code><?php echo $_SESSION['mediasite_api_user']; ?></code></p>

					<p>
						Mediasite API Bruker er autentisert og kan snakke med APIet.
					</p>
				</div>
				<div class="box-footer">
					<em><sup>&dagger;</sup> Hentet fra UNINETTs tjenesteregister ("KIND")</em>
				</div>
			</div>
		</div>
	</div>
</section>