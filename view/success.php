<?php 
include './services/function.php';
$session_id = $_GET['session_id'];
?>
<!DOCTYPE html>
<html>

<head>
  <title>Merci pour votre commande!</title>
  <link rel="stylesheet" href="/assets/style/subscription_style.css">
  <script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
  <script src="https://js.stripe.com/v3/"></script>
  <script type="text/javascript" src="/services/function.js"></script>

  <script>
      var session_status = <?php echo checkSession($session_id); ?>;
      if(session_status.http == 200) {
        <?php saveUserToDB(checkSession($session_id)) ?>
      } else {
        location.replace('/index.php');
      }
  </script>
</head>

<body>
  <div class="container">
    <section>
      <p>
        <b>Le paiement a été effectué avec succès !</b>
      </p>
      <p>
        <b>Si vous avez des questions, veuillez envoyer un e-mail à : <a
            href="mailto:orders@example.com">orders@example.com</a></b>
      </p>
      <a href="/index.php">
        <button>Accueil</button>
      </a>

    </section>
  </div>
</body>

</html>