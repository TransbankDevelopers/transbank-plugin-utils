<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transbank Utils</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.5/css/bulma.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <script src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js"></script>
  </head>
  <body>
  <section class="section">
    <div class="container">
      <h1 class="title">
        Transbank Utils Examples
      </h1>
        <br>
        <button id="unload" class="button"> Volver</button>
        <br>
        <section id="content">

        </section>
        <section id="table">
            <table class="table">

                <thead>
                <th>Examples:</th>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <a href="#" file="/logHander.php">Log Handler</a>
                    </td>
                </tr>
                </tbody>

            </table>
        </section>

    </div>
  </section>
  </body>
  <script>
      $(document).ready(function (e) {
          $('#unload').hide();
      });
      $('a').click(function(e) {
          e.preventDefault();
          console.log('click');
          let alt = $(this).attr('file');
          $('#content').load(alt);
          $('#table').hide();
          $('button').show();
      });
      $('#unload').click(function (e) {
          e.preventDefault();
          $('#content').empty();
          $('#table').show();
          $(this).hide();
      });
  </script>
</html>
