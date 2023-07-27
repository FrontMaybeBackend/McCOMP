<?php
require_once("Zadanie3.php");

if (isset($_GET['id'])) {

    $surveyId = (int) $_GET['id'];
    $survey = null;

    foreach ($allSurveys as $s) {
        if ($s->surveyId === $surveyId) {
            $survey = $s;
            break;
        }
    }

    if ($survey) {

        echo "<h2>Nazwa Ankiety: {$survey->name}</h2>";
        echo "<p>Start ankiety: {$survey->getStartDate()->format('Y-m-d')}</p>";
        echo "<p>Koniec ankiety: {$survey->getEndDate()->format('Y-m-d')}</p>";
        foreach ($survey->questions as $question): ?>
            <li>
                <p><strong>
                        <?php echo $question->text; ?>
                    </strong></p>
                <?php if ($question instanceof YesNoQuestions): ?>
                    <!-- Pytanie typu Yes/No -->
                    <?php foreach ($question->options as $option): ?>
                        <input type="<?php echo $question->inputType; ?>" name="<?php echo $question->text; ?>"
                            value="<?php echo $option; ?>" />
                        <label for="<?php echo $question->text; ?>"><?php echo $option; ?></label>
                    <?php endforeach; ?>

                <?php elseif ($question instanceof OneToTen): ?>
                    <!-- Pytanie typu oceny 1-10 -->
                    <select name="<?php echo $question->text; ?>">
                        <?php foreach ($question->rating as $rate): ?>
                            <option value="<?php echo $rate; ?>"><?php echo $rate; ?></option>
                        <?php endforeach; ?>
                    </select>

                <?php elseif ($question instanceof OneToFive): ?>
                    <!-- Pytanie typu ocney 1-5 -->
                    <select name="<?php echo $question->text; ?> ">
                        <?php foreach ($question->rating as $rate): ?>
                            <option value="<?php echo $rate; ?>"><?php echo $rate; ?></option>
                        <?php endforeach; ?>
                    </select>


                <?php elseif ($question instanceof OpenQuestions): ?>
                    <!-- Pytanie otwarte -->
                    <textarea name="<?php echo $question->text; ?>" rows="4" cols="50" required></textarea>
                <?php endif; ?>

            </li>
        <?php endforeach;


    } else {
        echo "<p>Error: Nie ma takiej ankiety</p>";
    }
} else {
    echo "<p>Error: Ankieta niedostępna!</p>";
}
?>
<button>
    <a href="End.php">Prześlij ankietę</a>
</button>
<a href="Zadanie3.php"> Powrót do ankiet</a>