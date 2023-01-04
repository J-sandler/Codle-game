<!DOCTYPE html>
<html lang="en">

<head>
  <title> DailyCodle.com </title>
  <link rel="stylesheet" href="Daily-codle.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <?php

  //backend questions-list interpretation:
  $questionsfile = fopen("Codle_Questions.txt", "r"); //func1: imf, func2: sumfunc, func3:mfunc;
  $questionsstr = fread($questionsfile, filesize("Codle_Questions.txt"));
  $questionsarray = explode("Q: ", $questionsstr, 416);
  $questions = array();

  foreach ($questionsarray as $question) {
    $question = (explode("[[", $question, 2))[1];
    $question = (explode("], [", $question, 7));
    $question[3] = str_replace('[', "", $question[3]);
    $question[5] = str_replace(']]]', "", $question[5]);
    for ($i = -1; $i <= 5; $i++) {
      $question[$i] = str_replace("'", "", $question[$i]);
      $question[$i] = str_replace("'", "", $question[$i]);
    }
    \array_splice($question, -1, 6);
    $question[0] = explode(",", $question[0], 3);

    for ($a = 3; $a <= 5; $a += 1) {
      $pair = explode(", ", $question[$a], 2);
      $question[$a] = $pair;
    }

    $num = count($questions) + 1;
    $questiontag = "Question $num:";
    $questions[$questiontag] = $question;
  }
  //colors:
  $maincolor = "#212120";
  $compcolor = "#545454";
  $compcolor2 = "#666666";
  $compcolor3 = "white";
  $compcolor4 = "#7F7F7F";
  $varcolor = "lightgreen";
  $functioncolor = "red";

  //graphics + images:
  $settingsGraphicref = "codle-resources/settings-graphic.jpeg";

  //question logic + variables:
  $num = (date('m')) * date('d');
  $questionnum = "Question $num:"; //only part that is updated every day ---------------------
  $dailyquestion = $questions[$questionnum];

  $functiondisplayorder = array(); //order functions are displayed in
  $functiondisplayorder = $questions[$questionnum][0];

  $starts = array(); //array of starting-array terms
  $starts = explode(", ", $questions[$questionnum][1], 6);
  $starts[5] = explode(", ", $starts[5], 2)[0];

  $ends = array(); //array of ending-array terms
  $ends = explode(", ", $questions[$questionnum][2], 6);

  $randsum = 0;
  $randmodulus = 0;
  $randindexmodulus = 0;

  foreach ($dailyquestion as $questionterm) {
    if (trim($questionterm[0]) == "sum_function") {
      $randsum = $questionterm[1];
    } else if (trim($questionterm[0]) == "modulus_function") {
      $randmodulus = $questionterm[1];
    } else if (trim($questionterm[0]) == "index_modulus_function") {
      $randindexmodulus = $questionterm[1];
    }
  }

  $SumFunctionText = "<p> def <span id='function'>sum_function</span>():</p><p>global <span id='var'>array</span> </p> <p>for <span id='var'>n</span> in <span id='var'>array</span>:</p> <p> &emsp; <span id='var'>empty_array</span>.<span id='function'>append</span>(<span id='var'>n</span> + $randsum)</p><p><span id='var'>array</span>=<span id='var'>empty_array</span></p>";
  $ModulusFunctionText = "<p> def <span id='function'>modulus_function</span>():</p><p>global <span id='var'>array</span></p><p>for <span id='var'>n</span> in <span id='var'>array</span>:</p><p>&emsp; if (<span id='var'>n</span> % $randmodulus == 0): </p><p>&emsp; &emsp; <span id='var'>array</span>.<span id='function'>remove</span>(<span id='var'>n</span>)</p>";
  $IndexModulusFunctionText = "<p> def <span id='function'>index_modulus_function</span>():</p><p>global <span id='var'>array</span></p><p>for <span id='var'>n</span> in <span id='var'>array</span>:</p><p>&emsp; if (<span id='var'>array</span>.<span id='function'>index</span>(<span id='var'>n</span>) % $randindexmodulus == 0):</p><p>&emsp; &emsp; <span id='var'>array</span>.<span id='function'>remove</span>(<span id='var'>n</span>)</p>";

  $FunctionA_displayText = "";
  $FunctionB_displayText = "";
  $FunctionC_displayText = "";
  $Function_letter_text_array = array($FunctionA_displayText, $FunctionB_displayText, $FunctionC_displayText);

  $function_correspondence_map = array("function_1" => $SumFunctionText, "function_2" => $ModulusFunctionText, "function_3" => $IndexModulusFunctionText);
  $forder = array();

  foreach ($functiondisplayorder as $function) {
    array_push($forder, $function_correspondence_map[trim((string)$function)]);
  }

  $functiondisplayorder = $forder; // now in the form: [$FunctionA_displayText, $FunctionB_displayText etc...]
  $FunctionA_displayText = $functiondisplayorder[0];
  $FunctionB_displayText = $functiondisplayorder[1];
  $FunctionC_displayText = $functiondisplayorder[2];

  $endsIds = array(); //array of values pertaining to the id of each end term (either true or false);

  if (count($ends) != 6) {
    $i = count($ends);
    $a = 0;
    while ($a != $i) {
      $endsIds[$a] = "end-term";
      $a += 1;
    }
    $i = 6 - count($ends);

    $a = count($ends);
    while ($i != 0) {
      array_push($endsIds, "no-term");
      $ends[$a] = " ";
      $i -= 1;
      $a += 1;
    }
  } else {
    $endsIds = array("end-term", "end-term", "end-term", "end-term", "end-term", "end-term");
  }

  $answerFunctionsArray = $dailyquestion[0];
  $function_answer_correspondence_map = array("function_1" => "", "function_2" => "", "function_3" => "");

  $function_answer_correspondence_map["function_1"] = $dailyquestion[3][0];
  $function_answer_correspondence_map["function_2"] = $dailyquestion[4][0];
  $function_answer_correspondence_map["function_3"] = $dailyquestion[5][0];

  $questionAnswer = array();
  $i = 0;
  foreach ($answerFunctionsArray as $function) {
    $questionAnswer[$i] = $function_answer_correspondence_map[trim($function)];
    $i += 1;
  }



  ?>
