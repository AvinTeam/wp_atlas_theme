
<div class="container mt-5">
    <form accept="" method="POST" enctype="multipart/form-data">

        <div class="form-group mt-2">
            <label for="title">نام</label>
            <input type="text" class="form-control mt-2" id="title" name="first_name" value="<?php echo $this_user->first_name ?>">
        </div>

        <div class="form-group mt-2">
            <label for="title">نام خانوادگی </label>
            <input type="text" class="form-control mt-2" id="title" name="last_name" value="<?php echo $this_user->last_name ?>">
        </div>
        <?php wp_nonce_field('atlas_nonce_user_submit' . get_current_user_id()); ?>

        <div class="form-group mt-2">
            <button type="submit" class="btn btn-primary" name="act_user" value="form_submit_profile">ارسال</button>
        </div>
    </form>
</div>

