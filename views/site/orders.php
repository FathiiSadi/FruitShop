<div class="container mt-150 mb-150">

    <h1>All Orders</h1>
    <div class="row">
        <div class="col-md-12">
            <?php if(!empty($orders)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo $order['id'] ?></td>
                            <td><?php  echo $order['order_date'] ?></td>
                            <td>$<?php echo $order['total_amount'] ?></td>
                            <td><?php echo $order['status'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="alert alert-danger justify-content-center">
                <h1>no orders yet!</h1>
            </div>
            <?php endif; ?>

        </div>
    </div>



</div>