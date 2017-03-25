<?php
    $imgList = array("1400bc","900bc","800bc","600bc","400bc",
    "200bc","100bc","0ad","100ad","400ad","700ad","800ad",
    "900ad","1000ad","1200ad","1300ad","1400ad","1500ad",
    "1600ad","1700ad","1800ad","1850ad","1900ad","1925ad",
    "1950ad","1975ad","2000ad");
    
    $imgDat = "";
    $imgScript = "";
    
    for($i = 1; $i <= count($imgList); $i++) {
        $imgDat.="<img src='images/$i.jpg' class='bg' id='img$i'>";
        $yr = $imgList[$i - 1];
        $imgScript.="
            new ScrollMagic.Scene({triggerElement: '#t$yr'})
                .on('enter leave', function (e) {
                    var state = e.type === 'enter' ? 1 : 0;
                    if(state === 1) {
                        $('#img$i').css('opacity', '1');
                    } else {
                        $('#img$i').css('opacity', '0');
                    }
    			})
    			.addTo(controller);
        ";
    }
    
    $data = json_decode(file_get_contents("content.json"), true);
    $content = "";
    $script = "";
    $ad = false;
    foreach($data as $d) {
        $date = $d["date"];
        if($date == 0) {
            $ad = true;
        }
        $addon = "bc";
        if($ad) {
            $addon = "ad";
        }
        $s = $d["suffix"];
        $one = (empty($d["1"]) ? "&nbsp;" : $d["1"]);
        $two = (empty($d["2"]) ? "&nbsp;" : $d["2"]);
        $three = (empty($d["3"]) ? "&nbsp;" : $d["3"]);
        $entry = "<div class='entry small' id='t$date$addon'>";
        $entry.= "<div class='time small' id='t$date$addon-time'>";
        $entry.= "$date<span id='t$date$addon-label' class='small'> $s</span></div>
        <div class='content'><div>$one</div></div>
        <div class='content'><div>$two</div></div>
        <div class='content'><div>$three</div></div>
        </div>";
        $content.=$entry;
        // Script
        $s = "
        new ScrollMagic.Scene({triggerElement: '#t$date$addon', duration: 350})
            .on('enter', function () {
                $('#t$date$addon').removeClass('small');
                $('#t$date$addon-time').removeClass('small');
                $('#t$date$addon-label').removeClass('small');
            })
            .on('leave', function () {
                $('#t$date$addon').addClass('small');
                $('#t$date$addon-time').addClass('small');
                $('#t$date$addon-label').addClass('small');
            })
            .addTo(controller);
        ";
        $script.=$s;
    }

    $file = file_get_contents("template.html");
    $file = str_ireplace("{{rand}}", microtime(), $file);
    $file = str_ireplace("{{content}}", $content, $file);
    $file = str_ireplace("{{script}}", $script, $file);
    $file = str_ireplace("{{images}}", $imgDat, $file);
    $file = str_ireplace("{{imgScript}}", $imgScript, $file);

    var_dump(file_put_contents("/projects/spanish-timeline/index.html", $file));
