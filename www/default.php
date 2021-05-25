<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<?php require_once 'includes/meta.php'; ?>    
  </head>
  <body>
	<?php require_once 'includes/header.php'; ?>	
    <div class="slim-mainpanel">
      <div class="container">
        <div class="slim-pageheader">
          <h6 class="slim-pagetitle">Dashboard</h6>
        </div><!-- slim-pageheader -->
        <div class="section-wrapper">
            <p class="mg-b-20 mg-sm-b-20">Welcome to dashboard of <b>Mzimhle Mosiwe's</b> assessment website.</p>
			<div class="row">
				<div class="col-lg-12">
					<div class="card card-dash-one mg-b-20">
					  <div class="row no-gutters">
						<div class="col-lg-6">
						  <div class="dash-content">
							<a href="/member/">
							<label class="tx-warning">Members</label>
							<p>View / search, add, update or delete members</p>
							</a>
						  </div><!-- dash-content -->
						</div><!-- col-3 -->
						<div class="col-lg-6">
						  <div class="dash-content">
							<a href="/member/">
							<label class="tx-success">Animal</label>
							<p>View / search, add, update or delete animals</p>
							</a>
						  </div><!-- dash-content -->
						</div><!-- col-3 -->					
					  </div><!-- row -->
					</div>
				</div>
			</div><!-- row -->
		  <!-- table-wrapper -->
        </div><!-- section-wrapper -->
      </div><!-- container -->
    </div><!-- slim-mainpanel -->
	<?php require_once 'includes/footer.php'; ?>
  </body>
</html>
