<?php

/*
Chcemy stworzyć aplikację do ankietowania klientów.
Proszę zaprojektować strukturę DTO dla takiej aplikacji. 
Celem zadania nie jest zaprojektowanie bazy danych, lecz struktury obiektów, które będą zwracane na frontend podczas żądań do API. Choć oczywiście do pewnego stopnia te rzeczy są podobne.
Oto główne założenia aplikacji:

Pytań może być wiele rodzajów:
- pytania otwarte
- pytania tak / nie
- pytania tak / nie / nie wiem
- ocena w skali 1 - 10 (w tym skrajne odpowiedzi mają swoje opisy, np. dobrze/źle lub szybko/wolno)
- ocena w skali 1 - 5

Jedna ankieta może składać się z dowolnej ilości pytań dowolnego rodzaju.

Ankiet w całej aplikacji może być dużo. Ankety mają swój czas trwania (data od - data do)

Aplikacja z założenia tworzy ankiety anonimowe, tzn. nie trzeba się autoryzować aby odpowiedzieć na pytanie.

Proszę nie implementować żadnej funkcjonalności, wystarczą same klasy. Mile widziane jest użycie klas abstrakcyjnych.
*/


class QuestionsDTO
{

    public $type;
    public $text;
    public function __construct($type, $text)
    {
        $this->type = $type;
        $this->text = $text;
    }
}

class OpenQuestions extends QuestionsDTO
{

    public function __construct(string $text)
    {
        parent::__construct('open', $text);
    }

}

class YesNoQuestions extends QuestionsDTO
{
    public $inputType;
    public $options;

    public function __construct($text, $inputType, $options)
    {
        parent::__construct('yes_no', $text);
        $this->inputType = $inputType;
        $this->options = $options;
    }
}

class YesNoUnkownQuestions extends YesNoQuestions
{

    public $unknown;
    public function __construct($unkown, $options, $text)
    {
        parent::__construct($options, 'nie wiem', $text);
        $this->unknown = $unkown;
    }
}

class OneToTen extends QuestionsDTO
{
    public $rating;

    public function __construct(array $rating, $text)
    {
        parent::__construct('ocena', $text);
        $this->rating = $rating;
    }
}

class OneToFive extends QuestionsDTO
{
    public $rating;

    public function __construct(array $rating, $text)
    {
        parent::__construct('ocena', $text);
        $this->rating = $rating;
    }
}


interface SurveyData
{
    public function getStartDate(): DateTime;
    public function getEndDate(): DateTime;
}

class SurveyDTO implements SurveyData
{
    public string $name;
    public int $surveyId;
    public array $questions;
    public DateTime $startDate;
    public DateTime $endDate;

    public function __construct(string $name, int $surveyId, array $questions, DateTime $startDate, DateTime $endDate)
    {
        $this->name = $name;
        $this->surveyId = $surveyId;
        $this->questions = $questions;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }
}

$questions = [
    new OpenQuestions('Jakie jest Twoje ulubione jedzenie?'),
    new YesNoQuestions('Studiujesz?', 'radio', ['Tak', 'Nie']),
    new YesNoQuestions('Czy jesteś zadowolony z naszych usług?', 'radio', ['Tak', 'Nie', 'Nie wiem']),
    new OneToTen(([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]), 'Oceń jakość naszego produktu.'),
    new OneToFive(([1, 2, 3, 4, 5]), "Oceń nasz zespół"),
];

$startDate = new DateTime('2023-07-01');
$endDate = new DateTime('2023-07-31');

$survey1 = new SurveyDTO("testowa1", 1, $questions, $startDate, $endDate);



//Wszystkie ankiety 
$allSurveys = [];

//Przypisanie 1 ankiety do tablicy
$allSurveys[] = $survey1;

// Tworzymy obiekt SurveyDTO i dodajemy go do tablicy pytań
$questions1 = [
    new OpenQuestions('Jakie jest Twoje ulubione danie?'),
    new YesNoQuestions('Czy jesteś zadowolony z naszych usług?', 'radio', ['Tak', 'Nie']),
    new OneToTen(([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]), 'Oceń jakość naszego produktu.'),
    new YesNoQuestions('Czy jesteś zadowolony z naszych usług?', 'radio', ['Tak', 'Nie', 'Nie wiem']),
    new OneToFive(([1, 2, 3, 4, 5]), "Oceń nasz sklep?"),
];

$startDate1 = new DateTime('2023-07-01');
$endDate1 = new DateTime('2023-07-31');

$survey2 = new SurveyDTO("testowa2", 2, $questions1, $startDate1, $endDate1);

// Dodajemy ankietę2 do tablicy ankiet
$allSurveys[] = $survey2;

// Dodajemy kolejne ankiety do tablicy pytań
$questions2 = [
    new OpenQuestions('Jakie jest Twoje ulubione danie?'),
    new YesNoQuestions('Czy jesteś zadowolony z naszych usług?', 'radio', ['Tak', 'Nie']),
    new OneToTen(([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]), 'Oceń jakość naszego produktu.'),
    new YesNoQuestions('Czy jesteś zadowolony z naszych usług?', 'radio', ['Tak', 'Nie', 'Nie wiem']),
    new OneToFive(([1, 2, 3, 4, 5]), "Oceń naszą pomoc"),
];

//Czas trwania ankiety
$startDate2 = new DateTime('2023-08-01');
$endDate2 = new DateTime('2023-08-31');

$survey3 = new SurveyDTO("testowa3", 3, $questions2, $startDate2, $endDate2);
$allSurveys[] = $survey3;


?>
<ul>
    <h1>Dostępne Ankiety </h1>
    <?php foreach ($allSurveys as $survey): ?>
        <li>

            <a href="Surveys.php?id=<?php echo $survey->surveyId; ?>">
                <?php echo $survey->name; ?> Ważna do - <?php echo $survey->getEndDate()->format('Y-m-d'); ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>