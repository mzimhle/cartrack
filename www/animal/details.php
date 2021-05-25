<?php
/* Add this on all pages on top. */
set_include_path($_SERVER['DOCUMENT_ROOT'].'/'.PATH_SEPARATOR.$_SERVER['DOCUMENT_ROOT'].'/library/classes/');
// Get the class file
require_once 'request.php';
// Get the object
$requestObject = new Request('animal');
// Check if we are updating
if(isset($_GET['id']) && trim($_GET['id']) != '') {

	$id			= trim($_GET['id']);
	$animalData	= $requestObject->getId($id);
	// Check if we all good
	if(!$animalData) {
		header('Location: /animal/');
		exit;		
	}
}
/* Check posted data. */
if(count($_POST) > 0) {

	$errors	= array();
	$data	= array();

	if(!isset($_POST['name'])) {
		$errors[] = 'Please add name of the animal';	
	} else if(trim($_POST['name']) == '') {
		$errors[] = 'Please add name of the animal';	
	}

	if(count($errors) == 0) {
		/* Add the details. */
		$data			= array();				
		$data['name']	= trim($_POST['name']);
		/* Insert or update. */
		if(!isset($animalData)) {
			// Insert
			$success = $requestObject->insert($data);
		} else {
			// Update
			$success = $requestObject->update($data, $animalData['id']);				
		}
		// Check if all is good.
		if((int)$success['code'] != 200) {
			$errors[] = $success['message'];	
		}
	}
	/* Check errors and redirect if there are non. */
	if(count($errors) == 0) {
		header('Location: /animal/');
		exit;
	} else {
		$errors = implode('<br />', $errors);
	}
}
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
          <ol class="breadcrumb slim-breadcrumb">
			<?php if(isset($animalData)) { ?>
			<li class="breadcrumb-item active" aria-current="page">Edit</li>
			<li class="breadcrumb-item"><?php echo $animalData['name']; ?></li>
			<?php } else { ?>
			<li class="breadcrumb-item active" aria-current="page">Add</li>
			<?php } ?>
			<li class="breadcrumb-item"><a href="/animal/">Animal</a></li>
            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
          </ol>
          <h6 class="slim-pagetitle">Animal</h6>
        </div><!-- slim-pageheader -->
        <div class="section-wrapper">
			<label class="section-title"><?php echo (isset($animalData) ? 'Update animal' : 'Add animal'); ?></label>
			<p class="mg-b-20 mg-sm-b-10">Below is where you add or update the animal's details</p>		
          <div class="row">
			<div class="col-md-12 col-lg-12 mg-t-20 mg-md-t-0-force">
            <?php if(isset($errors)) { ?><div class="alert alert-danger" role="alert"><strong><?php echo $errors; ?></strong></div><?php } ?>
            <form action="/animal/details.php<?php echo (isset($animalData) ? '?id='.$animalData['id'] : ''); ?>" method="POST">
                <div class="row">					
                    <div class="col-sm-12">			  
                        <div class="form-group has-error">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" class="form-control" value="<?php echo (isset($animalData) ? $animalData['name'] : ''); ?>" />
                            <code>Please add the name of the animal</code>									
                        </div>
                    </div>
                </div>			
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-actions text">
                            <input type="submit" value="<?php echo (isset($animalData) ? 'Update' : 'Add'); ?>" class="btn btn-primary">
                        </div>
                    </div>
                </div>
            </form>
            </div><!-- col-4 -->
          </div><!-- row -->
        </div><!-- section-wrapper -->
      </div><!-- container -->
    </div><!-- slim-mainpanel -->
	<?php require_once 'includes/footer.php'; ?>	
  </body>
</html>
