<!DOCTYPE html>
<html lang="en">
<h>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading...</title>
    <style>
        /* From Uiverse.io by shadowmurphy */ 
        .chili{
        --pathlength: 1384;
        width: 90px;
        fill: transparent;
        stroke: red;
        stroke-linecap: round;
        stroke-width: 15px;
        stroke-dashoffset: var(--pathlength);
        stroke-dasharray: 0 var(--pathlength);
        animation: loader 3.5s cubic-bezier(.5,.1,.5,1) infinite both;
        }

        @keyframes loader {
        90%, 100% {
            stroke-dashoffset: 0;
            stroke-dasharray: var(--pathlength) 0;
        }
        }
    </style>
</head>

<body onload="myFunction()">
    
    <div id="loader" style="margin-top: 50vh; text-align: center;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="chili"> 
            <path d="M22.7 33.4c13.5-4.1 28.1 1.1 35.9 12.9L224 294.3 389.4 46.3c7.8-11.7 22.4-17 35.9-12.9S448 49.9 448 64l0 384c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-278.3L250.6 369.8c-5.9 8.9-15.9 14.2-26.6 14.2s-20.7-5.3-26.6-14.2L64 169.7 64 448c0 17.7-14.3 32-32 32s-32-14.3-32-32L0 64C0 49.9 9.2 37.5 22.7 33.4z"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="chili">
            <path d="M64 256c0 88.4 71.6 160 160 160c28.9 0 56-7.7 79.4-21.1l-72-86.4c-11.3-13.6-9.5-33.8 4.1-45.1s33.8-9.5 45.1 4.1l70.9 85.1C371.9 325.8 384 292.3 384 256c0-88.4-71.6-160-160-160S64 167.6 64 256zM344.9 444.6C310 467 268.5 480 224 480C100.3 480 0 379.7 0 256S100.3 32 224 32s224 100.3 224 224c0 56.1-20.6 107.4-54.7 146.7l47.3 56.8c11.3 13.6 9.5 33.8-4.1 45.1s-33.8 9.5-45.1-4.1l-46.6-55.9z"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="chili">
            <path d="M428.3 3c11.6-6.4 26.2-2.3 32.6 9.3l4.8 8.7c19.3 34.7 19.8 75.7 3.4 110C495.8 159.6 512 197.9 512 240c0 18.5-3.1 36.3-8.9 52.8c-6.1 17.3-28.5 16.3-36.8-.1l-11.7-23.4c-4.1-8.1-12.4-13.3-21.5-13.3L360 256c-13.3 0-24-10.7-24-24l0-80c0-13.3-10.7-24-24-24l-17.1 0c-21.3 0-30-23.9-10.8-32.9C304.7 85.4 327.7 80 352 80c28.3 0 54.8 7.3 77.8 20.2c5.5-18.2 3.7-38.4-6-55.8L419 35.7c-6.4-11.6-2.3-26.2 9.3-32.6zM171.2 345.5L264 160l40 0 0 80c0 26.5 21.5 48 48 48l76.2 0 23.9 47.8C372.3 443.9 244.3 512 103.2 512l-58.8 0C19.9 512 0 492.1 0 467.6c0-20.8 14.5-38.8 34.8-43.3l49.8-11.1c37.6-8.4 69.5-33.2 86.7-67.7z"/>
        </svg>
    </div>

    <div style="display:none;" id="myDiv" class="">
        <!-- session -->
<?php 
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }
  if (isset($_SESSION['unique_id'])) {
      header("location: user_page/shop.php");
      exit;
  }

?>


    <script>
        var myVar;

        function myFunction() {
            myVar = setTimeout(showPage, 3000);
        }
            
        function showPage() {
            document.getElementById("loader").style.display = "none";
            document.getElementById("myDiv").style.display = "block";
        }
    </script>
</body>