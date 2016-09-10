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
        <script src="safarimobile-multiline-select.js"></script>
        
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:600,600italic,800,800italic' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Roboto+Mono:400,700' rel='stylesheet' type='text/css'>
        <script>

        var thumbsHosts = {};
        var openerHost = "Jake Rodkin";

        $(document).ready(function(){

			$("#extracrap").hide();
            $('#theepisode').text(episode);

            thumbsHosts["Chris Remo"] = true;
            thumbsHosts["Jake Rodkin"] = true;
            thumbsHosts["Nick Breckon"] = true;
            thumbsHosts["Sean Vanaman"] = false;
            thumbsHosts["James Spafford"] = false;
            thumbsHosts["Danielle Riendeau"] = false;
            thumbsHosts["Steve Gaynor"] = false;
            
            var hostsSelect = $("#thumbsHosts");
            $.each(thumbsHosts, function(key, value) {
                hostsSelect.append($("<option />").val(key).text(key));
            });
            $('#thumbsHosts').attr('size', Object.keys(thumbsHosts).length);
            
            $("select").fixForSafariMobile();
            
            setTheHostOrder();
            
            $('#hostup').click(function(){
                moveUpItem();
                setTheHostOrder();
            });

            $('#hostdown').click(function(){
                moveDownItem();
                setTheHostOrder();
            });

            var d = new Date();

            $('#thurs').prop('checked', (d.getDay() != 4));
            
            if (d.getDay() != 4) {
                $('#thurshint').html("<em>(today is <u>not</u> Thursday)")
            } else {
                $('#thurshint').html("<em>(today <u>is</u> Thursday)")
            }

            setTheDate($('#thurs').is(':checked'));

            $('#thurs').click(function () {
                setTheDate($('#thurs').is(':checked'));
            });
            
            $('#showcrap').click(function () {
                if ($('#showcrap').is(':checked')) {
                    $("#extracrap").show();
                } else {
                    $("#extracrap").hide();
                }
            });
            
            makeTheScript();
            
        })
        
        function makeTheScript() {
        
            $("#thescript").empty();
        
            $("#thescript")
                .append($('<tr align="center">')
                    .append($('<td>')
                        .append($('<strong>')
                            .html($('input[name=opener]:checked', '#thumbsform').val().split(' ')[0].toUpperCase())
                        )
                    )
                )
                .append($('<tr>')
                    .append($('<td>')
                        .html("It's " + $('#thedate').text())
                    )                    
                )
                
            var first = "This is Idle Thumbs " + episode + ", ";
                
            var hostsSelect = $("#thumbsHosts > option");
            $.each(hostsSelect, function() {
            
            if (thumbsHosts[this.text]) {
            
            if ($("#thumbsform input:checkbox:checked:last").val() == this.text) {
                first = "And ";
            }
            
            $("#thescript")
                .append($('<tr align="center">')
                    .append($('<td>')
                        .append($('<strong>')
                            .html(this.text.split(' ')[0].toUpperCase())
                        )
                    )
                )
                .append($('<tr>')
                    .append($('<td>')
                        .html(first + "I'm " + this.text)
                    )                    
                )

                first = "";
                }
            });
        }

        function setTheDate(thurs) {

            var suffix = "";
            var d = new Date();

            if (thurs) {
                d = nextThursday(d);
            }

            var n = d.getDate();

            switch(n) {
                case 1: case 21: case 31: suffix = 'st'; break;
                case 2: case 22: suffix = 'nd'; break;
                case 3: case 23: suffix = 'rd'; break;
                default: suffix = 'th';
            }

            // Ides is the 15th of [March, May, July, October] and the 13th of others.
            // note: Date.getMonth() starts counting from 0
            var idesMonths = [2,4,6,9];
            var ides = (idesMonths.indexOf(d.getMonth()) > -1) ? 15 : 13;

            if (n === ides) {
                $('#thedate').text("the Ides of " + $.datepicker.formatDate('MM, yy', d))
            } else {
                $('#thedate').text($.datepicker.formatDate('MM d', d) + suffix + $.datepicker.formatDate(', yy', d));
            }
            
        }
        
        function setTheHostOrder() {
            var hostsSelect = $("#thumbsHosts > option");
            $('#orderedHosts').empty();
            $('#openingHost').empty();
            $.each(hostsSelect, function() {
               $('<input />', { type: 'checkbox', id: this.text.replace(" ", "-"), value: this.text, checked: thumbsHosts[this.text] }).appendTo($('#orderedHosts'));
               $('<label />', { 'for': this.text.replace(" ", "-"), text: this.text }).appendTo($('#orderedHosts'));
               $('<br />').appendTo($('#orderedHosts'));
               
                $("#" + this.text.replace(" ", "-")).change(function () {
                    thumbsHosts[this.value] = this.checked;
                    setTheHostOrder();
                });
               
               if (thumbsHosts[this.text]) {
                       var selectedHost = (this.text == openerHost);
                   $('<input />', { type: 'radio', name: 'opener', id: 'opener-' + this.text.replace(" ", "-"), checked: selectedHost, value: this.text }).appendTo($('#openingHost'));
                   $('<label />', { 'for': 'opener-' + this.text.replace(" ", "-"), text: this.text }).appendTo($('#openingHost'));
                   $('<br />').appendTo($('#openingHost'));
                   
                    $("#opener-" + this.text.replace(" ", "-")).change(function () {
                        openerHost = this.value;
                        makeTheScript();
                    });
               }
            });
            makeTheScript();
        }

        function nextThursday(date) {
            var ret = new Date(date||new Date());
            ret.setDate(ret.getDate() + (4 - 1 - ret.getDay() + 7) % 7 + 1);
            return ret;
        }
        
        function moveUpItem(){
            $('#thumbsHosts option:selected').each(function(){
                $(this).insertBefore($(this).prev());
            });
        }

        function moveDownItem(){
            $('#thumbsHosts option:selected').each(function(){
                $(this).insertAfter($(this).next());
            });
        }
        
        </script>
        <title>Idle Thumbs Opening Script</title>
        <meta name="viewport" content="width=device-width">
        <style>
            h1 {
                font-weight: bold;
                font-size: 24px;
                }
            body {
                 font-family: 'Open Sans', sans-serif;
                 }
            #thescript {
                 font-family: 'Roboto Mono', monospace;
                 }
                 
             #thescript > tbody > tr > td {
                 padding: 5px 10px 10px 25px
             }
             
            #thumbsHosts_safarimobile { height: 99px;  width:114px; }
             /*114x99 */

        
        </style>
    </head>
    <body>
        <h1>It's <span id="thedate"></span></h1>
        <h1>This is Idle Thumbs <span id="theepisode"></span></h1>
        <input type="checkbox" id="thurs" checked />
        <label for="thurs">Make it be the <em>next</em> Thursday</label><br/>
        <span id="thurshint"></span>
        <br />
        <input type="checkbox" id="showcrap" />
        <label for="showcrap">Show the extra crap</label><br/>
        <div id="extracrap">
            <hr/>
            <form id="thumbsform">
                <table>
                    <tr>
                        <td>
                            All Thumbs
                        </td>
                        <td>                        
                        </td>
                        <td>
                            Available Thumbs
                        </td>
                        <td>
                            Opening Thumb
                        </td>
                    <tr>

                    <tr>
                        <td>
                            <select id="thumbsHosts"></select>
                        </td>
                        <td>
                            <input type="button" value="^" id="hostup" /><br />
                            <input type="button" value="v" id="hostdown" />
                        </td>
                        <td>
                            <span id="orderedHosts">
                            </span>
                        </td>
                        <td>
                            <span id="openingHost">
                            </span>
                        </td>
                    <tr>
                </table>
            </form>
        <hr />
            <table id="thescript">
                <tbody>
                </tbody>
            </table>
        </div>
        <a href="https://github.com/tomkidd/ThumbsOpeningScript" target="_new"><img src="GitHub-Mark-32px.png" style="position: absolute; top: 5; right: 5; border: 0;" width="16" height="16" /></a>
    </body>
</html>
