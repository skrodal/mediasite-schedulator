<!-- Left side column. contains the logo and sidebar -->
<aside class="left-side sidebar-offcanvas">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<!-- /.search form -->
		<!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li id="dashboard" class="treeview active">
                <a style="cursor: default; color: #CACACA;">
                    <i class="ion ion-wand"></i> <span>VEIVISER</span>
                </a>
                <ul class="treeview-menu">
                    <?php
                        // Build sidebar index
                        foreach($PAGES_CONFIG as $page => $config){
                            echo    "<li>
                                        <a style='cursor: default; color: #CACACA;'>
                                            <i class='ion ion-ios7-arrow-right'></i> <span><i class='". $config["icon"] . "'></i>&nbsp;&nbsp;" . $config["title"] . "</span>
                                        </a>
                                    </li>";
                        }
                    ?>
                </ul>
            </li>
        </ul>

		<div id="test-alert" class="box box-solid bg-orange hidden">
			<div class="box-body">
				<p><strong>TESTMODUS!</strong></p>
				<code>TESTING == true;</code> i <code>wizard.js</code>
				<p>
					Ingen API-kall utf&oslash;res.
				</p>
			</div>
		</div>
	</section>
	<!-- /.sidebar -->
</aside>