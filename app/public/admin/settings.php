<?php

session_start();

if(empty($_SESSION['username'])) {
    header('Location:login.php');
    die('Redirecting to the login page...');
}

include('inc/functions.php');

require_once '../../inc/databaseClass.php';
require_once '../../inc/settingsClass.php';
require_once '../../inc/accessClass.php';

$database = new Database();

$access_obj = new Access($database);
$settings_obj = new Settings($database);

if(isset($_POST['settings_save'])) {

    $authorization = $access_obj->is_authorized("settings", "update", NULL);

    if(!$authorization) {
        echo json_encode(["status" => false, "result" => "Access denied"]);
        exit();
    }

    $update_all = ["status" => true, "result" => "All options updated"];

    $data = [
        'site_title' => $_POST['site_title'],
        'site_tagline' => $_POST['site_tagline'],
        'thumbnail_size' => $_POST['thumbnail_size'],
    ];

    foreach($data as $key => $value) {
        $update_key = $settings_obj->update_option($key, $value);
        if(!$update_key["result"]) {
            $update_all = ["status" => false, "result" => $update["result"]];
            break;
        }
    }

    echo json_encode($update_all);
    exit();
}

$current_settings = [
    'site_title' => $settings_obj->get_option('site_title')['result']['option_value'],
    'site_tagline' => $settings_obj->get_option('site_tagline')['result']['option_value'],
    'thumbnail_size' => $settings_obj->get_option('thumbnail_size')['result']['option_value']
];

get_template('header');
get_template('topbar');

// Access Control

$authorization = $access_obj->is_authorized('settings', 'read', NULL);

?>

<div class="container-fluid text-left">
    <div class="row position-relative">
        <?php
        get_template('sidebar');
        ?>

        <div class="editor-middle col-md-10 bg-light-subtle">
            <?php if(empty($authorization)) : ?>
                <div class="alert alert-danger">You don't have enough permissions to access this resource</div>
                <?php get_template('footer'); ?>
                <?php exit(); ?>
            <?php endif; ?>

            <h1 class="fs-2 pb-3 pt-3 border-bottom mb-3">Settings</h1>

            <form action="" id="site-settings-form">
                <div class="mb-3">
                    <label for="site-title" class="form-label">Site Title</label>
                    <input type="text" name="site-title" id="site-title" class="form-control" value="<?= $current_settings['site_title'] ?>">
                </div>

                <div class="mb-3">
                    <label for="site-tagline" class="form-label">Site Tagline</label>
                    <input type="text" name="site-tagline" id="site-tagline" class="form-control" value="<?= $current_settings['site_tagline'] ?>">
                </div>

                <div class="mb-3">
                    <label for="thumbnail-size" class="form-label">Thumbnail Size</label>
                    <input type="number" name="thumbnail-size" id="thumbnail-size" class="form-control" value="<?= $current_settings['thumbnail_size'] ?>">
                </div>

                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>

        </div>
    </div>
</div>

<?php

get_template('footer');