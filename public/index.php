<?php 

    require_once('../private/initialize.php');
    
    $errors = [];
    if (is_post_request()) {
        $data = $_POST['data'];
        
        if (!isset($data['first_name']) || !trim($data['first_name'])) {
            $errors[] = 'The first name field is required.';
        }
        if (!isset($data['last_name']) || !trim($data['last_name'])) {
            $errors[] = 'The last name field is required.';
        }
        if (!isset($data['email']) || !trim($data['email'])) {
            $errors[] = 'The email field is required.';
        }
        
        if (!isset($data['password']) || !trim($data['password'])) {
            $errors[] = 'The password field is required.';
        }
        
        if (!isset($data['role'])) {
            $errors[] = 'The role field is required.';
        }
        if(empty($errors)) {
      
            if($data['role'] == 'mentor') {
                $mentor = new Mentor($data);
                $result = $mentor->save();
                if($result === true) {
                    $new_id = $mentor->id;
                    redirect_to(url_for('/pages/mentor.php?id=' . $new_id));
                } else {
                    // show errors
                    redirect_to(url_for('/index.php'));
                  }   
            }
            
            
            if($data['role'] == 'mentee') {
                $mentee = new Mentee($data);
                $result = $mentee->save();
                if($result === true) {
                    $new_id = $mentee->id;
                    redirect_to(url_for('/pages/select_mentor.php?id=' . $new_id));
                } else {
                    // show errors
                    redirect_to(url_for('/index.php'));
                  }   
            }
            
            
  
        } else {
            $errors[] = 'Sign up was not successful.';
        }
        
    }
    
        
?>


<?php $page_title = 'Sign up'; ?>
<?php  include(SHARED_PATH . '/header.php'); ?>
<div class="container">
    <div>
        <h1>Sign up</h1>
        <?php echo display_errors($errors); ?>
        <form action=" <?php echo url_for('index.php'); ?> " method="post">
            <div>
                <label for="data[first_name]">First Name</label>
                <input type="firstName" name="data[first_name]" id="">
            </div>
            <div>
                <label for="data[last_name]">Last Name</label>
                <input type="lastName" name="data[last_name]" id="">
            </div>
            <div>
                <label for="data[email]">Email</label>
                <input type="email" name="data[email]" id="">
            </div>
            <div>
                <label for="data[password]">Password</label>
                <input type="password" name="data[password]" id="">
            </div>
            <div>
                <label for="data[role]">Password</label>
                <select name="data[role]">
                    <option>mentor</option>
                    <option>mentee</option>
                </select>
            </div>
            <div>
                <button type="submit" class="submit_btn">Submit</button>
            </div>
        </form>
    </div>
    <?php  include(SHARED_PATH . '/footer.php'); ?>
</div>