</head>

<body style="background-color: <?php echo $maincolor ?>;">
  <header style="margin: 0; top: -1px; right: -1px; left: -1px; height: 65px; position: fixed; border: 1px solid <?php echo $compcolor; ?>; ">
    <div id="left-header">
      <div id="hamburger"><span>&#9776</span></div>
    </div>
    <div id="center-header">
      <p id="header-title"> <span>C</span>odle</p><span id="brackets">[ ]</span>
    </div>
    <div id="right-header">
      <button id="rules" style="border: 2px solid <?php echo $compcolor3; ?>; background-color: <?php echo $maincolor; ?>;">?</button>
      <i class="fa" style="color: <?php echo $compcolor3; ?>; font-size: 30px; margin: 0px 30px;"> &#xf013 </i>
    </div>
  </header>

  <div id="gamespace">
    <div id="arrays" style="border: 0px solid <?php echo $compcolor; ?>;">
      <!------arrays----->
      <div id="starting-array">
        <div id="text-box">
          <p> Starting Array [ ]</p>
        </div>
        <div id="starting-terms-box">
          <div id="starting-term-1" style="border: 2px solid <?php echo $compcolor; ?>;"><span id="start-term"><?php echo $starts[0]; ?></span></div>
          <div id="starting-term-2" style="border: 2px solid <?php echo $compcolor; ?>;"><span id="start-term"><?php echo $starts[1]; ?></span></div>
          <div id="starting-term-3" style="border: 2px solid <?php echo $compcolor; ?>;"><span id="start-term"><?php echo $starts[2]; ?></span></div>
          <div id="starting-term-4" style="border: 2px solid <?php echo $compcolor; ?>;"><span id="start-term"><?php echo $starts[3]; ?></span></div>
          <div id="starting-term-5" style="border: 2px solid <?php echo $compcolor; ?>;"><span id="start-term"><?php echo $starts[4]; ?></span></div>
          <div id="starting-term-6" style="border: 2px solid <?php echo $compcolor; ?>;"><span id="start-term"><?php echo $starts[5]; ?></span></div>
        </div>
      </div>
      <div id="final-array">
        <div id="text-box">
          <p> Final Array [ ]</p>
        </div>
        <div id="final-terms-box">
          <div id="final-term-1" style="border: 2px solid <?php echo $compcolor2; ?>;"><span id=<?php echo $endsIds[0]; ?>><?php echo $ends[0]; ?></span></div>
          <div id="final-term-2" style="border: 2px solid <?php echo $compcolor2; ?>;"><span id=<?php echo $endsIds[1]; ?>><?php echo $ends[1]; ?></span></div>
          <div id="final-term-3" style="border: 2px solid <?php echo $compcolor2; ?>;"><span id=<?php echo $endsIds[2]; ?>><?php echo $ends[2]; ?></span></div>
          <div id="final-term-4" style="border: 2px solid <?php echo $compcolor2; ?>;"><span id=<?php echo $endsIds[3]; ?>><?php echo $ends[3]; ?></span></div>
          <div id="final-term-5" style="border: 2px solid <?php echo $compcolor2; ?>;"><span id=<?php echo $endsIds[4]; ?>><?php echo $ends[4]; ?></span></div>
          <div id="final-term-6" style="border: 2px solid <?php echo $compcolor2; ?>;"><span id=<?php echo $endsIds[5]; ?>><?php echo $ends[5]; ?></span></div>
        </div>
      </div>
    </div>
    <div id="functions">
      <!------functions----->
      <div id="drag-zone">
        <div id="function-a" style="border: 2px solid <?php echo $compcolor4; ?>;">
          <button id="function-a-dragable" draggable="true" style="color: <?php echo $compcolor3; ?>; background-color: <?php echo $compcolor; ?>;"><?php echo $FunctionA_displayText; ?></button>
        </div>
        <div id="function-b" style="border: 2px solid <?php echo $compcolor4; ?>;">
          <button id="function-b-dragable" draggable="true" style="color: <?php echo $compcolor3; ?>; background-color: <?php echo $compcolor; ?>;"><?php echo $FunctionB_displayText; ?></button>
        </div>
        <div id="function-c" style="border: 2px solid <?php echo $compcolor4; ?>;">
          <button id="function-c-dragable" draggable="true" style="color: <?php echo $compcolor3; ?>; background-color: <?php echo $compcolor; ?>;"><?php echo $FunctionC_displayText; ?></button>
        </div>
      </div>
      <div id='output-box'>
        <div id="drop-zone">
          <div class="dropzone" id="drop-a" style="border: 2px solid <?php echo $compcolor4; ?>;">
          </div>
          <div class="dropzone" id="drop-b" style="border: 2px solid <?php echo $compcolor2; ?>;">
          </div>
          <div class="dropzone" id="drop-c" style="border: 2px solid <?php echo $compcolor; ?>;">
          </div>
        </div>
        <div id="output-buttons">
          <button id="reset">reset</button>
          <button id="run">run</button>
        </div>
      </div>
    </div>
  </div>
  <footer style="border: 1px solid <?php echo $compcolor; ?>;">
    <div></div>
    <div id="secretanswer!"> <?php print_r($questionAnswer); ?> </div>
  </footer>
