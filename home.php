<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Data Warehousing</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/general.css" rel="stylesheet">
    <script src="js/jQuery.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script src="js/main.js"></script>
    <meta charset='utf-8'>
  </head>

  <body>
    <div class="site-wrapper">
      <div class="site-wrapper-inner">
        <div class="cover-container">
          <div class="inner cover">
            <h1 class="cover-heading">Data Warehousing 2015</h1>
            <p class="lead">Please choose the data set you want to analyze.</p>
            <p class="lead">
              <div class="btn-group">
                <button type="button" class="btn btn-default">Data Sets</button>
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  <span class="caret"></span>
                  <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                  
                </ul>
              </div>
            </p>
          </div>

          <div class="mastfoot">
            <div class="inner">
              <p>Gonçalo Dias da Silva (nº 41831)</p>
              <p>João Francisco Pinto (nº 41887)</p>
            </div>
          </div>


          <form action="cube.php" method="post">
          <input type="hidden" name="cube">
          </form>

        </div>
      </div>
    </div>
  </body>
</html>
