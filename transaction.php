<?php
include 'include/header.php';
include 'configration/db.config.php';

$products = $pdo->prepare("SELECT * FROM orders WHERE user_id = :user_id");
$products->execute([':user_id' => $_SESSION['user_id']]);
$orders = $products->fetchAll(PDO::FETCH_OBJ);
?>
    <div id="page-content" class="page-content">
        <div class="banner">
            <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('assets/img/bg-header.jpg');">
                <div class="container">
                    <h1 class="pt-5">
                        Your Transactions
                    </h1>
                    <p class="lead">
                        Save time and leave the groceries to us.
                    </p>
                </div>
            </div>
        </div>

        <section id="cart">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="5%"></th>
                                        <th>Order-ID</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($_SESSION['username'])): ?>
                                        <?php if (count($orders) > 0): ?>
                                            <?php foreach ($orders as $order): ?>
                                                <tr>
                                                    <td width="5%"></td>
                                                    <td><?= $order->id; ?></td>
                                                    <td><?= $order->created_at; ?></td>
                                                    <td>Rp. <?= $order->Total_order_value; ?></td>
                                                    <td><?= $order->status; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <div class="alert alert-danger bg-danger text-white text-centre">
                                                <h1>error!</h1>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php include 'include/footer.php'; ?>