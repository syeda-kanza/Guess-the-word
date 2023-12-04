<!DOCTYPE html>

<head>

<body>
    <h1>GUESS THE WORD</h1>
    <?php
    session_start();

    if (!isset($_SESSION['words'])) {
        $_SESSION['words'] = array("Jigsaw", "Desert", "Mobile", "Family", "Season", "Number", "Animal", "Camera", "Bright", "Hockey");
    }
    if (!isset($_SESSION['word'])) {

        $words = $_SESSION['words'];
        $random_word = array_rand($words);
        $selected_word = $words[$random_word];
        $selected_word = strtolower($selected_word);

        $chars = str_split($selected_word);
        shuffle($chars);
        $chars = array_slice($chars, 0, 3);
        $missing_word = str_replace($chars, "_", $selected_word);

        $counter = 3;

        $_SESSION["word"] = $selected_word;
        $_SESSION["missing_word"] = $missing_word;
        $_SESSION["counter"] = $counter;
        $_SESSION['tries'] = array();
    }

    if (isset($_POST['guess'])) {
        $guess = strtolower($_POST['guess']);

        if (strlen($guess) == 1 && preg_match('/[a-z]/', $guess)) {
            $selected_word = $_SESSION['word'];
            $missing_word = $_SESSION['missing_word'];
            $char = array();
            $found = false;
            for ($i = 0; $i < strlen($selected_word); $i++) {
                if ($selected_word[$i] == $guess) {
                    $found = true;
                    if (strpos($missing_word, '_') !== false) {
                        $missing_word[$i] = $guess;
                    }
                }
            }
            if ($found) {
                $counter = $_SESSION['counter'];
                echo "<p>Remaining turns:$counter</p>";
                $_SESSION['missing_word'] = $missing_word;
                $_SESSION['tries'][] = array('guess' => $guess, 'result' => 'Correct');
            } else {
                $_SESSION['counter']--;
                $counter = $_SESSION['counter'];
                echo "<p>Remaining turns:$counter</p>";
                $_SESSION['tries'][] = array('guess' => $guess, 'result' => 'Incorrect');
            }
        }
        if ($_SESSION['missing_word'] == $_SESSION['word']) {
            echo "<h2>Congratulations! You won!</h2>";
            echo "<button><a href='new.php'>reset</a></button>";
            exit;
            
        } else if ($_SESSION['counter'] <= 0) {
            $selected_word = $_SESSION['word'];
            $tries = $_SESSION['tries'];

            echo "<h2>Oops! you lost</h2>";
            echo "<h3>Click reset to start new game</h3>";
            echo "<button><a href='new.php'>reset</a></button>";
            exit;
        }
    }

    ?>

    <p>Selected word: <?php echo $_SESSION["missing_word"]; ?></p>

    <form method=post>
        <input name="guess" id="guess" placeholder="take a guess" required />
        <button type="submit">Guess</button>
    </form>

    <?PHP
    $tries = $_SESSION['tries'];
    foreach ($tries as $turn) {
        echo '<li>Guess: ' . $turn['guess'] . ', Result: ' . $turn['result'] . '</li>';
    }
    ?>
    <button><a href="new.php">reset</a></button>

</body>
</head>

</html>