<html>
    <head>
        <?php
        date_default_timezone_set("America/Chicago");
        $xml=("http://www.idlethumbs.net/feeds/idle-thumbs");
        $xmlDoc = new DOMDocument();
        $xmlDoc->load($xml);

        // get latest episode title
        $title = $xmlDoc->getElementsByTagName('item')->item(0)->getElementsByTagName('title')->item(0)->nodeValue;

        // extrapolate number, add one to it
        $current_episode = intval(explode(" ", explode(':', $title)[0])[2]) + 1;

        // ouput episode number to js on page
        echo("<script>var episode = ");
        echo($current_episode);
        echo(";</script>");

        ?>

        <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

        <script>

        $(document).ready(function(){

            $('#theepisode').text(episode);
   
            setTheDate($('#wed').is(':checked'));
   
            $('#wed').click(function () {
                setTheDate($('#wed').is(':checked'));
            });
   
        })

        function setTheDate(wed) {

            var suffix = "";
            var d = new Date();

            if (wed) {
                d = nextWednesday(d);
            }

            var n = d.getDate();
    
            switch(n) {
                case 1: case 21: case 31: suffix = 'st'; break;
                case 2: case 22: suffix = 'nd'; break;
                case 3: case 23: suffix = 'rd'; break;
                default: suffix = 'th';
            }

           $('#thedate').text($.datepicker.formatDate('MM d', d) + suffix + $.datepicker.formatDate(', yy', d));

        }

        function nextWednesday(date) {
            var ret = new Date(date||new Date());
            ret.setDate(ret.getDate() + (3 - 1 - ret.getDay() + 7) % 7 + 1);
            return ret;
        }
        </script>
        <title>Idle Thumbs Opening Script</title>
        <meta name="viewport" content="width=device-width">
        <style>
            h1 {
                font-weight: bold;
                font-size: 28px;
                }
        </style>
    </head>
    <body>
        <h1>It's <span id="thedate"></span></h1>
        <h1>This is Idle Thumbs <span id="theepisode"></span></h1>
        <input type="checkbox" id="wed" checked />Make it be the next Wednesday
    </body>
</html>