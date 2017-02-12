<?php
    $data = json_decode(file_get_contents("content.json"), true);

    $content = "";
    $script = "";
    foreach($data as $d) {
        $date = $d["date"];
        $s = $d["suffix"];
        $one = (empty($d["1"]) ? "&nbsp;" : $d["1"]);
        $two = (empty($d["2"]) ? "&nbsp;" : $d["2"]);
        $three = (empty($d["3"]) ? "&nbsp;" : $d["3"]);
        $entry = "<div class='entry small' id='t$date'>";
        $entry.= "<div class='time small' id='t$date-time'>";
        $entry.= "$date<span id='t$date-label' class='small'> $s</span></div>
        <div class='content'><div>$one</div></div>
        <div class='content'><div>$two</div></div>
        <div class='content'><div>$three</div></div>
        </div>";
        $content.=$entry;
        // Script
        $s = "
        new ScrollMagic.Scene({triggerElement: '#t$date', duration: 350})
            .on('enter', function () {
                $('#t$date').removeClass('small');
                $('#t$date-time').removeClass('small');
                $('#t$date-label').removeClass('small');
            })
            .on('leave', function () {
                $('#t$date').addClass('small');
                $('#t$date-time').addClass('small');
                $('#t$date-label').addClass('small');
            })
            .addTo(controller);
        ";
        $script.=$s;
    }

    $file = file_get_contents("template.html");
    $file = str_ireplace("{{rand}}", microtime(), $file);
    $file = str_ireplace("{{content}}", $content, $file);
    $file = str_ireplace("{{script}}", $script, $file);

    file_put_contents("index.html", $file);
