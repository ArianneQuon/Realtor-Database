<!doctype html>
<html lang="en" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>Real Estate Homepage</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
      /*
 * Globals
 */


/* Custom default button */
.btn-secondary,
.btn-secondary:hover,
.btn-secondary:focus {
  color: #333;
  text-shadow: none; /* Prevent inheritance from `body` */
}


/*
 * Base structure
 */

body {
  text-shadow: 0 .05rem .1rem rgba(0, 0, 0, .5);
  box-shadow: inset 0 0 5rem rgba(0, 0, 0, .5);
}

.cover-container {
  max-width: 42em;
}


/*
 * Header
 */

.nav-masthead .nav-link {
  padding: .25rem 0;
  font-weight: 700;
  color: rgba(255, 255, 255, .5);
  background-color: transparent;
  border-bottom: .25rem solid transparent;
}

.nav-masthead .nav-link:hover,
.nav-masthead .nav-link:focus {
  border-bottom-color: rgba(255, 255, 255, .25);
}

.nav-masthead .nav-link + .nav-link {
  margin-left: 1rem;
}

.nav-masthead .active {
  color: #fff;
  border-bottom-color: #fff;
}
    </style>

    
    <!-- Custom styles for this template -->
    <link href="cover.css" rel="stylesheet">
  </head>
  <body class="d-flex h-100 text-center text-white bg-dark">
    
<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
  <header class="mb-auto">
    <div>
      <h3 class="float-md-start mb-0">Real Estate Project</h3>
      <nav class="nav nav-masthead justify-content-center float-md-end">
        <a class="nav-link active" aria-current="page" href="#">Home</a>
        <a class="nav-link" href="manage_property.php">Properties</a>
        <a class="nav-link" href="realtor_home.php">Realtor</a>
        <a class="nav-link" href="buyerNavi.php">Buyer</a>
        <a class="nav-link" href="seller_home.php">Seller</a>
        <a class="nav-link" href="hire_maintenance.php">Hire Me!</a>
      </nav>
    </div>
  </header>

  <main class="px-3">
    <h1>Welcome To Our CS304 Project</h1>
    <p class="lead">Arianne Quon, Maylisa Siem, Harrison Liu</p>
    <p class="lead">
      <a href="realtor_home.php" class="btn btn-lg btn-secondary fw-bold border-white bg-white">I'm A Realtor</a>
      <a href="seller_home.php" class="btn btn-lg btn-secondary fw-bold border-white bg-white">I'm A Seller</a>
      <a href="buyerNavi.php" class="btn btn-lg btn-secondary fw-bold border-white bg-white">I'm A Buyer</a>
      <a href="new_member.php" class="btn btn-lg btn-secondary fw-bold border-white bg-white">New Member</a>
    </p>
  </main>

  <footer class="mt-auto text-white-50">
    
  </footer>
</div>    
</body>
</html>
