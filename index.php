<?php

define( 'ABSPATH', dirname( __FILE__ ) . '/' );

require_once( 'includes/config_validation.php' );

?>
<!DOCTYPE html>
<html lang="de">
<?php include( 'includes/head.php' ); ?>
<body>
<header class="bd-header bg-dark py-3 d-flex align-items-stretch border-bottom border-dark">
    <div class="container-fluid d-flex align-items-center">
        <h1 class="d-flex align-items-center fs-4 text-white mb-0">
			<?php echo FORM_DATA->get_title(); ?>
        </h1>
    </div>
</header>
<div class="container py-3">
	<?php
	if ( FORM_DATA->get_description() ) {
		printf( '<p class="lead">%s</p>', FORM_DATA->get_description() );
	}
    if( defined( 'BOOTSTRAP_ALERT' ) ){
        echo BOOTSTRAP_ALERT;
    }
	?>
    <form action="index.php" method="post">
		<?php
		foreach ( FORM_DATA->get_items() as $item ) {
			$item->render();
		}
		?>
        <div class="row mt-4">
            <hr/>
            <div class="col-12 mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <label for="email" class="form-label">E-Mail</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <label for="phone" class="form-label">Telefon Nummer</label>
                <input type="text" minlength="10" maxlength="20" name="phone" id="phone" class="form-control" required>
            </div>
            <div class="col-12 mb-3">
                <label for="comments" class="form-label">Anmerkungen</label>
                <textarea name="comments" id="comments" class="form-control" required></textarea>
            </div>
            <div class="col-12 text-end mb-3">
                <button type="submit" name="submit" class="btn btn-primary">Absenden</button>
            </div>
        </div>
    </form>
</div>
</body>
</html>

