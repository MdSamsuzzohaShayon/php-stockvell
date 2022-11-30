<?php
session_start();
$is_admin = false;
$is_member = false;
if (isset($_SESSION['admin_id'])) $is_admin = true;
if (isset($_SESSION['member_id'])) $is_member = true;
$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/config/lang.php");
require_once($ROOT . '/layouts/header.php');
?>


<main class="home">
  <section class="section-1">
    <!-- <img src="public/img/saving-shape.svg" class="position-absolute landing-img top-0 end-0" alt=""> -->
    <div class="container d-flex flex-column content justify-content-center">
      <div class="row mx-0 flex-column-reverse flex-md-row">
        <div class="col-12 col-md-6">
          <h1 class="h1 text-primary heading"><?= __("Stockvel Makes Saving for Life Easy") ?></h1>
          <p class="text-primary"><?= __("A platform for Income Generation and Wealth Creation. A platform where members share a common savings goal, such as a December party, New Year's Eve, or a wedding, or investing in real estate or the stock market.") ?></p>
          <?php if ($is_member) { ?>
            <a href="/packs.php" class="btn btn-warning text-primary"><?= __("Join Now") ?></a>
          <?php } else { ?>
            <a href="/signup.php" class="btn btn-warning text-primary"><?= __("Join Now") ?></a>
          <?php } ?>
        </div>
        <div class="col-12 col-md-6">
          <img src="public/img/saving-shape.svg" alt="" class="img-fluid">
        </div>
      </div>
    </div>
  </section>
  <section class="section-2 text-primary">
    <div class="container d-flex flex-column content justify-content-center">
      <h2 class="h1 text-center" id="how-it-work"><?= __("How does it work?") ?></h2>
      <div class="d-flex justify-content-between flex-column flex-md-row">
        <div class="border border-warning p-3 my-3 my-md-0 explain-box">
          <img src="public/icons/groupsavings.svg" alt="">
          <h3 class="h3 text-primary"><?= __("Connect to your savings") ?></h3>
          <p class="text-primary"><?= __("Create a free account, join a pack or create your pack, and claim in an instant Welcome Bonuses.") ?></p>
        </div>
        <div class="border border-warning p-3 my-3 my-md-0 explain-box">
          <img src="public/icons/groupagreement.svg" alt="">
          <h3 class="h3 text-primary"><?= __("Must agree with goal of the group") ?></h3>
          <p class="text-primary"><?= __("Shop, dine, and discover your favorite deals with Upromise to earn cash rewards on your everyday purchases.") ?></p>
        </div>
        <div class="border border-warning p-3 my-3 my-md-0 explain-box">
          <img src="public/icons/groupwithdraw.svg" alt="">
          <h3 class="h3 text-primary"><?= __("Withdraw money at the end") ?></h3>
          <p class="text-primary"><?= __("Stockvel will automatically deposit your cash rewards & bonuses into your linked account with monthly contributions for maximum savings potential.") ?></p>
        </div>
      </div>
    </div>
  </section>
  <section class="section-3 bg-secondary">
    <div class="container">
      <div class="row">
        <div class="col-md-6"><img class="trust_each_other" src="public/img/trusteachother.svg" alt=""></div>
        <div class="col-md-6">
          <h2 class="h2 text-primary mt-5"><?= __("Stackvel is saving for more than 20000 members of the Stackvell pack.") ?></h2>
          <p><?= __("There’s a reason we’re the #1 Cash Back shopping companion. Don’t believe us? Just ask our community of 15+ million members.") ?></p>
        </div>
      </div>
    </div>
  </section>

  <section class="section-4">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h2 class="h2 text-primary mt-5"><?= __("Investing monthly adds up") ?></h2>
          <p><?= __("See how saving with Upromise adds up quickly when linked to your 529 account. Just $10/month in Upromise rewards can add more than $3,500 to your 529 account over 18 years at 5% interest. Calculated with a monthly deposit of $100/month at 1% interest in a savings account and 5% interested in a 529 account.") ?></p>
        </div>
        <div class="col-md-6"><img class="barchat_graph" src="public/img/barchatgraph.svg" alt=""></div>
      </div>
    </div>
  </section>

  <section class="section-5 pb-5">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h2 class="h2 text-primary mt-5"><?= __("Let's get social") ?></h2>
          <div class="icon-list d-flex">
            <a href="http://facebook.com" target="_blink" class="text-decoration-none mx-2"><img class="social-icon" src="public/icons/fb.svg" alt=""></a>
            <a href="http://facebook.com" target="_blink" class="text-decoration-none mx-2"><img class="social-icon" src="public/icons/twitter.svg" alt=""></a>
            <a href="http://facebook.com" target="_blink" class="text-decoration-none mx-2"><img class="social-icon" src="public/icons/linkedin.svg" alt=""></a>
            <a href="http://facebook.com" target="_blink" class="text-decoration-none mx-2"><img class="social-icon" src="public/icons/instagram.svg" alt=""></a>
          </div>
        </div>
        <div class="col-md-6">
          <div class="shape-list d-flex justify-content-between">
            <!-- <div class="box-shape border border-primary">
              <img class="shape-image p-3" src="public/icons/shape-1.svg" alt="">
            </div>
            <div class="box-shape border border-primary">
              <img class="shape-image p-3" src="public/icons/shape-2.svg" alt="">
            </div>
            <div class="box-shape border border-primary">
              <img class="shape-image p-3" src="public/icons/shape-3.svg" alt="">
            </div> -->
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php
require_once($ROOT . '/layouts/footer.php');
?>