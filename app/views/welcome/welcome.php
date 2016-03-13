<?php

use Core\Language;

?>

<div class="page-header">
	<h1><?php echo $data['title'] ?></h1><form action="" method="POST">
<input type="text" name="test" value="1111111111111111111111111111111">
<input type="submit" value="123">
</form>
</div>

<p><?php echo $data['welcome_message'] ?></p>

<a class="btn btn-md btn-success" href="<?php echo DIR;?>subpage">
	<?php echo Language::show('open_subpage', 'welcome'); ?>
</a>

