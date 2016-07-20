<?php

$config = [
    'username' => 'B4o4T', // username display on chat
    'icon_url' => 'https://les-404.xyz/img/B4o4T.png', // icon display on chat
    'token' => [
        'token_channels1',
        'token_channels2'
    ] // Channels token
];

// get the words.json if exist
$wordsList = (array) json_decode(file_get_contents('words.json'));

// When bot come here
if ($_POST && $_GET && array_key_exists('bot', $_GET) && array_key_exists('text', $_POST) && array_key_exists('token', $_POST)) {

    // init var
    $msg = [];
    $resp = [];
    $text = $_POST['text'];

    // check token
    if (!in_array($_POST['token'], $config['token'])) return false;

    // check if text contains some urls to edit
    checkUrl($text);

    // loop into all msg list
    foreach ($wordsList as $key => $list) {

        // loop into all msg
        foreach ($list->msg as $k => $msg) {
            if (strpos($msg, ' ')) { // check message
                if (stripos($text, $msg) !== false) {
                    displayRandomMsg($wordsList[$key]->resp);
                }
            } else { // check word
                if (checkWord($msg, $text)) {
                    displayRandomMsg($wordsList[$key]->resp);
                }
            }
        }
    }
    die;
}

/*
 Check if a word contains string
 */
function checkWord($string, $search)
{
    $words = explode(' ', $search);
    foreach ($words as $w) {
        if (strtolower($string) == strtolower($w) && $w != "") return true;
    }

    return false;
}

/**
 * Return to mattermost the data
 * @param $text
 */
function displayMsg($text)
{
    global $config;

    // display the msg
    echo json_encode(array(
        'text' => $text,
        'username' => $config['username'],
        'icon_url' => $config['icon_url'],
    ));
    die;
}

/**
 * Get a random msg in array of responses
 * @param $texts
 */
function displayRandomMsg($texts)
{
    // select random msg
    $max = count($texts) - 1;
    $randomMsg = $texts[rand(0, $max)];
    displayMsg($randomMsg);
}

/**
 * check url to edit
 * @param $text
 */
function checkUrl($text)
{
    $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
    if (preg_match($reg_exUrl, $text, $urls)) {

        // make the urls hyper links
        $url = $urls[0];
        if (stripos($url, '//gyazo.com')) { // if it's a gyazo url
            displayGyzao($url);
        }
    }
}

/**
 * Return a giazo url ;)
 * @param $url
 */
function displayGyzao($url)
{
    $html = file_get_contents($url);
    $doc = new DOMDocument();
    @$doc->loadHTML($html);
    $img = $doc->getElementsByTagName('img')[0];
    displayMsg($img->getAttribute('src'));
}

