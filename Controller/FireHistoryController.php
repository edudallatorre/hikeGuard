<?php

class FireHistoryController {
    public function processFormSubmission() {
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["selectedYear"])) {
            $selectedYear = $_GET["selectedYear"];
            $this->fetchStudies($selectedYear);
        }
    }

    public function fetchStudies($year) {
        $apiBase = "https://www.ncei.noaa.gov/access/paleo-search/study/search.json?";
        $reqParams = "earliestYear={$year}&latestYear=0&timeFormat=BP&timeMethod=overEntire&dataTypeId=12&limit=10";
        $reqStr = $apiBase . $reqParams;

        $response = file_get_contents($reqStr);
        $json_data = json_decode($response, true);

        if (isset($json_data['study'])) {
            $this->displayStudies($json_data['study']);
        } else {
            echo "<p>No data available for the selected year.</p>";
        }
    }

    public function displayStudies($studies) {
        foreach ($studies as $study) {
            echo "<div class='studyCard'>";
            echo "<h2>{$study['studyName']}</h2>";
            echo "<p><strong>DOI:</strong> <a href=\"{$study['doi']}\" target=\"_blank\">{$study['doi']}</a></p>";
            echo "<p><strong>Investigators:</strong> {$study['investigators']}</p>";
            echo "<p><strong>Online Resource:</strong> <a href=\"{$study['onlineResourceLink']}\" target=\"_blank\">{$study['onlineResourceLink']}</a></p>";
            echo "</div>";
        }
    }
}

?>
