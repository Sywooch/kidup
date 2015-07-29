<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
?>

<!--Login modal-->
<div class="modal modal-small fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true"
     data-backdrop="static">
    <div class="dismiss-modal" data-dismiss="modal"><i class="pe-7s-close-circle"></i></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <img src="<?= Url::to('@assets/img/logo/horizontal.png') ?>" height="30px">

                <h2 class="modal-title text-center">Del med dine venner</h2>
            </div>
            <div class="modal-body">
                <div class="social-area">
                    <p> 
                        De opslag som bliver delt på Facebook bliver lejet ud 54 % mere, end de opslag der ikke bliver delt. Så spred budskabet, og få gang i udlejningen. 
                        <br />
                        <a href="" class="btn btn-fill btn-social btn-facebook">
                        <i class="fa fa-facebook-square"></i> Del på Facebook</a>
                    
                    
                    <a href="" class="btn btn-fill btn-social btn-twitter">
                        <i class="fa fa-twitter"></i> Del på Twitter</a>
                        </br>
                        
                        
                        
                    </p>
                    
                </div>

                
            </div>
            <div class="modal-footer">
               
            </div>
        </div>
    </div>
</div>
