<?php get_header(); ?>

<div class="container mt-5">

    <a class="btn btn-dark m-2 text-white" href="/atlas/panel">ورود به پنل کاربری</a>

    <select id="select2" class="form-select form-select-lg mb-3" name="state" style="width: 100%;">
        <option></option>
        <?php
$iran = new Iran_Area;

$search = $iran->all_city();

foreach ($search as $key => $value):

?>
        <option value="<?=$key?>"><?=$value?></option>

        <?php endforeach; ?>

    </select>
</div>





<?php get_footer(); ?>