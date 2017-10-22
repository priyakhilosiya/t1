<div role="dialog" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <style>
        .well.nopad {
            padding: 0px;
        }
    </style>

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">
                    <i class="ico-cart"></i>
                    Order: <b><?php echo $orderDetails['ORD_REFERENCE'];?></b></h3>
            </div>
            <div class="modal-body">
				<?php $orderFlag=$orderDetails['ORD_PAYMENT_RECEIVED'];?>
				<?php if($orderFlag==0){?>
                <div class="alert alert-info">
                    This order is awaiting payment.
                </div>
				<a data-id="2188" data-route="<?=admin_path()?>users/orderReceived/<?=$orderDetails['ORD_ID'];?>" class="btn btn-primary btn-sm markPaymentReceived" href="javascript:void(0);">Mark Payment Received</a>
				<?php }?>
                

                <h3>Order Overview</h3>
                <style>
                    .order_overview b {
                        text-transform: uppercase;
                    }
                    
                    .order_overview .col-sm-4 {
                        margin-bottom: 10px;
                    }
                </style>
                <div class="p0 well bgcolor-white order_overview">
                    <div class="row">
                        <div class="col-sm-6 col-xs-6">
                            <b>First Name</b>
                            <br><?php echo $orderDetails['ATD_FNAME'];?>
                        </div>
                        <div class="col-sm-6 col-xs-6">
                            <b>Last Name</b>
                            <br> <?php echo $orderDetails['ATD_LNAME'];?>
                        </div>

                        <div class="col-sm-6 col-xs-6">
                            <b>Amount</b>
                            <br><?php echo $orderDetails['ORD_TOTAL_AMT'];?>
                        </div>

                        <div class="col-sm-6 col-xs-6">
                            <b>Reference</b>
                            <br> <?php echo $orderDetails['ORD_REFERENCE'];?>
                        </div>
                        <div class="col-sm-6 col-xs-6">
                            <b>Date</b>
                            <br><?php echo $orderDetails['ORD_CREATED'];?>
                        </div>
                        <div class="col-sm-6 col-xs-6">
                            <b>Email</b>
                            <br><?php echo $orderDetails['ATD_EMAIL'];?>
                        </div>

                    </div>
                </div>

                

                <h3>
                    Order Attendees
                </h3>
                <div class="well nopad bgcolor-white p0">

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td>
                                        <?php echo $orderDetails['ATD_FNAME']." ".$orderDetails['ATD_LNAME'];?>
                                    </td>
                                    <td>
                                        <?php echo $orderDetails['ATD_EMAIL'];?>
                                    </td>
                                    <td>
                                        <?php echo $orderDetails['ATD_T_NAME'];?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /end modal body-->

            <div class="modal-footer">
                <button class="btn modal-close btn-danger" data-dismiss="modal" type="button">Close</button>
            </div>
        </div>
        <!-- /end modal content-->
    </div>
</div>