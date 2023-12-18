<?php

// see https://getbootstrap.com/docs/5.3/components/card/ for customization
function cardHeader($title, $isPrimary = true)
{
    $text = htmlspecialchars($title);
    if ($isPrimary) {
        echo "<div class='card border-dark h-100'>";
        $text = "<b>$text</b>";
    } else {
        echo "<div class='card h-100'>";
    }
    echo "
        <div class='card-header text-center'>
            $text
        </div>
    ";
    if ($isPrimary) {
        echo "<div class='card-body'>";
    } else {
        echo "<div class='card-body text-secondary'>";
    }
}

function cardFooter()
{
    echo "</div>";
    echo "</div>";
}
