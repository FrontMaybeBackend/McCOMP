<?php
/*

Poniższy program wyświetla na ekranie wyniki konkursu dla dzieci. Wyniki są w tablicy quizResults. Zmienna thershold to próg punktowy, który należy osiągnąć by znaleźć się wśród zwycięzców.
W pętli usuwane są rekordy, które nie spełniły tego warunku.
Na końcu wyświetlana jest lista. Pomimo, że program dobrze obliczył liczbę zwycięzców, lista jest pokazywana niepoprawnie:

1) Ania - 75 pkt,
2)  -  pkt,
3)  -  pkt,
4) Kasia - 81 pkt,
5)  -  pkt,
6) Kamil - 63 pkt,

Proszę poprawić program tak, aby podawał właściwe wyniki.

 */
$quizResults = [
    ["Ania", 75],
    ["Piotrek", 32],
    ["Asia", 43],
    ["Kasia", 81],
    ["Bartek", 11],
    ["Kamil", 63],
    ["Ola", 76],
    ["Ludmiła", 49],
    ["Tosia", 92],
    ["Krzyś", 89],
];

$thershold = 50;

$contestantsCount = count($quizResults);
for ($n = 0; $n < $contestantsCount; $n++) {
    if ($quizResults[$n][1] < $thershold) {
        unset($quizResults[$n]);
    }

    //Problem jest w tym, że gdy robimy unset na tablicy, to indeks się nie przesuwa automatycznie.
}
$quizResults = array_values($quizResults);

$winnersCount = count($quizResults);
print "Oto lista laureatów konkursu dla dzieci. Należało zdobyć przynajmneij $thershold punktów i warunek spełniło $winnersCount osób. A oto zwycięzcy:\n\n";
for ($n = 0; $n < $winnersCount; $n++) {
    print($n + 1) . ") " . $quizResults[$n][0] . " - " . $quizResults[$n][1] . " pkt,\n";
}