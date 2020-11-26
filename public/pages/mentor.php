<?php require_once('../../private/initialize.php');

if(!isset($_GET['id'])) {
    redirect_to(url_for('/index.php'));
}

$id = $_GET['id'] ?? '1';

$mentor = Mentor::find_by_id($id);
?>


<?php $page_title = 'Mentor Page'; ?>
<?php  include(SHARED_PATH . '/header.php'); ?>

<div class="container">
    <div style="margin: 1.3rem 0;">

        <h2 class="mt">First Name <span style="font-weight: normal;"><?php echo $mentor->first_name ?></span></h2>
        <h2 class="mt">Last Name <span style="font-weight: normal;"><?php echo $mentor->last_name ?></span></h2>
        <h2 class="mt">Email <span style="font-weight: normal;"><?php echo $mentor->email ?></span></h2>
        <h2 class="mt">Role <span style="font-weight: normal;">Mentor</span></h2>

    </div>
    <?php  include(SHARED_PATH . '/footer.php'); ?>
</div>