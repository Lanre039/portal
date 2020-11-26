<?php require_once('../../private/initialize.php');

$errors = [];
if(!isset($_GET['id'])) {
    redirect_to(url_for('/index.php'));
}

$mentee_id = $_GET['id'];

if(is_post_request()) {

    if (empty($_POST['data'])) {
        $errors[] = 'Select Mentors';
    } else {
        $items = $_POST['data'];
        foreach($items as $item) {
            $data = ['mentee_id' => $mentee_id, 'mentor_id' => $item];
            $relationship = new MenteeMentor($data);
            $result = $relationship->save();

        }
        redirect_to(url_for('/pages/mentee.php?id=' . $mentee_id));     
    }
}

$mentors = Mentor::find_all();

?>


<?php $page_title = 'Select Mentor'; ?>
<?php  include(SHARED_PATH . '/header.php'); ?>

<div class="container">
    <div style="margin: 1.3rem 0;">
        <?php echo display_errors($errors); ?>
        <form action=" <?php echo url_for('/pages/select_mentor.php?id=' . $mentee_id); ?> " method="post">
            <?php foreach($mentors as $mentor) { ?>
            <div>
                <input type="checkbox" name='data[]' value=<?php echo $mentor->id ?>>
                <label for="data[]"><?php echo $mentor->full_name() ?> </label>
            </div>

            <?php } ?>
            <div>
                <button type="submit" class="submit_btn">Submit</button>
            </div>
        </form>

    </div>
    <?php  include(SHARED_PATH . '/footer.php'); ?>
</div>