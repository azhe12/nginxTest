<?php include_once('header.php'); ?>
  <h2>Search</h2>
  <p>Enter a keyword to search for:</p>

  <form action="search.php" method="POST">
    <input name="keyword" size=20>
    <input type="submit" value="Search">
  </form>
<?php include_once('footer.php'); ?>
