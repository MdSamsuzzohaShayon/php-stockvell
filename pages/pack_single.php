<?php
session_start(); // In every single page we should start our session at the top of our code
if (!isset($_SESSION['member_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: /login.php");
    exit();
}

if (!isset($_GET['stockvell_id'])) {
    header("Location: /index.php");
}

$member_email = $_SESSION['member_email'];
$member_id = $_SESSION['member_id'];
$stockvell_id = $_GET['stockvell_id'];

$is_admin = false;
if (isset($_SESSION['admin_id'])) $is_admin = true;
$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/config/lang.php");
require_once($ROOT . "/layouts/header.php");
require_once($ROOT . "/config/option-list.php");
require_once($ROOT . "/includes/pack_single.inc.php");
?>



<main class="pack_single">
    <section class="section-1">
        <div class="container">
            collapse
            member list
            make desc
            agreement
            more
            <div class="row">
                <div class="col-md-6">
                    <h1 class="h1"><?= $ssr_result['name']; ?></h1>
                    <p>Description</p>
                    <p><?= $ssr_result['category']; ?></p>
                    <?php 
                    if($is_admin === false){
                        if($is_mos) {
                            echo "<a href='' class='btn btn-warning'>Leave Pack</a>";
                        }else{
                            echo "<a href='' class='btn btn-warning'>Join Pack</a>";    
                        }
                    }
                    ?>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="row">
                        <div class="col bg-primary text-secondary m-4">
                            <p>Monthly Deposit</p>
                            <h3 class="h3">$<?= $ssr_result['payment']; ?></h3>
                        </div>
                        <div class="col bg-primary text-secondary m-4">
                            <p>Goal</p>
                            <h3 class="h3"><?= $ssr_result['goal']; ?></h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col bg-primary text-secondary m-4">
                            <p>Total Members</p>
                            <h3 class="h3"><?= $ssr_result['total_members']; ?></h3>
                        </div>
                        <div class="col bg-primary text-secondary m-4">
                            <p>Pack ID</p>
                            <h3 class="h3"><?= $ssr_result['id']; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <p>
                <h2 class="h2" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"> <span><img src="/public/icons/down-arrow.svg" height="30" alt=""></span> Members</h2>
                </p>
                <div class="collapse" id="collapseExample">
                    <?php
                    // psr = pending stockvell result 
                    // $psr_result - getting from dashboard.inc.php
                    if (count($ssr_result['members']) <= 0) {
                        echo "<div class='alert alert-warning'>No pack found</div>";
                    } else { ?>
                        <div class="table-responsive">
                            <table class="table table-bordered border-warning">
                                <thead class="bg-warning text-white border-primary">
                                    <tr>
                                        <th scope="col">#ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Country</th>
                                        <th scope="col">Profession</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // amr = all member result
                                    // id, firstname, surname, email, country, phone, gender, profession, interest, govt_id, source, role

                                    foreach ($ssr_result['members'] as $amr_key) {
                                        echo "
                                          <tr class='text-lowercase'>
                                             <th>" . $amr_key["id"] . "</th>
                                             <td>" . $amr_key["firstname"] . " " . $amr_key["surname"]  . "</td>
                                             <td>" . $amr_key["country"] . "</td>
                                             <td>" . $amr_key["profession"] . "</td>
                                          </tr>
                                       ";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php }                  ?>
                </div>
            </div>
            <div class="row">
                <p>
                <h2 class="h2" data-bs-toggle="collapse" href="#agreementCollapse" role="button" aria-expanded="false" aria-controls="agreementCollapse"> <span><img src="/public/icons/down-arrow.svg" height="30" alt=""></span> Agreement</h2>
                </p>
                <div class="collapse" id="agreementCollapse">
                    <?php echo $ssr_result['agreement']; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once($ROOT . "/layouts/footer.php"); ?>