<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<h2>Namen der Kategorie ändern</h2>
<form id="renameCategory" method="post" action="/action/cateory_rename.php" class="validate">
    <div class="form-field form-required term-name-wrap">
        <label for="tag-name">Name</label>
        <input name="tag-name" id="tag-name" type="text" value="" size="40" aria-required="true">
    </div>
    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Neue Kategorie erstellen">
    </p>
</form>