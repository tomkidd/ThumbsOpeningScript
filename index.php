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
        
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:600,600italic,800,800italic' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Roboto+Mono:400,700' rel='stylesheet' type='text/css'>
        <script>

        $(document).ready(function(){

            $('#theepisode').text(episode);

            var d = new Date();

            $('#wed').prop('checked', (d.getDay() != 3));
            
			if (d.getDay() != 3) {
				$('#wedhint').html("<em>(today is <u>not</u> Wednesday)")
			} else {
				$('#wedhint').html("<em>(today <u>is</u> Wednesday)")
			}

            setTheDate($('#wed').is(':checked'));

            $('#wed').click(function () {
                setTheDate($('#wed').is(':checked'));
            });
            
            makeTheScript();
            
        })
        
        function makeTheScript() {
        
	        $("#thescript").empty();
        
			$("#thescript")
				.append($('<tr align="center">')
					.append($('<td>')
						.append($('<strong>')
							.html($('input[name=its]:checked', '#thumbsform').val().split(' ')[0].toUpperCase())
						)
					)
				)
				.append($('<tr>')
					.append($('<td>')
						.html("It's " + $('#thedate').text())
					)					
				)
				.append($('<tr align="center">')
					.append($('<td>')
						.append($('<strong>')
							.html($('input[name=date]:checked', '#thumbsform').val().split(' ')[0].toUpperCase())
						)
					)
				)
				.append($('<tr>')
					.append($('<td>')
						.html("This is Idle Thumbs " + episode + ", I'm " + $('input[name=date]:checked', '#thumbsform').val())
					)					
				)
				.append($('<tr align="center">')
					.append($('<td>')
						.append($('<strong>')
							.html($('input[name=its]:checked', '#thumbsform').val().split(' ')[0].toUpperCase())
						)
					)
				)
				.append($('<tr>')
					.append($('<td>')
						.html("I'm " + $('input[name=its]:checked', '#thumbsform').val())
					)					
				);
		}

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

        
        </style>
    </head>
    <body>
        <h1>It's <span id="thedate"></span></h1>
        <h1>This is Idle Thumbs <span id="theepisode"></span></h1>
        <input type="checkbox" id="wed" checked />
        <label for="wed">Make it be the <em>next</em> Wednesday</label><br/>
        <span id="wedhint"></span>
        <hr/>
        <form id="thumbsform">
			<table>
				<tr>
					<td>
						Thumb
					</td>
					<td>
						"It's" Thumb
					</td>
					<td>
						Dateline Thumb
					</td>
				</tr>
				<tr>
					<td>
						<input type="checkbox" name="thumb" id="thumb_cremo" checked /><label for="thumb_cremo">Chris Remo</label>
					</td>
					<td align="center">
						<input type="radio" name="its" id="its_cremo" value="Chris Remo" />
					</td>
					<td align="center">
						<input type="radio" name="date" id="date_cremo" value="Chris Remo" checked />
					</td>
				</tr>
				<tr>
					<td>
						<input type="checkbox" name="thumb" id="thumb_jrodkin" checked /><label for="thumb_jrodkin">Jake Rodkin</label>
					</td>
					<td align="center">
						<input type="radio" name="its" id="its_jrodkin" value="Jake Rodkin" checked />
					</td>
					<td align="center">
						<input type="radio" name="date" id="date_jrodkin" value="Jake Rodkin" />
					</td>
				</tr>
				<tr>
					<td>
						<input type="checkbox" name="thumb" id="thumb_svannaman" checked /><label for="thumb_svannaman">Sean Vannaman</label>
					</td>
					<td align="center">
						<input type="radio" name="its" id="its_svannaman" value="Sean Vannaman" />
					</td>
					<td align="center">
						<input type="radio" name="date" id="date_svannaman" value="Sean Vannaman" />
					</td>
				</tr>
				<tr>
					<td>
						<input type="checkbox" name="thumb" id="thumb_nbreckon" checked /><label for="thumb_nbreckon">Nick Breckon</label>
					</td>
					<td align="center">
						<input type="radio" name="its" id="its_nbreckon" value="Nick Breckon" />
					</td>
					<td align="center">
						<input type="radio" name="date" id="date_nbreckon" value="Nick Breckon" />
					</td>
				</tr>
			</table>
        </form>
        <hr />
        <table id="thescript">
        	<tbody>
        	</tbody>
        </table>
        <a href="https://github.com/tomkidd/ThumbsOpeningScript" target="_new"><img src="GitHub-Mark-32px.png" style="position: absolute; top: 5; right: 5; border: 0;" width="16" height="16" /></a>
    </body>
</html>
