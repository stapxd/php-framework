<?php 
use Vendor\General\Session; 
?>

<div>
    <?php
        $errors = Session::flash('errors');
        if($errors){
        foreach($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }}
    ?>
</div>