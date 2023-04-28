<?php

require "vendor/autoload.php";
require "download.php";


use App\QuestionManager;

$score = null;
try {
    $manager = new QuestionManager;
    $manager->initialize();


    if (!isset($_SESSION['answers'])) {
        throw new Exception('Missing answers');
    }
    $score = $manager->computeScore($_SESSION['answers']);
    $_SESSION['Qnumber'] = $manager -> getQuestionSize();
    $_SESSION['Score'] = $score;

}   catch (Exception $e) {
    echo '<h1>An error occurred:</h1>';
    echo '<p>' . $e->getMessage() . '</p>';
    exit;
}
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quiz</title>
</head>
<body>

<h1>Thank You</h1>

<p style="color: gray">
    You've completed the exam.
</p>

<h3>
    Congratulations <?php echo $_SESSION['user_fullname']; ?>!
    <?php echo "(". $_SESSION[ 'user_email' ]. ")"; ?>!<br>
    Your score is <?php echo $score; ?> out of <?php echo $manager->getQuestionSize() ;?><br>
<?php $ex = $manager->Question($_SESSION["answers"]);?> </h3>

<form action = "result.php" method ="POST">
<input type="submit" value="Click here to download the results." name="download">
</form>

</body>
</html>



<!-- DEBUG MODE -->
<pre>
<?php
var_dump($_SESSION);
var_dump($_POST);
?>
</pre>