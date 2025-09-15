<?php if(session()->getFlashdata('error_message')): ?>
    <!-- <div class="container-fluid"> -->
        <div class="alert alert-danger container-fluid" role="alert">
            <?php echo session()->getFlashdata('error_message'); ?>
        </div>
    <!-- </div> -->
<?php endif; ?>
    
<?php if(session()->getFlashdata('success_message')): ?>
    <!-- <div class="container"> -->
        <div class="alert alert-success container-fluid" role="alert">
            <?php echo session()->getFlashdata('success_message'); ?>
        <!-- </div> -->
    </div>
<?php endif; ?>
</div>