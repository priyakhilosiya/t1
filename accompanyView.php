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
                    <b>Accompany details</b></h3>
            </div>
            <div class="modal-body">
				<h3>
                    Accompany details
                </h3>
				<?php if(count($accmpDetails)>0){?>
				<div class="well nopad bgcolor-white p0">

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
							<?php foreach ($accmpDetails as $key=>$val){?>
                
                                <tr>
								
                                    <td>
                                        <?php echo $val['ACMP_FULLNAME']?>
                                    </td>
                                    <td>
                                        <?php echo $val['ACMP_EMAIL'];?>
                                    </td>
								 </tr>
								 	<?php }?>
                                
                            </tbody>
                        </table>
                    </div>
                </div>

				<?php }else{?>
				<div class="alert alert-info" role="alert">No records Found</div>
				<?php }?>
				
				
            </div>
            <!-- /end modal body-->

            <div class="modal-footer">
                <button class="btn modal-close btn-danger" data-dismiss="modal" type="button">Close</button>
            </div>
        </div>
        <!-- /end modal content-->
    </div>
</div>