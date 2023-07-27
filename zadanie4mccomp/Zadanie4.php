<?php

/*
Prosze napisać prosty program, który będzie pomagał grupie znajomych umówić się na konkretną godzinę.

Każda z osób wywoła nasz skrypt z parametrami oznaczającymi jego imię i preferowaną godzinę, np.
http://serwer/Umow.php?kto=KamilS&godzina=12:00

Strona, która się otworzy powinna pokazywać jak zagłosowali inni koledzy, np:

16:00 - 3 osoby (Michał, Arek, Kasia)
16:30 - 2 osoby (KamilC, Łukasz)
12:00 - 1 osoba (kamilS)

Mile widziane jest grupowanie odpowiedzi i sortowanie ich.
Wejście na stronę drugi raz przez tą samą osobę ma zmienić jego głos a nie dodać nowy.

Do przechowywania danych wystarczy prosty plik tekstowy na serwerze, proszę wybrać najprostszą dla siebie metodę.

Z powodu ogranicznonego czasu na wykonanie nie jest wymagane dopracowanie wyglądu strony, oraz wprowadzanie javascript.
*/

//Funkcja do odczytu danych z pliku.

// Funkcja do odczytywania danych z pliku tekstowego
$filename = 'spotkania.txt';

// Funkcja do odczytywania danych z pliku tekstowego
function readDataFromFile($filename)
{
    if (file_exists($filename)) {
        $data = file_get_contents($filename);
        return json_decode($data, true);
    } else {
        return [];
    }
}

// Funkcja do zapisywania danych do pliku tekstowego
function writeDataToFile($filename, $data)
{
    $jsonData = json_encode($data);
    file_put_contents($filename, $jsonData);
}

// Sprawdzamy, czy plik istnieje, jeśli nie, tworzymy go z pustą tablicą
if (!file_exists($filename)) {
    writeDataToFile($filename, []);
}

// Obsługa formularza głosowania
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['kto']) && isset($_POST['godzina'])) {
        $name = $_POST['kto'];
        $preferredTime = $_POST['godzina'];

        // Odczytujemy dane z pliku
        $meetupData = readDataFromFile($filename);

        // Sprawdzamy, czy osoba już głosowała, jeśli tak, zmieniamy jej preferowaną godzinę
        $found = false;
        foreach ($meetupData as &$entry) {
            if ($entry['name'] === $name) {
                $entry['preferredTime'] = $preferredTime;
                $found = true;
                break;
            }
        }

        // Jeśli osoba nie głosowała wcześniej, dodajemy nowy wpis do danych
        if (!$found) {
            $meetupData[] = ['name' => $name, 'preferredTime' => $preferredTime];
        }

        // Zapisujemy zaktualizowane dane do pliku
        writeDataToFile($filename, $meetupData);

        //Wyświetlanie w url podanych danych
        header("Location: http://localhost:8000//Zadanie4.php?kto=$name&godzina=$preferredTime");
        exit;
    }
}

// Odczytujemy dane z pliku po ewentualnej aktualizacji
$meetupData = readDataFromFile($filename);

// Grupujemy odpowiedzi i sortujemy wg preferowanych godzin
$groupedData = [];
foreach ($meetupData as $entry) {
    $preferredTime = $entry['preferredTime'];
    if (!isset($groupedData[$preferredTime])) {
        $groupedData[$preferredTime] = [];
    }
    $groupedData[$preferredTime][] = $entry['name'];
}

// Sortujemy wyniki po preferowanych godzinach
ksort($groupedData);

// Wyświetlamy wyniki
foreach ($groupedData as $time => $people) {
    $count = count($people);
    $names = implode(', ', $people);
    echo "$time - $count osoby";
    echo $count !== 1 ? 'y' : '';
    echo " ($names)<br>";
}
?>

<!-- Formularz głosowania -->
<form method="POST">
    Imię: <input type="text" name="kto" pattern="^[A-Za-z]+$" required>
    Preferowana godzina: <input type="text" name="godzina" pattern="^\d{2}:\d{2}$" required>
    <input type="submit" value="Zagłosuj">
</form>