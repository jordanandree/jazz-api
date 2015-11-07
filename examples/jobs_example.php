<?php

require "../lib/jazz.php";

$jazz = new Jazz("YOUR_API_KEY");
$subdomain = "mycompanyname"; // subdomain for you company jazz domain
$jobs = $jazz->getJobs(array(
  "status" => "open", // open jobs
));


?>
<!DOCTYPE html>
<html>
<head>
  <title>Open Jobs - Jazz PHP API Example</title>
  <style type="text/css">
  body {
    font: 18px/32px "Helvetica Neue", Helvetica, Arial, Sans-serif;
    padding: 0;
    margin: 0;
    -webkit-font-smoothing: antialiased;
    font-smoothing: antialiased;
  }
  h1 {
    padding-bottom: 96px;
  }
  article {
    padding-top: 32px;
    padding-bottom: 32px;
  }
  a {
    color: #2C9AB7;
  }
  a:hover {
    text-decoration: none;
  }
  .wrapper {
    width: 990px;
    margin: 0 auto;
    padding: 96px 0;
  }
  .button {
    padding: 12px 18px;
    background-color: #2C9AB7;
    color: #fff;
    text-decoration: none;
  }
  </style>
</head>
<body>

  <section class='wrapper'>

    <h1>Open Jobs - Jazz PHP API Example</h1>

    <?php foreach($jobs as $job): ?>
      <article id="<?php echo $job->board_code ?>">
        <h2><?php echo $job->title ?></h2>
        <em>Department: <?php echo $job->department ?></em>
        <p><?php echo $job->description ?></p>
        <p>
          <a class='button' href="http://<?php echo $subdomain ?>.thejazz.com/apply/<?php echo $job->board_code; ?>">
            Apply Now
          </a>
        </p>
      </article>
    <?php endforeach; ?>

  </section>

</body>
</html>