// save responses into file
if ($_POST && array_key_exists('responses', $_POST)) {
    $wordsList = json_encode($_POST['responses']);
    file_put_contents('words.json', $wordsList);
    echo json_encode(array('success' => true));
    die;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://use.fontawesome.com/23511f232a.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,700' rel='stylesheet' type='text/css'>

    <style>
        h1 {
            font-family: 'Roboto', sans-serif;
            font-weight: 300;
            box-shadow: 0 2px 5px 0 rgba(0, 0, 0, .16), 0 2px 10px 0 rgba(0, 0, 0, .12);
            display: inline-block;
            padding: 20px 40px;
        }

        button {
            font-family: 'Roboto', sans-serif;
            border-radius: 0px;
            border: none;
            color: #fff;
            background: #373a3c;
            width: 100%;
            padding: 10px 0px;
            font-size: 18px;

            -webkit-transition: all .5s;
            -moz-transition: all .5s;
            -ms-transition: all .5s;
            -o-transition: all .5s;
            transition: all .5s;
        }

        button:focus, button:active {
            border: none !important;
            outline: none !important;
        }

        button .btn-add {
            display: inline-block;
            position: relative;
            width: 20px;
            height: 20px;
            text-align: left;
        }

        button .btn-add::before, button .btn-add::after {
            content: '';
            position: absolute;
            width: 100%;
            top: 60%;
            left: 0%;
            height: 2px;
            background: #fff;
            -webkit-transform: rotate(90deg);
            -moz-transform: rotate(90deg);
            transform: rotate(90deg);
            margin-left: 0%;

            -webkit-transition: all .25s;
            -moz-transition: all .25s;
            -ms-transition: all .25s;
            -o-transition: all .25s;
            transition: all .25s;
        }

        button .btn-add::after {
            -webkit-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            transform: rotate(0deg);
        }

        button:hover, button:hover .btn-add:before, button:hover .btn-add:after {
            -webkit-transition: all .25s;
            -moz-transition: all .25s;
            -ms-transition: all .25s;
            -o-transition: all .25s;
            transition: all .25s;
        }

        button:hover .btn-add:before {
            left: -25%;
            -webkit-transform: rotate(60deg);
            -moz-transform: rotate(60deg);
            transform: rotate(60deg);
        }

        button:hover .btn-add:after {
            left: 25%;
            -webkit-transform: rotate(-60deg);
            -moz-transform: rotate(-60deg);
            transform: rotate(-60deg);
        }

        .messages-container {
            padding-top: 30px;
        }

        table th {
            text-align: center;
            text-transform: uppercase;
            padding: 10px 0px;
        }

        table thead tr {
            background: #373a3c;
            color: #fff;
        }

        table tr td {
            padding: 20px 0px;
            border-bottom: 1px solid #dfe1e2;
            position: relative;
        }

        table tr td:nth-child(2) {
            padding-right: 40px;
        }

        table tr td:nth-child(2):before {
            content: "";
            width: 40px;
            height: 40px;
            background: url('<?= $config['icon_url']?>');
            background-size: cover;
            position: absolute;
            left: 0;
            display: inline-block;
        }

        table tbody tr:last-child td {
            border: none !important;
        }

        .container table textarea {
            width: 80%;
            margin: auto;
            border-radius: 0px;
            resize: none;
            min-height: 95px;
            white-space: nowrap;
        }

        .remove {
            width: 50px;
            position: absolute;
            top: 20px;
            right: 0px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-12 text-center">
            <h1><?= $config['username']; ?> responses</h1>

            <div class="messages-container">
                <table class="col-xs-12">
                    <thead>
                    <tr>
                        <th>Message</th>
                        <th>Response</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php if (count($wordsList) > 0):
                        foreach ($wordsList as $list) {
                            $list = (array) $list;
                            $messages = implode(PHP_EOL, $list['msg']);
                            $responses = implode(PHP_EOL, $list['resp']);
                            ?>
                            <tr>
                                <td><textarea class="form-control" placeholder="One per line"><?= $messages ?></textarea></td>
                                <td>
                                    <textarea class="form-control" placeholder="One per line (random choice)"><?= $responses ?></textarea>
                                    <button class="remove"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php else: ?>
                        <tr>
                            <td><textarea class="form-control" placeholder="One per line"></textarea></td>
                            <td>
                                <textarea class="form-control" placeholder="One per line (random choice)"></textarea>
                                <button class="remove"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            </td>
                        </tr>
                    <?php endif; ?>

                    </tbody>
                </table>
                <button id="add-row" style="width: auto;padding:10px 15px;float:left;"><span class="btn-add"></span></button>
                <button id="save" style="margin-top: 20px">SAVE</button>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>
<script>
    $(document).ready(function () {

        // add a row
        $('#add-row').on('click', function () {
            $('tbody').append(
                '<tr>' +
                '<td><textarea class="form-control" placeholder="One per line"></textarea></td>' +
                '<td><textarea class="form-control" placeholder="One per line (random choice)"></textarea>' +
                '<button class="remove"><i class="fa fa-trash" aria-hidden="true"></i></button></td>' +
                '</tr>');
        });

        // remove the selected row
        $('body').on('click', '.remove', function () {
            if (confirm('Are you sure to delete this ?!')) {
                $(this).parent().parent().remove();
            }
        });

        $('#save').on('click', function () {
            var responses = [];
            $('table tbody tr').each(function () {
                var msg = $(this).find('td textarea')[0];
                msg = $(msg).val();
                msg = msg.split('\n');
                msg = cleanArray(msg);

                var resp = $(this).find('td textarea')[1];
                resp = $(resp).val();
                resp = resp.split('\n');
                resp = cleanArray(resp);

                responses.push({
                    'msg': msg,
                    'resp': resp,
                })
            });

            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: {
                    'responses': responses
                },
                success: function () {
                    $('#save').text('SAVED !');
                    setTimeout(function () {
                        $('#save').text('SAVE');
                    }, 3000);
                },
                error: function () {
                    $('#save').text('ERROR, please report issue.');
                }
            });
        });

        /**
         * Remove useless ""
         * @param arr
         * @returns {Array}
         */
        function cleanArray(arr) {
            var result = [];
            arr.forEach(function (elem) {
                if (elem.length > 0) {
                    result.push(elem);
                }
            });
            return result;
        }
    });
</script>
</body>
</html>