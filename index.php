<?php
require_once __DIR__ . '/helpers.php';
$csrf = csrf_token();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="<?= e($csrf) ?>">
  <title>RTG Tourism | Booking Prototype</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="site-header">
  <a class="brand" href="index.php">RTG <span>TOURISM</span></a>
  <nav><a href="#stays">Explore</a><a href="#booking">Booking request</a><a href="login.php">Admin</a></nav>
</header>
<section class="hero"><div class="hero-copy">
  <p class="eyebrow">DISCOVER ZIMBABWE</p>
  <h1>Make room for<br><em>remarkable journeys.</em></h1>
  <p>Explore sample accommodation and tourism experiences in this independent RTG case-study prototype.</p>
  <a class="btn" href="#stays">Explore experiences</a>
</div></section>
<main>
  <section id="stays">
    <p class="eyebrow">FIND YOUR ESCAPE</p><h2>Featured experiences</h2>
    <div id="services" class="cards"><p>Loading experiences…</p></div>
  </section>
  <section id="booking" class="booking">
    <div><p class="eyebrow">PLAN YOUR STAY</p><h2>Send a booking request</h2>
      <p>Complete the form to send an enquiry. Your request starts as <b>Pending</b>; it is not a confirmed reservation.</p>
      <p class="small-note">This demonstration uses sample information and does not check live availability or process payments.</p>
    </div>
    <form id="bookingForm">
      <label>Experience<select id="service_id" name="service_id" required><option value="">Loading experiences…</option></select></label>
      <div class="pair">
        <label>Check-in<input name="check_in" type="date" required></label>
        <label>Check-out<input name="check_out" type="date" required></label>
      </div>
      <div class="pair">
        <label>Guests<input name="guest_count" type="number" min="1" max="20" value="2" required></label>
        <label>Your name<input name="full_name" maxlength="120" required></label>
      </div>
      <label>Email or phone<input name="contact_details" maxlength="180" required></label>
      <button class="btn" type="submit">Submit request</button>
      <p id="message" role="status" aria-live="polite"></p>
    </form>
  </section>
</main>
<footer>Independent student prototype · Rainbow Tourism Group case study · No live availability or payments</footer>
<script src="app.js" defer></script>
</body></html>
