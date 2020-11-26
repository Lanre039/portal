<?php require_once('../../private/initialize.php');

if(!isset($_GET['id'])) {
    redirect_to(url_for('/index.php'));
}

$mentors = [];
$id = $_GET['id'];
$mentee = Mentee::find_by_id($id);
$assigned_mentor = MenteeMentor::find_by_mentee_id($id);
if (!empty($assigned_mentor)) {
    foreach ($assigned_mentor as $mentor) {
        $details = Mentor::find_by_id($mentor->mentor_id);
        array_push($mentors, $details);
    }

}

?>


<?php $page_title = 'Mentee Page'; ?>
<?php  include(SHARED_PATH . '/header.php'); ?>

<div class="container">
    <div style="margin: 1.3rem 0;">

        <h2 class="mt">First Name <span style="font-weight: normal;"><?php echo $mentee->first_name ?> </span></h2>
        <h2 class="mt">Last Name <span style="font-weight: normal;"><?php echo $mentee->last_name ?></span></h2>
        <h2 class="mt">Email <span style="font-weight: normal;"><?php echo $mentee->email ?></span></h2>
        <h2 class="mt">Role <span style="font-weight: normal;">Mentee</span></h2>
        <div>
            <h2 style="margin: 1.5rem 0">Mentors</h2>
            <?php if (!empty($mentors)) { ?>
            <?php foreach ($mentors as $item) { ?>
            <P style="margin: 1rem 0"> <?php echo $item->full_name() ?> </P>
            <?php } ?>
            <p> <?php  ?> </p>
            <?php }; ?>
        </div>
    </div>
    <?php  include(SHARED_PATH . '/footer.php'); ?>
</div>