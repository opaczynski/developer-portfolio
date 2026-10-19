<?php
ob_start();
require "./ipregion.php";
ob_end_clean();

$filePath = __DIR__ . '/translations.json';
$jsonContent = file_get_contents($filePath);
$data = json_decode($jsonContent, true);

$lang = (!isset($_GET['lang']) || !$_GET['lang'] ? strtolower($country) : strtolower($_GET['lang'])) == "pl" ? "pl" : "en";
$isEn = ($lang === 'en');
$enLabel = $isEn ? "[ ENGLISH ]" : "ENGLISH";
$plLabel = !$isEn ? "[ POLSKI ]" : "POLSKI";

$navbar = $data["navbar"][$lang];
$featuredProjectsName = $navbar["featuredProjects"];
$labName = $navbar["lab"];

$hero = $data["hero"][$lang];
$heroName = $hero["title"];
$heroDescription = $hero["description"];

$navbarValues = [
    [
        'id' => "featuredProjects",
        'table' => $data["featuredProjects"][$lang]
    ],
    [
        'id' => "lab",
        'table' => $data["lab"][$lang]
    ]
];

$aboutAria = $data["aboutAria"][$lang];
$aboutAriaTitle = $aboutAria["title"];
$aboutAriaDescription = $aboutAria["description"];
$aboutAriaCredit = $aboutAria["credit"];

$aboutAriaName = $navbar["aboutAria"];

$footerText = $data["footer"][$lang]["toTop"];

echo "<noscript>";
echo "<center><a href='?lang=#top'><h1 id='top'>Jakub Opaczyński</h1></a></center>";
echo "<center>";
echo "<a href='#featuredProjects'>${featuredProjectsName}</a> • <a href='#lab'>${labName}</a> • <a href='#aboutAria'>${aboutAriaName}</a>  • ";
echo "<a href='?lang=en'>${enLabel}</a> | <a href='?lang=pl'>${plLabel}</a>";
echo "</center>";
echo "<hr>";

echo "<div>";
echo "<center><h2><strong>${heroName}</strong></h2>";
for ($i = 0; $i < count($heroDescription); $i++) { 
    $el = $heroDescription[$i];
    echo "<p>${el}</p>";
}
echo "</center></div>";

for ($i = 0; $i < count($navbarValues); $i++) {
    $elData = $navbarValues[$i];
    $elID = $elData["id"];
    $elTable = $elData["table"];
    
    $elName = $elTable["title"];
    $elFeatures = $elTable["features"];
    $elLessons = $elTable["lessons"];
    $elProjects = $elTable["projects"];

    echo "<hr>";
    echo "<div id='${elID}'>";
    echo "<center><h2><strong>${elName}</strong></h2></center>";
    
    for ($j = 0; $j < count($elProjects); $j++) { 
        $articleData = $elProjects[$j];
        $articleName = $articleData["title"];
        $articleURL = $articleData["url"];
        
        if (!empty($articleData["urlAlt"])) {
            $urlName = $articleData["urlAlt"];
        } else {
            $urlName = ($articleData["urlIcon"] === "github") 
                ? ($isEn ? "GitHub Repository" : "Repozytorium GitHub") 
                : ($isEn ? "Link to project" : "Odnośnik do projektu");
        }

        $articleDescription = $articleData["description"];
        $articleFeatures = $articleData["features"] ?? [];
        $articleLessons = $articleData["lessons"] ?? [];
        
        echo "<center><h2><strong>${articleName}</strong> | <a href='${articleURL}' target='_blank'>${urlName}</a></h2></center>";
        echo "<p>${articleDescription}</p>";
        
        if (!empty($articleFeatures)) {
            echo "<p><i>${elFeatures}:</i></p><ul>";
            for ($k = 0; $k < count($articleFeatures); $k++) {
                $featuresText = $articleFeatures[$k];
                echo "<li>${featuresText}</li>";
            }
            echo "</ul>";
        }
        
        if (!empty($articleLessons)) {
            echo "<p><i>${elLessons}:</i></p><ul>";
            for ($k = 0; $k < count($articleLessons); $k++) {
                $lessonsText = $articleLessons[$k];
                echo "<li>${lessonsText}</li>";
            }
            echo "</ul>";
        }
    }

    echo "</div>";
}

echo "<hr>";
echo "<div id='aboutAria'>";
echo "<center><h2><strong>${aboutAriaTitle}</strong></h2></center>";
for ($i = 0; $i < count($aboutAriaDescription); $i++) { 
    $el = $aboutAriaDescription[$i];
    echo "<p>${el}</p>";
}
echo "<p><i>${aboutAriaCredit}:</i> <a href='https://vgen.co/Kaibeexx' target='_blank'>Kaibeexx</a></p>";
echo "</div>";

echo "<hr>";
echo "<div>";
echo "<center><a href='#top'>${footerText}</a></center>";
echo "</div>";
echo "</noscript>";
?>