</body>
<script language="javascript" type="text/javascript">
  var username;
  var password;

  /*function login(username, password) {
    if (username == null) {
      username = prompt('Enter your username');
      if (username in logins) {
        password = prompt('Enter your password');
        if (!(logins[username] == password)) {
          alert('password is incorrect');
          password = null;
          username = null;
          login(username, password);
        }
      } else {
        alert('  username not found, \nplease try again or set up new account');
        username = null;
        login(username, password);
      }
    }
    alert('welcome to codle ' + username);
  }*/

  //------------//FUNCTIONS dragging//------------//

  var draggedRef = null;

  document.addEventListener("dragstart", event => {
    draggedRef = event.target;
  })

  document.addEventListener("dragover", event => {
    event.preventDefault();

  })

  document.addEventListener("drop", event => {
    event.preventDefault();
    if (event.target.className == "dropzone") {
      draggedRef.parentNode.removeChild(draggedRef);
      event.target.appendChild(draggedRef);
    }

  })

  //Reset and Run buttons//

  document.getElementById("reset").onclick = function(event) {

    var fad = document.getElementById("function-a-dragable");
    var fbd = document.getElementById("function-b-dragable");
    var fcd = document.getElementById("function-c-dragable");

    var fa = document.getElementById("function-a");
    var fb = document.getElementById("function-b");
    var fc = document.getElementById("function-c");

    document.getElementById("drop-a").innerHtml = "";
    document.getElementById("drop-b").innerHtml = "";
    document.getElementById("drop-c").innerHtml = "";

    fa.appendChild(fad);
    fb.appendChild(fbd);
    fc.appendChild(fcd);
  }

  document.getElementById("run").onclick = function(event) {

  }

  var questionAnswer = getElementById("secretanswer!");
  document.write(questionAnswer.innerHtml);
</script>

</html>