<?php

require "vendor/autoload.php";

session_start();

// 3.

use App\QuestionManager;

$number = null;
$question = null;

try {
    $manager = new QuestionManager;
    $manager->initialize();

    if (isset($_SESSION['is_quiz_started'])) {
        $number = $_SESSION['current_question_number'];
    } else {
        // Marker for a started quiz
        $_SESSION['is_quiz_started'] = true;
        $_SESSION['answers'] = [];
        $number = 1;
    }

    if (isset($_POST['answer'])) {
        $_SESSION['answers'][$number] = $_POST['answer'];
        $number++;
    }

    // Has user answered all items
    if ($number > $manager->getQuestionSize()) {
        header("Location: result.php");
        exit;
    }

    // Marker for question number
    $_SESSION['current_question_number'] = $number;

    $question = $manager->retrieveQuestion($number);
} catch (Exception $e) {
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

<h1>Analogy Questions</h1>

<h3>Instructions</h3>

<p style="color: gray">
    There is a certain relationship between two given words on one side of : : and one word is given on another side of : : while another word is to be found from the given alternatives, having the same relation with this word as the words of the given pair bear. Choose the correct alternative.
</p>


<h1>Question #<?php echo $question->getNumber(); ?></h1>
<h2 style="color: blue"><?php echo $question->getQuestion(); ?></h2>
<h4 style="color: blue">Choices</h4>
<form method="POST" action="quiz.php">
<input type="hidden" name="number" value="<?php echo $question->getNumber();?>" />

<?php foreach ($question->getChoices() as $choice): ?>

<input
    type="radio"
    name="answer"
    value="<?php echo $choice->letter; ?>" />
    <?php echo $choice->letter; ?>)
<?php echo $choice->label; ?><br />

<?php endforeach; ?>

<input type="submit" value="Next">
</form>

</body>
</html>

<!-- DEBUG MODE -->
<pre>
<?php
var_dump($_SESSION);
?>
</pre>

//result.php

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
